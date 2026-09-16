<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class LaporanController extends Controller
{
    /**
     * Comprehensive Sales Report with KPI Metrics, Invoices Breakdown, Item Sales, and Channel Share.
     */
    public function penjualan(Request $request)
    {
        $tanggalAwal = $request->get('tanggal_awal', date('Y-m-01'));
        $tanggalAkhir = $request->get('tanggal_akhir', date('Y-m-d'));
        $orderType = $request->get('tipe_order', 'all');
        $paymentStatus = $request->get('status_pembayaran', 'all');
        $userId = $request->get('id_user', 'all');

        $query = Penjualan::with(['member', 'user', 'detail.produk'])
            ->where('total_item', '>', 0)
            ->whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir);

        if ($orderType !== 'all') {
            $query->where('tipe_order', $orderType);
        }

        if ($paymentStatus === 'paid') {
            $query->where(function($q) {
                $q->where('status_pembayaran', 'paid')
                  ->orWhereRaw('diterima >= bayar');
            });
        } elseif ($paymentStatus === 'unpaid') {
            $query->where(function($q) {
                $q->where('status_pembayaran', '!=', 'paid')
                  ->whereRaw('(diterima < bayar OR diterima IS NULL)');
            });
        }

        if ($userId !== 'all') {
            $query->where('id_user', $userId);
        }

        $invoices = $query->orderBy('id_penjualan', 'desc')->get();

        // Summary KPIs
        $totalSales = (float) $invoices->sum('bayar');
        $paidSales = (float) $invoices->sum(function($inv) {
            return ($inv->status_pembayaran === 'paid' || $inv->diterima >= $inv->bayar) 
                ? $inv->bayar 
                : (float) ($inv->diterima ?? 0);
        });
        $unpaidSales = (float) $invoices->sum(function($inv) {
            return ($inv->status_pembayaran === 'paid' || $inv->diterima >= $inv->bayar)
                ? 0
                : max(0, (float) $inv->bayar - (float) ($inv->diterima ?? 0));
        });
        $totalOrders = (int) $invoices->count();
        $totalItemsSold = (int) $invoices->sum('total_item');
        $totalDiscounts = (float) $invoices->sum(function($inv) {
            return $inv->total_harga > $inv->bayar ? ($inv->total_harga - $inv->bayar) : 0;
        });
        $avgOrderValue = $totalOrders > 0 ? round($totalSales / $totalOrders) : 0;

        // Item-wise Top Sold Dishes
        $itemSales = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('produk', 'penjualan_detail.id_produk', '=', 'produk.id_produk')
            ->leftJoin('kategori', 'produk.id_kategori', '=', 'kategori.id_kategori')
            ->where('penjualan.total_item', '>', 0)
            ->whereDate('penjualan.created_at', '>=', $tanggalAwal)
            ->whereDate('penjualan.created_at', '<=', $tanggalAkhir);

        if ($orderType !== 'all') {
            $itemSales->where('penjualan.tipe_order', $orderType);
        }
        if ($userId !== 'all') {
            $itemSales->where('penjualan.id_user', $userId);
        }

        $itemBreakdown = $itemSales->select(
            'produk.id_produk',
            'produk.nama_produk',
            'produk.kode_produk',
            'kategori.nama_kategori',
            DB::raw('AVG(penjualan_detail.harga_jual) as unit_price'),
            DB::raw('SUM(penjualan_detail.jumlah) as total_qty'),
            DB::raw('SUM(penjualan_detail.subtotal) as total_revenue')
        )
        ->groupBy('produk.id_produk', 'produk.nama_produk', 'produk.kode_produk', 'kategori.nama_kategori')
        ->orderBy('total_revenue', 'desc')
        ->get();

        // Channel Breakdown Stats
        $dineInCount = $invoices->where('tipe_order', 'Dine-In')->count();
        $dineInSales = $invoices->where('tipe_order', 'Dine-In')->sum('bayar');
        $takeawayCount = $invoices->where('tipe_order', 'Takeaway')->count();
        $takeawaySales = $invoices->where('tipe_order', 'Takeaway')->sum('bayar');
        $deliveryCount = $invoices->where('tipe_order', 'Delivery')->count();
        $deliverySales = $invoices->where('tipe_order', 'Delivery')->sum('bayar');

        $users = User::orderBy('name')->get();

        return view('laporan.penjualan', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'orderType',
            'paymentStatus',
            'userId',
            'invoices',
            'totalSales',
            'paidSales',
            'unpaidSales',
            'totalOrders',
            'totalItemsSold',
            'totalDiscounts',
            'avgOrderValue',
            'itemBreakdown',
            'dineInCount',
            'dineInSales',
            'takeawayCount',
            'takeawaySales',
            'deliveryCount',
            'deliverySales',
            'users'
        ));
    }

    /**
     * Comprehensive Purchase (Stock-In) Report with Supplier Balances & Raw Materials Inflow.
     */
    public function pembelian(Request $request)
    {
        $tanggalAwal = $request->get('tanggal_awal', date('Y-m-01'));
        $tanggalAkhir = $request->get('tanggal_akhir', date('Y-m-d'));
        $idSupplier = $request->get('id_supplier', 'all');
        $paymentStatus = $request->get('payment_status', 'all');

        $query = Pembelian::with(['supplier', 'detail.produk'])
            ->whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir);

        if ($idSupplier !== 'all') {
            $query->where('id_supplier', $idSupplier);
        }

        if ($paymentStatus === 'paid') {
            $query->whereRaw('bayar >= (total_harga - (COALESCE(diskon, 0) / 100 * total_harga))');
        } elseif ($paymentStatus === 'due') {
            $query->whereRaw('bayar < (total_harga - (COALESCE(diskon, 0) / 100 * total_harga))');
        }

        $purchases = $query->orderBy('id_pembelian', 'desc')->get();

        // Summary KPIs
        $totalPurchases = (float) $purchases->sum(function($p) {
            $discountAmount = ((float) ($p->diskon ?? 0)) / 100 * (float) $p->total_harga;
            return (float) $p->total_harga - $discountAmount;
        });
        $paidPurchases = (float) $purchases->sum('bayar');
        $duePurchases = (float) $purchases->sum(function($p) {
            $net = (float) $p->total_harga - (((float) ($p->diskon ?? 0)) / 100 * (float) $p->total_harga);
            return max(0, $net - (float) $p->bayar);
        });
        $totalOrders = (int) $purchases->count();
        $totalItemsIn = (int) $purchases->sum('total_item');

        // Item-wise Purchase Breakdown
        $itemPurchases = DB::table('pembelian_detail')
            ->join('pembelian', 'pembelian_detail.id_pembelian', '=', 'pembelian.id_pembelian')
            ->join('produk', 'pembelian_detail.id_produk', '=', 'produk.id_produk')
            ->leftJoin('supplier', 'pembelian.id_supplier', '=', 'supplier.id_supplier')
            ->whereDate('pembelian.created_at', '>=', $tanggalAwal)
            ->whereDate('pembelian.created_at', '<=', $tanggalAkhir);

        if ($idSupplier !== 'all') {
            $itemPurchases->where('pembelian.id_supplier', $idSupplier);
        }

        $itemBreakdown = $itemPurchases->select(
            'produk.id_produk',
            'produk.nama_produk',
            'produk.kode_produk',
            'supplier.nama as nama_supplier',
            DB::raw('AVG(pembelian_detail.harga_beli) as avg_unit_cost'),
            DB::raw('SUM(pembelian_detail.jumlah) as total_qty'),
            DB::raw('SUM(pembelian_detail.subtotal) as total_cost')
        )
        ->groupBy('produk.id_produk', 'produk.nama_produk', 'produk.kode_produk', 'supplier.nama')
        ->orderBy('total_cost', 'desc')
        ->get();

        // Supplier Balances & Dues
        $supplierSummary = DB::table('pembelian')
            ->join('supplier', 'pembelian.id_supplier', '=', 'supplier.id_supplier')
            ->whereDate('pembelian.created_at', '>=', $tanggalAwal)
            ->whereDate('pembelian.created_at', '<=', $tanggalAkhir)
            ->select(
                'supplier.id_supplier',
                'supplier.nama',
                'supplier.telepon',
                DB::raw('COUNT(pembelian.id_pembelian) as po_count'),
                DB::raw('SUM(pembelian.total_harga - (COALESCE(pembelian.diskon, 0) / 100 * pembelian.total_harga)) as total_billed'),
                DB::raw('SUM(pembelian.bayar) as total_paid'),
                DB::raw('SUM(GREATEST(0, (pembelian.total_harga - (COALESCE(pembelian.diskon, 0) / 100 * pembelian.total_harga)) - pembelian.bayar)) as total_due')
            )
            ->groupBy('supplier.id_supplier', 'supplier.nama', 'supplier.telepon')
            ->orderBy('total_due', 'desc')
            ->get();

        $suppliers = Supplier::orderBy('nama')->get();

        return view('laporan.pembelian', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'idSupplier',
            'paymentStatus',
            'purchases',
            'totalPurchases',
            'paidPurchases',
            'duePurchases',
            'totalOrders',
            'totalItemsIn',
            'itemBreakdown',
            'supplierSummary',
            'suppliers'
        ));
    }

    /**
     * Daily Income & Profit/Loss Overview Report.
     */
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        // Summary totals for top KPI cards
        $totalSales = (float) Penjualan::where('total_item', '>', 0)
            ->whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir)
            ->sum('bayar');

        $totalPurchases = (float) Pembelian::whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir)
            ->sum('bayar');

        $totalExpenses = (float) Pengeluaran::whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir)
            ->sum('nominal');

        $netIncome = $totalSales - $totalPurchases - $totalExpenses;

        return view('laporan.index', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'totalSales',
            'totalPurchases',
            'totalExpenses',
            'netIncome'
        ));
    }

    public function getData($awal, $akhir)
    {
        $no = 1;
        $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;
        $total_penjualan_sum = 0;
        $total_pembelian_sum = 0;
        $total_pengeluaran_sum = 0;

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $total_penjualan = (float) Penjualan::where('total_item', '>', 0)->where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pembelian = (float) Pembelian::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pengeluaran = (float) Pengeluaran::where('created_at', 'LIKE', "%$tanggal%")->sum('nominal');

            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $total_pendapatan += $pendapatan;
            $total_penjualan_sum += $total_penjualan;
            $total_pembelian_sum += $total_pembelian;
            $total_pengeluaran_sum += $total_pengeluaran;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['penjualan'] = format_currency($total_penjualan);
            $row['pembelian'] = format_currency($total_pembelian);
            $row['pengeluaran'] = format_currency($total_pengeluaran);
            $row['pendapatan'] = format_currency($pendapatan);

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '<strong>Total Summary</strong>',
            'penjualan' => '<strong>' . format_currency($total_penjualan_sum) . '</strong>',
            'pembelian' => '<strong>' . format_currency($total_pembelian_sum) . '</strong>',
            'pengeluaran' => '<strong>' . format_currency($total_pengeluaran_sum) . '</strong>',
            'pendapatan' => '<strong>' . format_currency($total_pendapatan) . '</strong>',
        ];

        return $data;
    }

    public function data($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);

        return datatables()
            ->of($data)
            ->escapeColumns([])
            ->make(true);
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');
        
        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }
}
