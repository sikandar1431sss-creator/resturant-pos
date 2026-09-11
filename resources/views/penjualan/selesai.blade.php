@extends('layouts.master')

@section('title')
Order Completed & Invoice
@endsection

@push('css')
<style>
    .order-success-container {
        max-width: 620px;
        margin: 10px auto 40px auto;
    }

    .success-badge-glow {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);
        margin-bottom: 14px;
        animation: scalePop 0.4s ease;
    }

    @keyframes scalePop {
        0% { transform: scale(0.6); opacity: 0; }
        80% { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Modern Restaurant Receipt Card */
    .digital-receipt-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.1), 0 5px 15px rgba(0,0,0,0.04);
        position: relative;
    }

    .receipt-slip-inner {
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 24px 20px;
        margin-bottom: 24px;
        font-family: 'Consolas', 'Courier New', monospace;
    }

    .receipt-header-title {
        font-family: var(--font-main);
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 4px 0;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .receipt-divider {
        border-top: 1px dashed #cbd5e1;
        margin: 12px 0;
    }

    .receipt-item-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: #334155;
        margin-bottom: 6px;
    }

    .receipt-total-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .receipt-grand-total {
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        font-weight: 800;
        color: #ea580c;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 2px solid #0f172a;
    }

    /* Action Buttons Bar */
    .receipt-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-receipt-print {
        flex: 1;
        min-width: 170px;
        padding: 13px 18px;
        font-size: 14.5px;
        font-weight: 700;
        border-radius: 12px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-thermal {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
    }

    .btn-thermal:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(245, 158, 11, 0.45);
    }

    .btn-invoice-pdf {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
    }

    .btn-invoice-pdf:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(2, 132, 199, 0.45);
    }

    .btn-next-order {
        flex: 100%;
        margin-top: 4px;
        padding: 14px 20px;
        font-size: 16px;
        font-weight: 800;
        border-radius: 12px;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: #ffffff;
        border: none;
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.35);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-next-order:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(249, 115, 22, 0.45);
    }

    .shortcut-hint {
        font-size: 11px;
        opacity: 0.8;
        font-weight: normal;
        background: rgba(0, 0, 0, 0.15);
        padding: 2px 6px;
        border-radius: 4px;
    }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Order Completed</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="order-success-container text-center">
            <!-- Glowing Success Icon -->
            <div class="success-badge-glow">
                <i class="fa fa-check"></i>
            </div>
            
            <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0; letter-spacing: -0.02em;">
                Order Placed Successfully!
            </h2>
            <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;">
                Transaction recorded and sent to Kitchen Display System (KDS).
            </p>

            <!-- Digital Receipt Preview Card -->
            <div class="digital-receipt-card text-left">
                <div class="receipt-slip-inner">
                    <!-- Restaurant Header -->
                    <div class="text-center">
                        <div class="receipt-header-title">{{ $setting->nama_perusahaan ?? 'FAST FOOD RESTAURANT' }}</div>
                        <div style="font-size: 11px; color: #64748b;">{{ $setting->alamat ?? 'Restaurant Location' }}</div>
                        <div style="font-size: 11px; color: #64748b;">Phone: {{ $setting->telepon ?? 'N/A' }}</div>
                    </div>

                    <div class="receipt-divider"></div>

                    <!-- Order Metadata -->
                    <div style="font-size: 12px; color: #475569; display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Invoice: #INV-{{ tambah_nol_didepan($penjualan->id_penjualan ?? 0, 5) }}</span>
                        <span>{{ date('d-M-Y H:i') }}</span>
                    </div>
                    <div style="font-size: 12px; color: #475569; display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Cashier: {{ $penjualan->user->name ?? auth()->user()->name }}</span>
                        <span>Type: <strong style="color: #ea580c;">{{ $penjualan->tipe_order ?? 'Dine-In' }} {{ ($penjualan->tipe_order ?? 'Dine-In') == 'Dine-In' && !empty($penjualan->nomor_meja) ? '('.$penjualan->nomor_meja.')' : '' }}</strong></span>
                    </div>
                    @if(!empty($penjualan->member->nama))
                    <div style="font-size: 12px; color: #475569; display: flex; justify-content: space-between;">
                        <span>Customer:</span>
                        <span><strong>{{ $penjualan->member->nama }}</strong></span>
                    </div>
                    @endif

                    <div class="receipt-divider"></div>

                    <!-- Items List -->
                    <div style="margin: 10px 0;">
                        @if(isset($detail) && count($detail) > 0)
                            @foreach($detail as $item)
                            <div class="receipt-item-row">
                                <span style="font-weight: 600;">{{ $item->jumlah }}x {{ $item->produk->nama_produk ?? 'Item' }}</span>
                                <span>{{ format_currency($item->subtotal) }}</span>
                            </div>
                            @endforeach
                        @else
                            <div class="receipt-item-row">
                                <span>{{ $penjualan->total_item ?? 1 }}x Fast Food Combo</span>
                                <span>{{ format_currency($penjualan->total_harga ?? 0) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="receipt-divider"></div>

                    <!-- Financial Summary -->
                    <div class="receipt-total-row">
                        <span>Subtotal (Gross):</span>
                        <span>{{ format_currency($penjualan->total_harga ?? 0) }}</span>
                    </div>
                    @if(($penjualan->diskon ?? 0) > 0)
                    <div class="receipt-total-row" style="color: #059669;">
                        <span>Discount:</span>
                        <span>-{{ $penjualan->diskon }}%</span>
                    </div>
                    @endif

                    <div class="receipt-grand-total">
                        <span>NET PAYABLE:</span>
                        <span>{{ format_currency($penjualan->bayar ?? 0) }}</span>
                    </div>

                    <div class="receipt-divider"></div>

                    <div class="receipt-total-row" style="color: #475569; font-size: 13px;">
                        <span>Cash Tendered:</span>
                        <span>{{ format_currency($penjualan->diterima ?? $penjualan->bayar ?? 0) }}</span>
                    </div>
                    <div class="receipt-total-row" style="color: #059669; font-size: 13px;">
                        <span>Change Returned:</span>
                        <span>{{ format_currency(($penjualan->diterima ?? 0) - ($penjualan->bayar ?? 0)) }}</span>
                    </div>

                    <div class="text-center" style="margin-top: 14px; font-size: 11px; color: #94a3b8;">
                        *** THANK YOU FOR DINING WITH US! ***
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="receipt-actions">
                    <a href="{{ route('transaksi.nota_kecil') }}" target="_blank" class="btn-receipt-print btn-thermal">
                        Print KOT / Thermal Slip <span class="shortcut-hint">[P]</span>
                    </a>
                    
                    <a href="{{ route('transaksi.nota_besar') }}" target="_blank" class="btn-receipt-print btn-invoice-pdf">
                        Print PDF Invoice
                    </a>

                    <a href="{{ route('transaksi.baru') }}" class="btn-next-order" id="btnNextOrder">
                        <i class="fa fa-bolt"></i> Start Next Order <span class="shortcut-hint">[Enter]</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

    function notaKecil(url) {
        window.open(url, '_blank');
    }

    function notaBesar(url) {
        window.open(url, '_blank');
    }

    // Keyboard shortcuts: Press 'P' to print receipt in new tab, Press 'Enter' for next order
    $(document).on('keydown', function (e) {
        if (e.key === 'p' || e.key === 'P') {
            window.open('{{ route('transaksi.nota_kecil') }}', '_blank');
        } else if (e.key === 'Enter') {
            window.location.href = "{{ route('transaksi.baru') }}";
        }
    });
</script>
@endpush