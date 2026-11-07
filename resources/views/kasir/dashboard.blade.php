@extends('layouts.master')

@section('title')
    Cashier Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Cashier Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2 col-sm-12">
        <div class="restaurant-hero-banner text-center" style="padding: 36px 24px;">
            <div class="hero-live-clock" style="margin-bottom: 16px;">
                <i class="fa fa-clock-o text-orange"></i> <span class="live-time-display">--:--:--</span>
            </div>
            <h2 style="font-size: 28px; font-weight: 800; color: #ffffff; margin: 0 0 10px 0;">
                Restaurant Counter Terminal
            </h2>
            <p style="color: #cbd5e1; font-size: 15px; margin-bottom: 24px;">
                Operator: <strong>{{ auth()->user()->name }}</strong> &nbsp;|&nbsp; Status: <span class="label label-success" style="font-size: 11px;">Active</span>
            </p>

            <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('transaksi.baru') }}" class="btn-hero-primary" style="font-size: 16px; padding: 14px 28px;">
                    <i class="fa fa-plus-circle"></i> Create New Invoice
                </a>
                <a href="{{ route('penjualan.index') }}" class="btn-hero-outline" style="font-size: 16px; padding: 14px 24px;">
                    <i class="fa fa-list-alt"></i> Invoices List
                </a>
            </div>
        </div>

        <div class="row" style="margin-top: 20px;">
            <div class="col-sm-6">
                <div class="kpi-card text-center">
                    <div class="kpi-icon-wrapper kpi-icon-orange" style="margin: 0 auto 12px auto;">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <div class="kpi-card-body">
                        <h3>{{ format_currency($today_sales ?? 0) }}</h3>
                        <p>Your Sales Total Today</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="kpi-card text-center">
                    <div class="kpi-icon-wrapper kpi-icon-blue" style="margin: 0 auto 12px auto;">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="kpi-card-body">
                        <h3>{{ $today_orders ?? 0 }}</h3>
                        <p>Completed Transactions</p>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($tables) && $tables->count() > 0)
        <!-- Dining Table Status for Cashiers -->
        <div class="box" style="margin-top: 20px; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
            <div class="box-header with-border" style="background: #ffffff; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center;">
                <h4 class="box-title" style="font-weight: 800; font-size: 15px; color: #0f172a; margin: 0;">
                    <i class="fa fa-cutlery" style="color: #ea580c;"></i> Dining Tables Status
                </h4>
                <div style="display: flex; gap: 8px;">
                    <span class="badge" style="background: #dcfce7; color: #15803d; border: 1px solid #86efac; font-weight: 700; padding: 4px 10px; font-size: 11.5px;">
                        {{ $free_tables ?? 0 }} Free
                    </span>
                    <span class="badge" style="background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; font-weight: 700; padding: 4px 10px; font-size: 11.5px;">
                        {{ $occupied_tables ?? 0 }} Occupied
                    </span>
                </div>
            </div>
            <div class="box-body" style="padding: 18px; background: #f8fafc;">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(185px, 1fr)); gap: 12px;">
                    @foreach($tables as $t)
                        @php $isOcc = ($t->status === 'occupied'); @endphp
                        <div style="background: {{ $isOcc ? '#fffafa' : '#ffffff' }}; border: 1.5px solid {{ $isOcc ? '#fca5a5' : '#e2e8f0' }}; border-radius: 10px; padding: 12px 14px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: space-between; min-height: 96px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                                <div>
                                    <div style="font-weight: 800; font-size: 13.5px; color: #0f172a; line-height: 1.3;">{{ $t->nomor_meja }}</div>
                                    <div style="font-size: 11px; color: #64748b; font-weight: 600; margin-top: 2px;"><i class="fa fa-users text-muted"></i> {{ $t->kapasitas }} Seats</div>
                                </div>
                                <span style="font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 4px; text-transform: uppercase; background: {{ $isOcc ? '#fee2e2' : '#ecfdf5' }}; color: {{ $isOcc ? '#b91c1c' : '#059669' }}; border: 1px solid {{ $isOcc ? '#fca5a5' : '#a7f3d0' }}; white-space: nowrap;">
                                    <i class="fa fa-circle" style="font-size: 6px;"></i> {{ $isOcc ? 'Occupied' : 'Free' }}
                                </span>
                            </div>
                            <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid {{ $isOcc ? '#fecaca' : '#f1f5f9' }}; font-size: 11px; color: {{ $isOcc ? '#b91c1c' : '#059669' }}; font-weight: 600;">
                                <i class="fa {{ $isOcc ? 'fa-hourglass-half' : 'fa-check-circle' }}"></i> {{ $isOcc ? 'In Service' : 'Ready for Guests' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection