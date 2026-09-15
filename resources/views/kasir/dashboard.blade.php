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
    </div>
</div>
@endsection