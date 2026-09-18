<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Penjualan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerLedgerController extends Controller
{
    public function index(Request $request)
    {
        $allMemberSales = Penjualan::whereNotNull('id_member')->get();

        $totalInvoiced = $allMemberSales->sum('bayar');
        $totalReceived = $allMemberSales->sum(function ($s) {
            return min($s->bayar, (float)$s->diterima);
        });
        $totalCustomerDue = max(0, $totalInvoiced - $totalReceived);

        $members = Member::orderBy('nama')->get();
        $customersWithDue = 0;

        foreach ($members as $m) {
            $custSales = $allMemberSales->where('id_member', $m->id_member);
            $custInvoiced = $custSales->sum('bayar');
            $custReceived = $custSales->sum(function ($s) {
                return min($s->bayar, (float)$s->diterima);
            });
            if (($custInvoiced - $custReceived) > 0.01) {
                $customersWithDue++;
            }
        }

        return view('ledger.customer.index', compact(
            'totalInvoiced',
            'totalReceived',
            'totalCustomerDue',
            'customersWithDue',
            'members'
        ));
    }

    public function data(Request $request)
    {
        $members = Member::orderBy('nama')->get();
        $allMemberSales = Penjualan::whereNotNull('id_member')->get();
        $statusFilter = $request->status ?? 'all';

        $data = [];

        foreach ($members as $m) {
            $custSales = $allMemberSales->where('id_member', $m->id_member);
            $orderCount = $custSales->count();

            $grossTotal = $custSales->sum('total_harga');
            $netInvoiced = $custSales->sum('bayar');
            $discountTotal = max(0, $grossTotal - $netInvoiced);
            $totalReceived = $custSales->sum(function ($s) {
                return min($s->bayar, (float)$s->diterima);
            });
            $dueBalance = max(0, $netInvoiced - $totalReceived);

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
                $payBtn = '<button type="button" onclick="openReceiveCustomerModal('. $m->id_member .', `'. e($m->nama) .'`, '. $dueBalance .')" class="btn-table-action btn-pay" title="Receive Customer Due"><i class="fa fa-money"></i></button>';
            }

            $statementUrl = route('ledger.customer.statement', $m->id_member);

            $actions = '
            <div class="table-actions-group">
                <a href="'. $statementUrl .'" class="btn-table-action btn-view" title="View Customer Ledger / Statement"><i class="fa fa-book"></i></a>
                ' . $payBtn . '
            </div>
            ';

            $data[] = [
                'customer_name' => '
                    <div>
                        <strong style="color: #0f172a; font-size: 13.5px;">' . e($m->nama) . '</strong>
                        ' . ($m->kode_member ? ' <span class="label label-success" style="font-size:10px;">' . e($m->kode_member) . '</span>' : '') . '
                        ' . ($m->telepon ? '<br><small class="text-muted"><i class="fa fa-phone"></i> ' . e($m->telepon) . '</small>' : '') . '
                    </div>
                ',
                'order_count' => '<span class="badge" style="background:#0f172a; color:#fff; font-weight:700; font-size:12px; padding:4px 8px;">' . number_format($orderCount) . '</span>',
                'gross_total' => format_currency($grossTotal),
                'discount' => format_currency($discountTotal),
                'net_invoiced' => '<strong style="color:#0f172a;">' . format_currency($netInvoiced) . '</strong>',
                'total_received' => '<span style="color:#15803d; font-weight:700;">' . format_currency($totalReceived) . '</span>',
                'due_balance' => '<strong style="color:' . ($dueBalance > 0.01 ? '#ef4444' : '#15803d') . '; font-size:13.5px;">' . format_currency($dueBalance) . '</strong>',
                'status' => $statusBadge,
                'aksi' => $actions
            ];
        }

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['customer_name', 'order_count', 'net_invoiced', 'total_received', 'due_balance', 'status', 'aksi'])
            ->make(true);
    }

    public function statement($id, Request $request)
    {
        $member = Member::findOrFail($id);
        $setting = Setting::first();

        $tanggalAwal = $request->tanggal_awal ?? date('Y-m-01');
        $tanggalAkhir = $request->tanggal_akhir ?? date('Y-m-d');

        $startDateTime = Carbon::parse($tanggalAwal)->startOfDay();
        $endDateTime = Carbon::parse($tanggalAkhir)->endOfDay();

        // 1. Calculate Opening Balance before start date
        $priorSales = Penjualan::where('id_member', $id)
            ->where('created_at', '<', $startDateTime)
            ->get();

        $priorInvoiced = $priorSales->sum('bayar');
        $priorReceived = $priorSales->sum(function ($s) {
            return min($s->bayar, (float)$s->diterima);
        });
        $openingBalance = max(0, $priorInvoiced - $priorReceived);

        // 2. Fetch all period sales
        $periodSales = Penjualan::where('id_member', $id)
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->orderBy('created_at', 'asc')
            ->get();

        $transactions = [];
        $runningBalance = $openingBalance;
        $invoicedInPeriod = 0;
        $receivedInPeriod = 0;

        foreach ($periodSales as $s) {
            $netInvoice = (float) $s->bayar;
            $invoicedInPeriod += $netInvoice;
            $runningBalance += $netInvoice;

            // Sales Invoice Record (Debit = increases customer receivable)
            $transactions[] = [
                'date' => $s->created_at,
                'ref_no' => 'INV-' . tambah_nol_didepan($s->id_penjualan, 5),
                'type' => 'Sales Invoice (' . ($s->tipe_order ?: 'Dine-In') . ')',
                'description' => 'Invoice for ' . ($s->nomor_meja ?: 'Takeaway/Delivery') . ' (' . $s->total_item . ' items)' . ($s->diskon > 0 ? ' [Disc ' . $s->diskon . '%]' : ''),
                'debit' => $netInvoice,
                'credit' => 0,
                'balance' => $runningBalance,
                'id_penjualan' => $s->id_penjualan,
                'print_url' => route('penjualan.nota_kecil', $s->id_penjualan)
            ];

            // If payment was received on this invoice
            $amtReceived = min($netInvoice, (float)$s->diterima);
            if ($amtReceived > 0) {
                $receivedInPeriod += $amtReceived;
                $runningBalance -= $amtReceived;

                $transactions[] = [
                    'date' => $s->updated_at ?: $s->created_at,
                    'ref_no' => 'PAY-INV-' . tambah_nol_didepan($s->id_penjualan, 5),
                    'type' => 'Payment Received (' . ucfirst($s->metode_pembayaran ?: 'Cash') . ')',
                    'description' => 'Payment received for Invoice #' . tambah_nol_didepan($s->id_penjualan, 5),
                    'debit' => 0,
                    'credit' => $amtReceived,
                    'balance' => $runningBalance,
                    'id_penjualan' => $s->id_penjualan,
                    'print_url' => route('penjualan.nota_kecil', $s->id_penjualan)
                ];
            }
        }

        $closingBalance = $runningBalance;

        // All-time balance
        $allSales = Penjualan::where('id_member', $id)->get();
        $allInvoiced = $allSales->sum('bayar');
        $allReceived = $allSales->sum(function ($s) {
            return min($s->bayar, (float)$s->diterima);
        });
        $currentTotalDue = max(0, $allInvoiced - $allReceived);

        return view('ledger.customer.statement', compact(
            'member',
            'setting',
            'tanggalAwal',
            'tanggalAkhir',
            'openingBalance',
            'invoicedInPeriod',
            'receivedInPeriod',
            'closingBalance',
            'currentTotalDue',
            'transactions'
        ));
    }

    public function receivePayment(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $amountReceived = (float) ($request->amount ?? $request->amount_received ?? 0);

        if ($amountReceived <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment amount must be greater than 0.'
            ], 422);
        }

        $unpaidSales = Penjualan::where('id_member', $id)
            ->where(function ($q) {
                $q->where('status_pembayaran', '!=', 'paid')
                  ->orWhereColumn('diterima', '<', 'bayar');
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $remainingPayment = $amountReceived;
        $settledCount = 0;

        foreach ($unpaidSales as $s) {
            if ($remainingPayment <= 0) break;

            $netTotal = (float) $s->bayar;
            $alreadyReceived = (float) $s->diterima;
            $due = max(0, $netTotal - $alreadyReceived);

            if ($due > 0) {
                $payForThis = min($due, $remainingPayment);
                $s->diterima += $payForThis;
                $s->sisa_bayar = max(0, $netTotal - $s->diterima);

                if ($s->sisa_bayar <= 0.01) {
                    $s->status_pembayaran = 'paid';
                } else {
                    $s->status_pembayaran = 'sebagian';
                }

                if (!empty($request->metode_pembayaran)) {
                    $s->metode_pembayaran = $request->metode_pembayaran;
                }

                $s->update();

                $remainingPayment -= $payForThis;
                $settledCount++;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Payment of ' . format_currency($amountReceived) . ' successfully received from ' . $member->nama . ' across ' . $settledCount . ' invoice(s).'
        ], 200);
    }
}
