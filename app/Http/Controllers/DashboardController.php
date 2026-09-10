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
    public function index()
    {
        $kategori = Kategori::count();
        $produk = Produk::count();
        $supplier = Supplier::count();
        $member = Member::count();
        $penjualan = Penjualan::sum('diterima');
        $pengeluaran = Pengeluaran::sum('nominal');
        $pembelian = Pembelian::sum('bayar');

        // Today's metrics
        $today = date('Y-m-d');
        $today_sales = Penjualan::where('created_at', 'LIKE', "$today%")->sum('diterima');
        $today_orders = Penjualan::where('created_at', 'LIKE', "$today%")->count();
        $today_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "$today%")->sum('nominal');
        $today_pembelian = Pembelian::where('created_at', 'LIKE', "$today%")->sum('bayar');
        $today_profit = $today_sales - $today_pengeluaran - $today_pembelian;
        $today_avg_order = $today_orders > 0 ? round($today_sales / $today_orders) : 0;

        // Current shift determination
        $current_hour = (int) date('H');
        if ($current_hour >= 6 && $current_hour < 12) {
            $shift_name = 'Breakfast & Morning Shift';
        } elseif ($current_hour >= 12 && $current_hour < 17) {
            $shift_name = 'Lunch Shift';
        } elseif ($current_hour >= 17 && $current_hour < 23) {
            $shift_name = 'Dinner & Evening Shift';
        } else {
            $shift_name = 'Late Night Dining';
        }

        // Monthly chart data calculation
        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $data_tanggal = [];
        $data_pendapatan = [];
        $data_orders = [];

        $loop_date = $tanggal_awal;
        while (strtotime($loop_date) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int) substr($loop_date, 8, 2);

            $daily_sales = Penjualan::where('created_at', 'LIKE', "$loop_date%")->sum('bayar');
            $daily_purchase = Pembelian::where('created_at', 'LIKE', "$loop_date%")->sum('bayar');
            $daily_expense = Pengeluaran::where('created_at', 'LIKE', "$loop_date%")->sum('nominal');
            $daily_orders_count = Penjualan::where('created_at', 'LIKE', "$loop_date%")->count();

            $pendapatan = $daily_sales - $daily_purchase - $daily_expense;
            $data_pendapatan[] = (int) $pendapatan;
            $data_orders[] = $daily_orders_count;

            $loop_date = date('Y-m-d', strtotime('+1 day', strtotime($loop_date)));
        }

        // Top selling products / popular menu items
        $top_dishes = PenjualanDetail::select('id_produk', DB::raw('SUM(jumlah) as total_qty'), DB::raw('SUM(subtotal) as total_amount'))
            ->groupBy('id_produk')
            ->orderBy('total_qty', 'desc')
            ->with('produk.kategori')
            ->take(5)
            ->get();

        // If no sales detail yet, fetch latest dishes for dashboard showcase
        $featured_dishes = [];
        if ($top_dishes->isEmpty()) {
            $featured_dishes = Produk::with('kategori')->orderBy('id_produk', 'desc')->take(5)->get();
        }

        // Recent sales/orders
        $recent_sales = Penjualan::with('member')
            ->orderBy('id_penjualan', 'desc')
            ->take(6)
            ->get();

        if (auth()->check() && auth()->user()->level != 1) {
            return view('kasir.dashboard', compact(
                'today_sales',
                'today_orders',
                'shift_name'
            ));
        }

        return view('admin.dashboard', compact(
            'kategori',
            'produk',
            'supplier',
            'member',
            'penjualan',
            'pengeluaran',
            'pembelian',
            'tanggal_awal',
            'tanggal_akhir',
            'data_tanggal',
            'data_pendapatan',
            'data_orders',
            'today_sales',
            'today_orders',
            'today_pengeluaran',
            'today_profit',
            'today_avg_order',
            'shift_name',
            'top_dishes',
            'featured_dishes',
            'recent_sales'
        ));
    }
}