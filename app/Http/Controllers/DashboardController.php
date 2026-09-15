<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the comprehensive store overview dashboard.
     */
    public function index()
    {
        // 1. Profit Stat Cards for Standard Intervals
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd   = date('Y-m-d 23:59:59');
        $today_card = $this->calculateMetrics($todayStart, $todayEnd);

        $monthStart = date('Y-m-01 00:00:00');
        $monthEnd   = date('Y-m-t 23:59:59');
        $month_card = $this->calculateMetrics($monthStart, $monthEnd);

        $yearStart = date('Y-01-01 00:00:00');
        $yearEnd   = date('Y-12-31 23:59:59');
        $year_card = $this->calculateMetrics($yearStart, $yearEnd);

        $alltimeStart = '1970-01-01 00:00:00';
        $alltimeEnd   = date('Y-m-d 23:59:59');
        $alltime_card = $this->calculateMetrics($alltimeStart, $alltimeEnd);

        // 2. Operational & Balance Breakdown
        $total_invoices_all = (int) Penjualan::where('total_item', '>', 0)->count();
        $paid_invoices_all = (int) Penjualan::where('total_item', '>', 0)
            ->where(function ($q) {
                $q->where('status_pembayaran', 'paid')
                  ->orWhereRaw('diterima >= bayar');
            })->count();
        $unpaid_invoices_all = (int) Penjualan::where('total_item', '>', 0)
            ->where(function ($q) {
                $q->where('status_pembayaran', '!=', 'paid')
                  ->whereRaw('(diterima < bayar OR diterima IS NULL)');
            })->count();

        $total_sales_all = (float) Penjualan::where('total_item', '>', 0)->sum('bayar');
        $paid_sales_all = (float) Penjualan::where('total_item', '>', 0)
            ->sum(DB::raw('CASE WHEN status_pembayaran = "paid" THEN bayar ELSE COALESCE(diterima, 0) END'));
        $unpaid_sales_all = (float) Penjualan::where('total_item', '>', 0)
            ->sum(DB::raw('CASE WHEN status_pembayaran = "paid" THEN 0 ELSE (bayar - COALESCE(diterima, 0)) END'));

        $total_expenses_all = (float) Pengeluaran::sum('nominal');

        // Supplier Payable: Pending supplier dues
        $supplier_payable = (float) Pembelian::select(DB::raw('SUM(GREATEST(0, (total_harga - (COALESCE(diskon, 0) / 100 * total_harga)) - bayar)) as due'))->value('due') ?? 0;

        // Products & Inventory Alert
        $active_products = Produk::count();
        $low_stock_count = Produk::where('stok', '<=', 5)->count();

        // 3. Monthly Financial Growth (12 Months of current year)
        $currentYear = date('Y');
        $monthly_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthly_paid_sales = [];
        $monthly_unpaid_sales = [];
        $monthly_profit = [];
        $monthly_expenses = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthNum = str_pad($m, 2, '0', STR_PAD_LEFT);
            $mStart = "$currentYear-$monthNum-01 00:00:00";
            $mEnd   = date("Y-m-t 23:59:59", strtotime("$currentYear-$monthNum-01"));

            $mMetrics = $this->calculateMetrics($mStart, $mEnd);
            $monthly_paid_sales[] = (float) $mMetrics['paid_sales'];
            $monthly_unpaid_sales[] = (float) $mMetrics['unpaid_sales'];
            $monthly_profit[] = (float) $mMetrics['net_profit'];
            $monthly_expenses[] = (float) $mMetrics['expenses'];
        }

        // 4. Top Categories Revenue (Donut chart data)
        $category_sales = DB::table('kategori')
            ->leftJoin('produk', 'kategori.id_kategori', '=', 'produk.id_kategori')
            ->leftJoin('penjualan_detail', 'produk.id_produk', '=', 'penjualan_detail.id_produk')
            ->select('kategori.nama_kategori', DB::raw('COALESCE(SUM(penjualan_detail.subtotal), 0) as total_sales'))
            ->groupBy('kategori.id_kategori', 'kategori.nama_kategori')
            ->orderBy('total_sales', 'desc')
            ->take(6)
            ->get();

        $cat_labels = [];
        $cat_amounts = [];
        foreach ($category_sales as $cs) {
            $cat_labels[] = $cs->nama_kategori;
            $cat_amounts[] = (float) $cs->total_sales;
        }

        if (empty($cat_labels)) {
            $categories_all = Kategori::take(5)->pluck('nama_kategori');
            foreach ($categories_all as $cn) {
                $cat_labels[] = $cn;
                $cat_amounts[] = 0;
            }
        }

        // Cashier simplified view check (if cashier role)
        if (auth()->check() && auth()->user()->level != 1 && !auth()->user()->hasRole('admin')) {
            $today_sales = $today_card['paid_sales'];
            $today_orders = $today_card['invoices'];
            $shift_name = $this->getShiftName();

            return view('kasir.dashboard', compact(
                'today_sales',
                'today_orders',
                'shift_name'
            ));
        }

        return view('admin.dashboard', compact(
            'today_card',
            'month_card',
            'year_card',
            'alltime_card',
            'total_sales_all',
            'paid_sales_all',
            'unpaid_sales_all',
            'total_invoices_all',
            'paid_invoices_all',
            'unpaid_invoices_all',
            'total_expenses_all',
            'supplier_payable',
            'active_products',
            'low_stock_count',
            'monthly_labels',
            'monthly_paid_sales',
            'monthly_unpaid_sales',
            'monthly_profit',
            'monthly_expenses',
            'cat_labels',
            'cat_amounts',
            'currentYear'
        ));
    }

    /**
     * AJAX endpoint to fetch period specific metrics for top strip.
     */
    public function getPeriodData(Request $request)
    {
        $period = $request->get('period', 'today');
        $label = 'Today (' . date('d M Y') . ')';

        switch ($period) {
            case 'yesterday':
                $start = date('Y-m-d 00:00:00', strtotime('-1 day'));
                $end   = date('Y-m-d 23:59:59', strtotime('-1 day'));
                $label = 'Yesterday (' . date('d M Y', strtotime('-1 day')) . ')';
                break;

            case 'this_week':
                $start = date('Y-m-d 00:00:00', strtotime('monday this week'));
                $end   = date('Y-m-d 23:59:59', strtotime('sunday this week'));
                $label = 'This Week (' . date('d M', strtotime('monday this week')) . ' - ' . date('d M Y', strtotime('sunday this week')) . ')';
                break;

            case 'this_month':
                $start = date('Y-m-01 00:00:00');
                $end   = date('Y-m-t 23:59:59');
                $label = 'This Month (' . date('M Y') . ')';
                break;

            case 'last_month':
                $start = date('Y-m-01 00:00:00', strtotime('first day of last month'));
                $end   = date('Y-m-t 23:59:59', strtotime('last day of last month'));
                $label = 'Last Month (' . date('M Y', strtotime('last month')) . ')';
                break;

            case 'this_year':
                $start = date('Y-01-01 00:00:00');
                $end   = date('Y-12-31 23:59:59');
                $label = 'This Year (' . date('Y') . ')';
                break;

            case 'all_time':
                $start = '1970-01-01 00:00:00';
                $end   = date('Y-m-d 23:59:59');
                $label = 'All Time Overview';
                break;

            case 'custom':
                $sDate = $request->get('start_date', date('Y-m-d'));
                $eDate = $request->get('end_date', date('Y-m-d'));
                $start = "$sDate 00:00:00";
                $end   = "$eDate 23:59:59";
                $label = date('d M Y', strtotime($sDate)) . ' - ' . date('d M Y', strtotime($eDate));
                break;

            case 'today':
            default:
                $start = date('Y-m-d 00:00:00');
                $end   = date('Y-m-d 23:59:59');
                $label = 'Today (' . date('d M Y') . ')';
                break;
        }

        $metrics = $this->calculateMetrics($start, $end);
        $metrics['period_label'] = $label;
        $metrics['currency_symbol'] = get_currency_symbol();

        // Formatted strings for direct UI rendering
        $metrics['paid_sales_formatted'] = format_currency($metrics['paid_sales']);
        $metrics['unpaid_sales_formatted'] = format_currency($metrics['unpaid_sales']);
        $metrics['sales_formatted'] = format_currency($metrics['sales']);
        $metrics['cogs_formatted'] = format_currency($metrics['cogs']);
        $metrics['gross_profit_formatted'] = format_currency($metrics['gross_profit']);
        $metrics['expenses_formatted'] = format_currency($metrics['expenses']);
        $metrics['net_profit_formatted'] = format_currency($metrics['net_profit']);
        $metrics['discounts_formatted'] = format_currency($metrics['discounts']);

        return response()->json($metrics);
    }

    /**
     * Helper to calculate financial metrics between two timestamp boundaries.
     */
    private function calculateMetrics($start, $end)
    {
        // 1. Invoices Count Breakdown (Only real non-empty sales)
        $paid_invoices = (int) Penjualan::where('total_item', '>', 0)
            ->where(function ($q) {
                $q->where('status_pembayaran', 'paid')
                  ->orWhereRaw('diterima >= bayar');
            })
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $unpaid_invoices = (int) Penjualan::where('total_item', '>', 0)
            ->where(function ($q) {
                $q->where('status_pembayaran', '!=', 'paid')
                  ->whereRaw('(diterima < bayar OR diterima IS NULL)');
            })
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $invoices = $paid_invoices + $unpaid_invoices;

        // 2. Sales Amounts (Paid Cash vs Unpaid Due vs Total Billed)
        $paid_sales = (float) Penjualan::where('total_item', '>', 0)
            ->whereBetween('created_at', [$start, $end])
            ->sum(DB::raw('CASE WHEN status_pembayaran = "paid" THEN bayar ELSE COALESCE(diterima, 0) END'));

        $unpaid_sales = (float) Penjualan::where('total_item', '>', 0)
            ->whereBetween('created_at', [$start, $end])
            ->sum(DB::raw('CASE WHEN status_pembayaran = "paid" THEN 0 ELSE (bayar - COALESCE(diterima, 0)) END'));

        $total_sales = $paid_sales + $unpaid_sales;

        // 3. Cost of Goods Sold (COGS) based on recipe/dish item cost
        // Realized COGS (from paid invoices)
        $paid_cogs = (float) PenjualanDetail::join('produk', 'penjualan_detail.id_produk', '=', 'produk.id_produk')
            ->join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            ->where('penjualan.total_item', '>', 0)
            ->where(function ($q) {
                $q->where('penjualan.status_pembayaran', 'paid')
                  ->orWhereRaw('penjualan.diterima >= penjualan.bayar');
            })
            ->whereBetween('penjualan.created_at', [$start, $end])
            ->sum(DB::raw('penjualan_detail.jumlah * COALESCE(produk.harga_beli, 0)'));

        // Total Billed COGS (all non-empty invoices)
        $total_cogs = (float) PenjualanDetail::join('produk', 'penjualan_detail.id_produk', '=', 'produk.id_produk')
            ->join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            ->where('penjualan.total_item', '>', 0)
            ->whereBetween('penjualan.created_at', [$start, $end])
            ->sum(DB::raw('penjualan_detail.jumlah * COALESCE(produk.harga_beli, 0)'));

        // 4. Expenses
        $expenses = (float) Pengeluaran::whereBetween('created_at', [$start, $end])->sum('nominal');

        // 5. Discounts
        $discounts = (float) Penjualan::where('total_item', '>', 0)
            ->whereBetween('created_at', [$start, $end])
            ->sum(DB::raw('CASE WHEN total_harga > bayar THEN total_harga - bayar ELSE 0 END'));

        // 6. Net Profit (Realized from Paid Sales minus Paid COGS minus Expenses)
        $gross_profit = $paid_sales - $paid_cogs;
        $net_profit = $gross_profit - $expenses;

        // Unpaid expected profit
        $unpaid_cogs = max(0, $total_cogs - $paid_cogs);
        $unpaid_gross_profit = $unpaid_sales - $unpaid_cogs;

        return [
            'sales' => $total_sales,
            'paid_sales' => $paid_sales,
            'unpaid_sales' => $unpaid_sales,
            'invoices' => $invoices,
            'paid_invoices' => $paid_invoices,
            'unpaid_invoices' => $unpaid_invoices,
            'cogs' => $paid_cogs,
            'total_cogs' => $total_cogs,
            'gross_profit' => $gross_profit,
            'expenses' => $expenses,
            'net_profit' => $net_profit,
            'unpaid_gross_profit' => $unpaid_gross_profit,
            'discounts' => $discounts,
        ];
    }

    private function getShiftName()
    {
        $current_hour = (int) date('H');
        if ($current_hour >= 6 && $current_hour < 12) {
            return 'Breakfast & Morning Shift';
        } elseif ($current_hour >= 12 && $current_hour < 17) {
            return 'Lunch Shift';
        } elseif ($current_hour >= 17 && $current_hour < 23) {
            return 'Dinner & Evening Shift';
        } else {
            return 'Late Night Dining';
        }
    }
}