@extends('layouts.master')

@section('title')
    Restaurant Operations Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
<!-- Hero Welcome & Operational Status Banner -->
<div class="restaurant-hero-banner">
    <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
        <div class="col-md-8 col-sm-12 hero-content">
            <h2 class="hero-title">
                Welcome back, {{ auth()->user()->name }}! 👋
            </h2>
            <div class="hero-subtitle">
                <span class="hero-live-clock">
                    <i class="fa fa-clock-o text-orange"></i> <span class="live-time-display">--:--:--</span>
                </span>
                <span><i class="fa fa-calendar-check-o"></i> {{ tanggal_indonesia(date('Y-m-d'), true) }}</span>
                <span><i class="fa fa-cutlery"></i> {{ $shift_name }}</span>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 text-right hero-actions" style="margin-top: 10px;">
            <a href="{{ route('transaksi.baru') }}" class="btn-hero-primary">
                <i class="fa fa-plus-circle"></i> Create New Invoice
            </a>
            <a href="{{ route('produk.index') }}" class="btn-hero-outline">
                <i class="fa fa-plus-circle"></i> Add Dish
            </a>
        </div>
    </div>
</div>

<!-- Quick Action Dock -->
<div class="quick-action-dock">
    <div class="quick-action-dock-title">Quick Actions & Terminal Shortcuts</div>
    <div class="quick-action-grid">
        <a href="{{ route('transaksi.baru') }}" class="quick-action-btn">
            <i class="fa fa-plus-circle" style="color: #f97316;"></i>
            <span>Create New Invoice</span>
        </a>
        <a href="{{ route('penjualan.index') }}" class="quick-action-btn">
            <i class="fa fa-list-alt" style="color: #0284c7;"></i>
            <span>Invoices List</span>
        </a>
        <a href="{{ route('produk.index') }}" class="quick-action-btn">
            <i class="fa fa-cutlery" style="color: #10b981;"></i>
            <span>Menu</span>
        </a>
        <a href="{{ route('pengeluaran.index') }}" class="quick-action-btn">
            <i class="fa fa-credit-card" style="color: #ef4444;"></i>
            <span>Record Expense</span>
        </a>
        <a href="{{ route('pembelian.index') }}" class="quick-action-btn">
            <i class="fa fa-cart-arrow-down" style="color: #8b5cf6;"></i>
            <span>Purchases</span>
        </a>
        <a href="{{ route('laporan.index') }}" class="quick-action-btn">
            <i class="fa fa-line-chart" style="color: #f59e0b;"></i>
            <span>Sales Reports</span>
        </a>
    </div>
</div>

<!-- KPI Metric Cards (Row 1) -->
<div class="row">
    <!-- Today's Sales -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="kpi-card">
            <div class="kpi-card-header">
                <div class="kpi-icon-wrapper kpi-icon-orange">
                    <i class="fa fa-dollar"></i>
                </div>
                <span class="kpi-badge kpi-badge-success">
                    <i class="fa fa-arrow-up"></i> Today
                </span>
            </div>
            <div class="kpi-card-body">
                <h3>{{ format_currency($today_sales) }}</h3>
                <p>Today's Sales Revenue</p>
            </div>
            <div class="kpi-card-footer">
                <span>{{ $today_orders }} orders placed today</span>
                <a href="{{ route('penjualan.index') }}">View <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Today's Profit -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="kpi-card">
            <div class="kpi-card-header">
                <div class="kpi-icon-wrapper kpi-icon-green">
                    <i class="fa fa-line-chart"></i>
                </div>
                <span class="kpi-badge kpi-badge-success">
                    Net Est.
                </span>
            </div>
            <div class="kpi-card-body">
                <h3>{{ format_currency($today_profit) }}</h3>
                <p>Today's Net Profit</p>
            </div>
            <div class="kpi-card-footer">
                <span>Sales minus expenses</span>
                <a href="{{ route('laporan.index') }}">Report <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Total Food Dishes -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="kpi-card">
            <div class="kpi-card-header">
                <div class="kpi-icon-wrapper kpi-icon-blue">
                    <i class="fa fa-cutlery"></i>
                </div>
                <span class="kpi-badge kpi-badge-neutral">
                    {{ $kategori }} Categories
                </span>
            </div>
            <div class="kpi-card-body">
                <h3>{{ $produk }}</h3>
                <p>Active Menu Items</p>
            </div>
            <div class="kpi-card-footer">
                <span>Food & beverage items</span>
                <a href="{{ route('produk.index') }}">Menu <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Diners & VIP Members -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="kpi-card">
            <div class="kpi-card-header">
                <div class="kpi-icon-wrapper kpi-icon-purple">
                    <i class="fa fa-id-card-o"></i>
                </div>
                <span class="kpi-badge kpi-badge-neutral">
                    Loyalty
                </span>
            </div>
            <div class="kpi-card-body">
                <h3>{{ $member }}</h3>
                <p>Registered Diners</p>
            </div>
            <div class="kpi-card-footer">
                <span>VIP customer database</span>
                <a href="{{ route('member.index') }}">Members <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Main Operations & Analytics Row -->
