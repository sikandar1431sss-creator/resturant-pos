<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SupplierLedgerController extends Controller
{
    public function index(Request $request)
    {
        $allPurchases = Pembelian::all();
        
        $totalPurchases = $allPurchases->sum(function ($p) {
            $discountAmt = ($p->diskon ?? 0) / 100 * $p->total_harga;
            return $p->total_harga - $discountAmt;
        });

        $totalPaid = $allPurchases->sum('bayar');
        $totalDue = max(0, $totalPurchases - $totalPaid);

        $suppliers = Supplier::orderBy('nama')->get();
        $suppliersWithDue = 0;

        foreach ($suppliers as $s) {
            $supPurchases = $allPurchases->where('id_supplier', $s->id_supplier);
            $supBilled = $supPurchases->sum(function ($p) {
                $discountAmt = ($p->diskon ?? 0) / 100 * $p->total_harga;
                return $p->total_harga - $discountAmt;
            });
            $supPaid = $supPurchases->sum('bayar');
            if (($supBilled - $supPaid) > 0.01) {
                $suppliersWithDue++;
            }
        }

        return view('ledger.supplier.index', compact(
            'totalPurchases',
            'totalPaid',
            'totalDue',
            'suppliersWithDue',
            'suppliers'
        ));
    }

    public function data(Request $request)
    {
        $suppliers = Supplier::orderBy('nama')->get();
        $allPurchases = Pembelian::all();
        $statusFilter = $request->status ?? 'all';

        $data = [];

        foreach ($suppliers as $s) {
            $supPurchases = $allPurchases->where('id_supplier', $s->id_supplier);
            $poCount = $supPurchases->count();
            
            $grossTotal = $supPurchases->sum('total_harga');
            $netTotal = $supPurchases->sum(function ($p) {
                $discountAmt = ($p->diskon ?? 0) / 100 * $p->total_harga;
                return $p->total_harga - $discountAmt;
            });
            $discountTotal = $grossTotal - $netTotal;
            $totalPaid = $supPurchases->sum('bayar');
            $dueBalance = max(0, $netTotal - $totalPaid);

            if ($statusFilter === 'due' && $dueBalance <= 0.01) {
                continue;
            }
            if ($statusFilter === 'settled' && $dueBalance > 0.01) {
                continue;
            }

            $statusBadge = ($dueBalance <= 0.01)
                ? '<span class="label label-success" style="font-size:11px; padding:3px 8px; border-radius:4px; font-weight:700;">Settled (Clear)</span>'
                : '<span class="label label-danger" style="font-size:11px; padding:3px 8px; border-radius:4px; font-weight:700;">Due: ' . format_currency($dueBalance) . '</span>';

            $payBtn = '';
            if ($dueBalance > 0.01) {
                $payBtn = '<button type="button" onclick="openPaySupplierModal('. $s->id_supplier .', `'. e($s->nama) .'`, '. $dueBalance .')" class="btn-table-action btn-pay" title="Pay Remaining Due"><i class="fa fa-money"></i></button>';
            }

            $statementUrl = route('ledger.supplier.statement', $s->id_supplier);

            $actions = '
            <div class="table-actions-group">
                <a href="'. $statementUrl .'" class="btn-table-action btn-view" title="View Account Ledger / Statement"><i class="fa fa-book"></i></a>
                ' . $payBtn . '
            </div>
            ';

            $data[] = [
                'supplier_name' => '
                    <div>
                        <strong style="color: #0f172a; font-size: 13.5px;">' . e($s->nama) . '</strong>
                        ' . ($s->telepon ? '<br><small class="text-muted"><i class="fa fa-phone"></i> ' . e($s->telepon) . '</small>' : '') . '
                    </div>
                ',
                'po_count' => '<span class="badge" style="background:#0f172a; color:#fff; font-weight:700; font-size:12px; padding:4px 8px;">' . number_format($poCount) . '</span>',
                'gross_total' => format_currency($grossTotal),
                'discount' => format_currency($discountTotal),
                'net_total' => '<strong style="color:#0f172a;">' . format_currency($netTotal) . '</strong>',
                'total_paid' => '<span style="color:#15803d; font-weight:700;">' . format_currency($totalPaid) . '</span>',
                'due_balance' => '<strong style="color:' . ($dueBalance > 0.01 ? '#ef4444' : '#15803d') . '; font-size:13.5px;">' . format_currency($dueBalance) . '</strong>',
                'status' => $statusBadge,
                'aksi' => $actions
            ];
        }

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['supplier_name', 'po_count', 'net_total', 'total_paid', 'due_balance', 'status', 'aksi'])
            ->make(true);
    }

    public function statement($id, Request $request)
    {
        $supplier = Supplier::findOrFail($id);
        $setting = Setting::first();

        $tanggalAwal = $request->tanggal_awal ?? date('Y-m-01');
        $tanggalAkhir = $request->tanggal_akhir ?? date('Y-m-d');

        $startDateTime = Carbon::parse($tanggalAwal)->startOfDay();
        $endDateTime = Carbon::parse($tanggalAkhir)->endOfDay();

        // 1. Calculate Opening Balance before start date
        $priorPurchases = Pembelian::where('id_supplier', $id)
            ->where('created_at', '<', $startDateTime)
            ->get();

        $priorBilled = $priorPurchases->sum(function ($p) {
            $discountAmt = ($p->diskon ?? 0) / 100 * $p->total_harga;
            return $p->total_harga - $discountAmt;
        });
        $priorPaid = $priorPurchases->sum('bayar');
        $openingBalance = max(0, $priorBilled - $priorPaid);

        // 2. Fetch all period purchases
        $periodPurchases = Pembelian::where('id_supplier', $id)
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->orderBy('created_at', 'asc')
            ->get();

        $transactions = [];
        $runningBalance = $openingBalance;
        $invoicedInPeriod = 0;
        $paidInPeriod = 0;

        foreach ($periodPurchases as $p) {
            $netBill = $p->total_harga - (($p->diskon ?? 0) / 100 * $p->total_harga);
            $invoicedInPeriod += $netBill;
            $runningBalance += $netBill;

            // Purchase Order Record (Credit = increases payable)
            $transactions[] = [
                'date' => $p->created_at,
                'ref_no' => 'PO-' . tambah_nol_didepan($p->id_pembelian, 4),
                'type' => 'Purchase Order (Stock-In)',
                'description' => 'Stock-In Purchase (' . $p->total_item . ' items)' . ($p->diskon > 0 ? ' [Disc ' . $p->diskon . '%]' : ''),
                'debit' => 0,
                'credit' => $netBill,
                'balance' => $runningBalance,
                'id_pembelian' => $p->id_pembelian,
                'print_url' => route('pembelian.nota_kecil', $p->id_pembelian)
            ];

            // If payment was made on this purchase
            if ($p->bayar > 0) {
                $paidInPeriod += $p->bayar;
                $runningBalance -= $p->bayar;

                $transactions[] = [
                    'date' => $p->updated_at ?: $p->created_at,
                    'ref_no' => 'PAY-PO-' . tambah_nol_didepan($p->id_pembelian, 4),
                    'type' => 'Supplier Payment',
                    'description' => 'Payment for PO #' . tambah_nol_didepan($p->id_pembelian, 4),
                    'debit' => $p->bayar,
                    'credit' => 0,
                    'balance' => $runningBalance,
                    'id_pembelian' => $p->id_pembelian,
                    'print_url' => route('pembelian.nota_kecil', $p->id_pembelian)
                ];
            }
        }

        $closingBalance = $runningBalance;

        // All-time balance
        $allPurchases = Pembelian::where('id_supplier', $id)->get();
        $allBilled = $allPurchases->sum(function ($p) {
            $discountAmt = ($p->diskon ?? 0) / 100 * $p->total_harga;
            return $p->total_harga - $discountAmt;
        });
        $allPaid = $allPurchases->sum('bayar');
        $currentTotalDue = max(0, $allBilled - $allPaid);

        return view('ledger.supplier.statement', compact(
            'supplier',
            'setting',
            'tanggalAwal',
            'tanggalAkhir',
            'openingBalance',
            'invoicedInPeriod',
            'paidInPeriod',
            'closingBalance',
            'currentTotalDue',
            'transactions'
        ));
    }

    public function recordPayment(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $amountToPay = (float) ($request->amount ?? $request->amount_paid ?? 0);

        if ($amountToPay <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment amount must be greater than 0.'
            ], 422);
        }

        $unpaidPurchases = Pembelian::where('id_supplier', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        $remainingPayment = $amountToPay;
        $settledCount = 0;

        foreach ($unpaidPurchases as $p) {
            if ($remainingPayment <= 0) break;

            $netTotal = $p->total_harga - (($p->diskon ?? 0) / 100 * $p->total_harga);
            $due = max(0, $netTotal - $p->bayar);

            if ($due > 0) {
                $payForThis = min($due, $remainingPayment);
                $p->bayar += $payForThis;
                $p->update();

                $remainingPayment -= $payForThis;
                $settledCount++;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Payment of ' . format_currency($amountToPay) . ' successfully recorded for ' . $supplier->nama . ' across ' . $settledCount . ' PO(s).'
        ], 200);
    }
}
