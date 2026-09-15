<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\ShiftClosing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShiftClosingController extends Controller
{
    public function index()
    {
        $currentShift = ShiftClosing::where('id_user', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        $history = ShiftClosing::with('user')->orderBy('id', 'desc')->take(30)->get();

        return view('shift.index', compact('currentShift', 'history'));
    }

    public function startShift(Request $request)
    {
        $request->validate([
            'opening_cash' => 'required|numeric|min:0',
        ]);

        // Check if there is already an open shift
        $open = ShiftClosing::where('id_user', Auth::id())->where('status', 'open')->first();
        if ($open) {
            return response()->json(['error' => 'You already have an open shift in progress!'], 422);
        }

        $shift = ShiftClosing::create([
            'id_user' => Auth::id(),
            'opened_at' => Carbon::now(),
            'opening_cash' => (float)$request->opening_cash,
            'status' => 'open'
        ]);

        return response()->json(['status' => 'success', 'message' => 'Shift opened successfully!']);
    }

    public function getSummary()
    {
        $user = Auth::user();
        $shift = ShiftClosing::where('id_user', $user->id)->where('status', 'open')->latest()->first();
        
        $openedAt = $shift ? $shift->opened_at : Carbon::today()->startOfDay();

        // Calculate sales during this shift period
        $sales = Penjualan::where('id_user', $user->id)
            ->where('created_at', '>=', $openedAt)
            ->where('diterima', '>', 0)
            ->get();

        $totalCash = $sales->where('metode_pembayaran', 'cash')->sum('bayar');
        // if metode_pembayaran is null or cash
        $totalCash += $sales->whereNull('metode_pembayaran')->sum('bayar');
        $totalCard = $sales->where('metode_pembayaran', 'card')->sum('bayar');
        $totalOnline = $sales->whereIn('metode_pembayaran', ['online', 'jazzcash', 'easypaisa', 'bank'])->sum('bayar');
        $totalSales = $sales->sum('bayar');

        // Expenses
        $totalExpense = Pengeluaran::where('created_at', '>=', $openedAt)->sum('nominal');

        $openingCash = $shift ? $shift->opening_cash : 0;
        $expectedCash = $openingCash + $totalCash - $totalExpense;

        return response()->json([
            'shift_id' => $shift ? $shift->id : null,
            'opened_at' => $openedAt ? date('d M Y, h:i A', strtotime($openedAt)) : '-',
            'opening_cash' => $openingCash,
            'total_cash_sales' => $totalCash,
            'total_card_sales' => $totalCard,
            'total_online_sales' => $totalOnline,
            'total_sales' => $totalSales,
            'total_orders' => $sales->count(),
            'total_expense' => $totalExpense,
            'expected_cash' => $expectedCash,
            'formatted_opening_cash' => format_currency($openingCash),
            'formatted_cash_sales' => format_currency($totalCash),
            'formatted_card_sales' => format_currency($totalCard),
            'formatted_online_sales' => format_currency($totalOnline),
            'formatted_total_sales' => format_currency($totalSales),
            'formatted_expense' => format_currency($totalExpense),
            'formatted_expected_cash' => format_currency($expectedCash),
        ]);
    }

    public function closeShift(Request $request)
    {
        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $shift = ShiftClosing::where('id_user', $user->id)->where('status', 'open')->latest()->first();

        $openedAt = $shift ? $shift->opened_at : Carbon::today()->startOfDay();

        $sales = Penjualan::where('id_user', $user->id)
            ->where('created_at', '>=', $openedAt)
            ->where('diterima', '>', 0)
            ->get();

        $totalCash = $sales->where('metode_pembayaran', 'cash')->sum('bayar');
        $totalCash += $sales->whereNull('metode_pembayaran')->sum('bayar');
        $totalCard = $sales->where('metode_pembayaran', 'card')->sum('bayar');
        $totalOnline = $sales->whereIn('metode_pembayaran', ['online', 'jazzcash', 'easypaisa', 'bank'])->sum('bayar');

        $totalExpense = Pengeluaran::where('created_at', '>=', $openedAt)->sum('nominal');
        $openingCash = $shift ? $shift->opening_cash : 0;
        $expectedCash = $openingCash + $totalCash - $totalExpense;
        $actualCash = (float)$request->actual_cash;
        $difference = $actualCash - $expectedCash;

        if (!$shift) {
            $shift = new ShiftClosing();
            $shift->id_user = $user->id;
            $shift->opened_at = $openedAt;
            $shift->opening_cash = 0;
        }

        $shift->closed_at = Carbon::now();
        $shift->total_cash_sales = $totalCash;
        $shift->total_card_sales = $totalCard;
        $shift->total_online_sales = $totalOnline;
        $shift->total_expense = $totalExpense;
        $shift->expected_cash = $expectedCash;
        $shift->actual_cash = $actualCash;
        $shift->difference = $difference;
        $shift->catatan = $request->catatan;
        $shift->status = 'closed';
        $shift->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Shift closed successfully! Z-Report generated.',
            'shift_id' => $shift->id
        ]);
    }
}