<div class="row">
    <!-- Sales & Revenue Trend Chart -->
    <div class="col-lg-8 col-md-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="modern-card-title">
                    <i class="fa fa-area-chart"></i> Monthly Income & Sales Trend
                </h3>
                <span class="label" style="background: #f1f5f9; color: #475569; font-size: 11px; padding: 5px 10px; border-radius: 6px;">
                    {{ tanggal_indonesia($tanggal_awal, false) }} - {{ tanggal_indonesia($tanggal_akhir, false) }}
                </span>
            </div>
            <div class="modern-card-body">
                <div class="chart-container" style="position: relative; height: 280px;">
                    <canvas id="restaurantSalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Dine-In Table Status Floor -->
    <div class="col-lg-4 col-md-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="modern-card-title">
                    <i class="fa fa-th"></i> Live Floor & Tables
                </h3>
                <a href="{{ route('transaksi.baru') }}" class="btn btn-xs btn-default" style="border-radius: 6px; font-weight: 600;">
                    <i class="fa fa-plus"></i> Seat Table
                </a>
            </div>
            <div class="modern-card-body" style="padding: 16px;">
                <div class="table-grid">
                    <div class="table-badge-card occupied">
                        <div class="table-badge-num">T-01</div>
                        <div class="table-badge-status">Occupied</div>
                        <div class="table-badge-time"><i class="fa fa-clock-o"></i> 32m · 4p</div>
                    </div>
                    <div class="table-badge-card vacant">
                        <div class="table-badge-num">T-02</div>
                        <div class="table-badge-status">Vacant</div>
                        <div class="table-badge-time">Ready · 2p</div>
                    </div>
                    <div class="table-badge-card occupied">
                        <div class="table-badge-num">T-03</div>
                        <div class="table-badge-status">Occupied</div>
                        <div class="table-badge-time"><i class="fa fa-clock-o"></i> 18m · 6p</div>
                    </div>
                    <div class="table-badge-card billed">
                        <div class="table-badge-num">T-04</div>
                        <div class="table-badge-status">Billed</div>
                        <div class="table-badge-time"><i class="fa fa-credit-card"></i> Pay · 4p</div>
                    </div>
                    <div class="table-badge-card vacant">
                        <div class="table-badge-num">T-05</div>
                        <div class="table-badge-status">Vacant</div>
                        <div class="table-badge-time">Ready · 4p</div>
                    </div>
                    <div class="table-badge-card reserved">
                        <div class="table-badge-num">T-06</div>
                        <div class="table-badge-status">Reserved</div>
                        <div class="table-badge-time">08:00 PM · 8p</div>
                    </div>
                </div>

                <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-around; font-size: 11.5px; color: #64748b;">
                    <span><span style="color: #ea580c; font-weight: 700;">●</span> 2 Occupied</span>
                    <span><span style="color: #059669; font-weight: 700;">●</span> 2 Vacant</span>
                    <span><span style="color: #0284c7; font-weight: 700;">●</span> 1 Billed</span>
                    <span><span style="color: #7c3aed; font-weight: 700;">●</span> 1 Reserved</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Section: Popular Menu Items & Live Recent Orders -->
<div class="row">
    <!-- Top Selling Dishes / Menu Highlights -->
    <div class="col-lg-5 col-md-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="modern-card-title">
                    <i class="fa fa-star text-orange" style="color: #f59e0b;"></i> Popular Dishes & Highlights
                </h3>
                <a href="{{ route('produk.index') }}" class="text-orange" style="font-size: 12px; font-weight: 600; color: #f97316;">
                    View All Menu →
                </a>
            </div>
            <div class="modern-card-body" style="padding-top: 8px;">
                @if(!$top_dishes->isEmpty())
                    @foreach($top_dishes as $dish)
                    <div class="dish-item-row">
                        <div class="dish-item-info">
                            <div class="dish-item-icon">
                                <i class="fa fa-cutlery"></i>
                            </div>
                            <div>
                                <h4 class="dish-item-name">{{ $dish->produk->nama_produk ?? 'Menu Item' }}</h4>
                                <span class="dish-item-cat">{{ $dish->produk->kategori->nama_kategori ?? 'Main Course' }}</span>
                            </div>
                        </div>
                        <div class="dish-item-stats">
                            <div class="dish-item-qty">{{ $dish->total_qty }} orders</div>
                            <div class="dish-item-price">{{ format_currency($dish->total_amount) }}</div>
                        </div>
                    </div>
                    @endforeach
                @elseif(!empty($featured_dishes) && count($featured_dishes) > 0)
                    @foreach($featured_dishes as $item)
                    <div class="dish-item-row">
                        <div class="dish-item-info">
                            <div class="dish-item-icon">
                                <i class="fa fa-cutlery"></i>
                            </div>
                            <div>
                                <h4 class="dish-item-name">{{ $item->nama_produk }}</h4>
                                <span class="dish-item-cat">{{ $item->kategori->nama_kategori ?? 'Dish' }}</span>
                            </div>
                        </div>
                        <div class="dish-item-stats">
                            <div class="dish-item-qty">Stock: {{ $item->stok }}</div>
                            <div class="dish-item-price">{{ format_currency($item->harga_jual) }}</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center" style="padding: 30px 20px; color: #94a3b8;">
                        <i class="fa fa-cutlery" style="font-size: 32px; margin-bottom: 8px; display: block;"></i>
                        <p style="font-size: 13px; margin: 0;">No menu dishes added yet.</p>
                        <a href="{{ route('produk.index') }}" class="btn btn-sm btn-primary" style="margin-top: 10px; border-radius: 8px;">
                            <i class="fa fa-plus"></i> Add First Dish
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Live Recent Orders / Transactions -->
    <div class="col-lg-7 col-md-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="modern-card-title">
                    <i class="fa fa-receipt"></i> Recent Orders & Receipts
                </h3>
                <a href="{{ route('penjualan.index') }}" class="text-orange" style="font-size: 12px; font-weight: 600; color: #f97316;">
                    View Order Ledger →
                </a>
            </div>
            <div class="modern-card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date & Time</th>
                                <th>Diner / Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_sales as $sale)
                            <tr>
                                <td>
                                    <strong style="color: #0f172a;">#INV-{{ tambah_nol_didepan($sale->id_penjualan, 6) }}</strong>
                                </td>
                                <td style="color: #64748b; font-size: 12px;">
                                    {{ date('M d, H:i', strtotime($sale->created_at)) }}
                                </td>
                                <td>
                                    <span style="font-weight: 600;">{{ $sale->member->nama ?? 'Walk-In Guest' }}</span>
                                </td>
                                <td>
                                    <strong style="color: #0f172a;">{{ format_currency($sale->bayar) }}</strong>
                                </td>
                                <td>
                                    <span class="badge-order-status badge-completed">
                                        <i class="fa fa-check-circle"></i> Paid
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center" style="padding: 30px; color: #94a3b8;">
                                    <i class="fa fa-inbox" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
                                    No customer orders placed yet today.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- ChartJS -->
<script src="{{ asset('AdminLTE-2/bower_components/chart.js/Chart.js') }}"></script>
<script>
$(function() {
    var ctx = document.getElementById('restaurantSalesChart').getContext('2d');
    var chart = new Chart(ctx);

    var dataTanggal = {!! json_encode($data_tanggal) !!};
    var dataPendapatan = {!! json_encode($data_pendapatan) !!};

    var chartData = {
        labels: dataTanggal.map(function(d) { return 'Day ' + d; }),
        datasets: [
            {
                label: 'Net Income',
                fillColor: 'rgba(249, 115, 22, 0.15)',
                strokeColor: '#f97316',
                pointColor: '#ea580c',
                pointStrokeColor: '#ffffff',
                pointHighlightFill: '#ffffff',
                pointHighlightStroke: '#ea580c',
                data: dataPendapatan
            }
        ]
    };

    var chartOptions = {
        scaleShowGridLines: true,
        scaleGridLineColor: 'rgba(0,0,0,.04)',
        scaleGridLineWidth: 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: false,
        bezierCurve: true,
        bezierCurveTension: 0.4,
        pointDot: true,
        pointDotRadius: 4,
        pointDotStrokeWidth: 2,
        pointHitDetectionRadius: 20,
        datasetStroke: true,
        datasetStrokeWidth: 3,
        datasetFill: true,
        responsive: true,
        maintainAspectRatio: false
    };

    chart.Line(chartData, chartOptions);
});
</script>
@endpush