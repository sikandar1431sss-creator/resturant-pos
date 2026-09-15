@extends('layouts.master')

@section('title')
    Dashboard Overview
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    .content-header {
        display: none !important;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .dash-wrapper {
        margin: 5px -5px 30px -5px;
    }

    /* Top Dashboard Header */
    .dash-header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 18px;
        padding: 0 4px;
    }

    .dash-title-group h2 {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 3px 0;
        letter-spacing: -0.02em;
    }

    .dash-title-group p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
        font-weight: 500;
    }

    /* Period Filter Pills Toolbar */
    .dash-filter-toolbar {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        background: #ffffff;
        padding: 4px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .period-btn {
        background: #ffffff;
        border: 1px solid transparent;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 13px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
        text-decoration: none !important;
    }

    .period-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .period-btn.active {
        background: #1e293b !important;
        color: #ffffff !important;
        border-color: #1e293b !important;
    }

    /* Hide/Seek Privacy Button */
    .btn-hide-seek {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-left: 4px;
    }

    .btn-hide-seek:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .btn-hide-seek.is-masked {
        background: #fef2f2;
        border-color: #fca5a5;
        color: #ef4444;
    }

    /* Top Filtered Period Summary Strip */
    .period-strip-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .period-strip-grid {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .strip-metric-item {
        flex: 1;
        min-width: 110px;
    }

    .strip-label {
        font-size: 10px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .strip-value {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .strip-link {
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        margin-top: 2px;
        text-decoration: none !important;
    }

    /* Stat Cards Grid (Rows 1 & 2) */
    .stat-overview-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 18px 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        position: relative;
    }

    .stat-overview-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    /* Top Accent Colors */
    .border-top-green { border-top: 3.5px solid #10b981 !important; }
    .border-top-blue { border-top: 3.5px solid #3b82f6 !important; }
    .border-top-purple { border-top: 3.5px solid #8b5cf6 !important; }
    .border-top-dark { border-top: 3.5px solid #1e293b !important; }
    .border-top-red { border-top: 3.5px solid #ef4444 !important; }
    .border-top-amber { border-top: 3.5px solid #f59e0b !important; }
    .border-top-slate { border-top: 3.5px solid #64748b !important; }
    .border-top-teal { border-top: 3.5px solid #06b6d4 !important; }

    .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .card-title-text {
        font-size: 11.5px;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin: 0;
    }

    .card-badge-pill {
        font-size: 10.5px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 4px;
        text-transform: uppercase;
    }

    .badge-green-soft { background: #dcfce7; color: #15803d; }
    .badge-blue-soft { background: #dbeafe; color: #1d4ed8; }
    .badge-purple-soft { background: #f3e8ff; color: #7e22ce; }
    .badge-dark-soft { background: #f1f5f9; color: #0f172a; }
    .badge-red-soft { background: #fee2e2; color: #b91c1c; }
    .badge-amber-soft { background: #fef3c7; color: #b45309; }
    .badge-slate-soft { background: #f1f5f9; color: #475569; }
    .badge-teal-soft { background: #ccfbf1; color: #0f766e; }

    .card-big-value {
        font-size: 24px;
        font-weight: 900;
        color: #0f172a;
        margin: 6px 0 10px 0;
        line-height: 1.15;
    }

    .card-footer-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11.5px;
        color: #64748b;
        font-weight: 500;
        border-top: 1px solid #f8fafc;
        padding-top: 8px;
        margin-top: 6px;
    }

    .card-footer-link {
        font-size: 12px;
        font-weight: 700;
        color: #0284c7;
        text-decoration: none !important;
    }

    .card-footer-link:hover {
        color: #0369a1;
    }

    /* Charts Section */
    .chart-box-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px 22px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .chart-box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .chart-box-title {
        font-size: 14.5px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-legend-badge {
        font-size: 11.5px;
        font-weight: 600;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        padding: 4px 10px;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
<div class="dash-wrapper">
    
    <!-- 1. Top Header with Period Filters and Hide/Show Button -->
    <div class="dash-header-bar">
        <div class="dash-title-group">
            <h2>Dashboard Overview</h2>
        </div>

        <div class="dash-filter-toolbar">
            <button type="button" class="period-btn active" data-period="today">Today</button>
            <button type="button" class="period-btn" data-period="yesterday">Yesterday</button>
            <button type="button" class="period-btn" data-period="this_week">This Week</button>
            <button type="button" class="period-btn" data-period="this_month">This Month</button>
            <button type="button" class="period-btn" data-period="last_month">Last Month</button>
            <button type="button" class="period-btn" data-period="this_year">This Year</button>
            <button type="button" class="period-btn" data-period="all_time">All Time</button>
            <button type="button" class="period-btn" onclick="openCustomDateModal()">Custom Date</button>
            
            <!-- Hide/Seek Privacy Toggle -->
            <button type="button" id="btnHideSeek" class="btn-hide-seek" onclick="toggleHideSeek()" title="Hide or reveal sensitive amounts">
                <i class="fa fa-eye"></i> <span id="hideSeekLabel">Hide/Show</span>
            </button>
        </div>
    </div>

    <!-- 2. Selected Period Live Strip (Clearly showing Paid vs Unpaid) -->
    <div class="period-strip-card">
        <div class="period-strip-grid">
            <!-- Selected Period -->
            <div class="strip-metric-item" style="min-width: 140px;">
                <div class="strip-label">SELECTED PERIOD</div>
                <div class="strip-value" id="stripPeriodLabel" style="color: #2563eb;">
                    Today ({{ date('d M Y') }})
                </div>
            </div>

            <!-- Paid Sales (Cash) -->
            <div class="strip-metric-item">
                <div class="strip-label">PAID SALES (CASH)</div>
                <div class="strip-value maskable-val" id="stripPaidSales" data-real="{{ format_currency($today_card['paid_sales']) }}" style="color: #10b981;">
                    {{ format_currency($today_card['paid_sales']) }}
                </div>
            </div>

            <!-- Unpaid Due (Sales) -->
            <div class="strip-metric-item">
                <div class="strip-label">UNPAID SALES (DUE)</div>
                <div class="strip-value maskable-val" id="stripUnpaidSales" data-real="{{ format_currency($today_card['unpaid_sales']) }}" style="color: #ef4444;">
                    {{ format_currency($today_card['unpaid_sales']) }}
                </div>
            </div>

            <!-- Total Billed -->
            <div class="strip-metric-item">
                <div class="strip-label">TOTAL BILLED</div>
                <div class="strip-value maskable-val" id="stripSales" data-real="{{ format_currency($today_card['sales']) }}">
                    {{ format_currency($today_card['sales']) }}
                </div>
            </div>

            <!-- Purchase Cost -->
            <div class="strip-metric-item">
                <div class="strip-label">PURCHASE COST</div>
                <div class="strip-value maskable-val" id="stripCogs" data-real="{{ format_currency($today_card['cogs']) }}" style="color: #64748b;">
                    {{ format_currency($today_card['cogs']) }}
                </div>
            </div>

            <!-- Expenses -->
            <div class="strip-metric-item">
                <div class="strip-label">EXPENSES</div>
                <div class="strip-value maskable-val" id="stripExpenses" data-real="{{ format_currency($today_card['expenses']) }}" style="color: #ef4444;">
                    {{ format_currency($today_card['expenses']) }}
                </div>
                <a href="{{ route('pengeluaran.index') }}" class="strip-link" style="color: #ef4444;">View Expenses &rarr;</a>
            </div>

            <!-- Realized Net Profit -->
            <div class="strip-metric-item">
                <div class="strip-label">REALIZED NET PROFIT</div>
                <div class="strip-value maskable-val" id="stripNetProfit" data-real="{{ format_currency($today_card['net_profit']) }}" style="color: #10b981;">
                    {{ format_currency($today_card['net_profit']) }}
                </div>
            </div>

            <!-- Invoices Breakdown (Paid & Unpaid) -->
            <div class="strip-metric-item" style="min-width: 130px;">
                <div class="strip-label">INVOICES COUNT</div>
                <div class="strip-value" id="stripInvoices" style="display:flex; gap:5px; align-items:center;">
                    <span class="badge" style="background:#dcfce7; color:#15803d; font-size:11px; padding:3px 6px;">{{ $today_card['paid_invoices'] }} Paid</span>
                    <span class="badge" style="background:#fee2e2; color:#b91c1c; font-size:11px; padding:3px 6px;">{{ $today_card['unpaid_invoices'] }} Unpaid</span>
                </div>
            </div>

            <!-- Discounts Deducted -->
            <div class="strip-metric-item">
                <div class="strip-label">DISCOUNTS DEDUCTED</div>
                <div class="strip-value maskable-val" id="stripDiscounts" data-real="{{ format_currency($today_card['discounts']) }}" style="color: #ef4444;">
                    {{ format_currency($today_card['discounts']) }}
                </div>
                <a href="{{ route('penjualan.index') }}" class="strip-link" style="color: #ef4444;">View Invoices &rarr;</a>
            </div>
        </div>
    </div>

    <!-- 3. ROW 1: PROFIT STAT CARDS (4 Cards with Colored Top Borders, Based on Paid Sales) -->
    <div class="row">
        <!-- Today's Profit -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="stat-overview-card border-top-green">
                <div class="card-header-flex">
                    <span class="card-title-text">TODAY'S NET PROFIT</span>
                    <span class="card-badge-pill badge-green-soft">TODAY</span>
                </div>
                <div class="card-big-value maskable-val" style="color: #10b981;" data-real="{{ format_currency($today_card['net_profit']) }}">
                    {{ format_currency($today_card['net_profit']) }}
                </div>
                <div class="card-footer-flex">
                    <span>Paid: <strong class="maskable-val" data-real="{{ format_currency($today_card['paid_sales']) }}">{{ format_currency($today_card['paid_sales']) }}</strong></span>
                    <span>Due: <strong class="maskable-val" style="color:#ef4444;" data-real="{{ format_currency($today_card['unpaid_sales']) }}">{{ format_currency($today_card['unpaid_sales']) }}</strong></span>
                </div>
                <div style="font-size:11px; color:#64748b; margin-top:4px;">
                    Invoices: <strong>{{ $today_card['paid_invoices'] }} Paid</strong> / <strong style="color:{{ $today_card['unpaid_invoices'] > 0 ? '#ef4444' : '#64748b' }};">{{ $today_card['unpaid_invoices'] }} Unpaid</strong>
                </div>
            </div>
        </div>

        <!-- This Month's Profit -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="stat-overview-card border-top-blue">
                <div class="card-header-flex">
                    <span class="card-title-text">THIS MONTH'S PROFIT</span>
                    <span class="card-badge-pill badge-blue-soft">THIS MONTH</span>
                </div>
                <div class="card-big-value maskable-val" style="color: #10b981;" data-real="{{ format_currency($month_card['net_profit']) }}">
                    {{ format_currency($month_card['net_profit']) }}
                </div>
                <div class="card-footer-flex">
                    <span>Paid: <strong class="maskable-val" data-real="{{ format_currency($month_card['paid_sales']) }}">{{ format_currency($month_card['paid_sales']) }}</strong></span>
                    <span>Due: <strong class="maskable-val" style="color:#ef4444;" data-real="{{ format_currency($month_card['unpaid_sales']) }}">{{ format_currency($month_card['unpaid_sales']) }}</strong></span>
                </div>
                <div style="font-size:11px; color:#64748b; margin-top:4px;">
                    Invoices: <strong>{{ $month_card['paid_invoices'] }} Paid</strong> / <strong style="color:{{ $month_card['unpaid_invoices'] > 0 ? '#ef4444' : '#64748b' }};">{{ $month_card['unpaid_invoices'] }} Unpaid</strong>
                </div>
            </div>
        </div>

        <!-- This Year's Profit -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="stat-overview-card border-top-purple">
                <div class="card-header-flex">
                    <span class="card-title-text">THIS YEAR'S PROFIT</span>
                    <span class="card-badge-pill badge-purple-soft">THIS YEAR</span>
                </div>
                <div class="card-big-value maskable-val" style="color: #10b981;" data-real="{{ format_currency($year_card['net_profit']) }}">
                    {{ format_currency($year_card['net_profit']) }}
                </div>
                <div class="card-footer-flex">
                    <span>Paid: <strong class="maskable-val" data-real="{{ format_currency($year_card['paid_sales']) }}">{{ format_currency($year_card['paid_sales']) }}</strong></span>
                    <span>Due: <strong class="maskable-val" style="color:#ef4444;" data-real="{{ format_currency($year_card['unpaid_sales']) }}">{{ format_currency($year_card['unpaid_sales']) }}</strong></span>
                </div>
                <div style="font-size:11px; color:#64748b; margin-top:4px;">
                    Invoices: <strong>{{ $year_card['paid_invoices'] }} Paid</strong> / <strong style="color:{{ $year_card['unpaid_invoices'] > 0 ? '#ef4444' : '#64748b' }};">{{ $year_card['unpaid_invoices'] }} Unpaid</strong>
                </div>
            </div>
        </div>

        <!-- All-Time Net Profit -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="stat-overview-card border-top-dark">
                <div class="card-header-flex">
                    <span class="card-title-text">ALL-TIME NET PROFIT</span>
                    <span class="card-badge-pill badge-dark-soft">ALL-TIME</span>
                </div>
                <div class="card-big-value maskable-val" style="color: #10b981;" data-real="{{ format_currency($alltime_card['net_profit']) }}">
                    {{ format_currency($alltime_card['net_profit']) }}
                </div>
                <div class="card-footer-flex">
                    <span>Paid: <strong class="maskable-val" data-real="{{ format_currency($alltime_card['paid_sales']) }}">{{ format_currency($alltime_card['paid_sales']) }}</strong></span>
                    <span>Due: <strong class="maskable-val" style="color:#ef4444;" data-real="{{ format_currency($alltime_card['unpaid_sales']) }}">{{ format_currency($alltime_card['unpaid_sales']) }}</strong></span>
                </div>
                <div style="font-size:11px; color:#64748b; margin-top:4px;">
                    Invoices: <strong>{{ $alltime_card['paid_invoices'] }} Paid</strong> / <strong style="color:{{ $alltime_card['unpaid_invoices'] > 0 ? '#ef4444' : '#64748b' }};">{{ $alltime_card['unpaid_invoices'] }} Unpaid</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. ROW 2: OPERATIONAL & BALANCE CARDS (Unpaid Dues, Paid Sales, Supplier Payable, Total Expenses) -->
    <div class="row">
        <!-- Customer Unpaid Invoices Due -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="stat-overview-card border-top-red">
                <div class="card-header-flex">
                    <span class="card-title-text">CUSTOMER UNPAID DUES</span>
                    <span class="card-badge-pill badge-red-soft">{{ $unpaid_invoices_all }} UNPAID</span>
                </div>
                <div class="card-big-value maskable-val" style="color: #ef4444;" data-real="{{ format_currency($unpaid_sales_all) }}">
                    {{ format_currency($unpaid_sales_all) }}
                </div>
                <div class="card-footer-flex">
                    <span>{{ $unpaid_invoices_all }} Pending Invoice(s)</span>
                    <a href="{{ route('penjualan.index') }}" class="card-footer-link" style="color: #ef4444;">Settle Invoices &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Realized Paid Sales -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="stat-overview-card border-top-green">
                <div class="card-header-flex">
                    <span class="card-title-text">PAID REALIZED SALES</span>
                    <span class="card-badge-pill badge-green-soft">{{ $paid_invoices_all }} PAID</span>
                </div>
                <div class="card-big-value maskable-val" style="color: #10b981;" data-real="{{ format_currency($paid_sales_all) }}">
                    {{ format_currency($paid_sales_all) }}
                </div>
                <div class="card-footer-flex">
                    <span>Total Billed: <strong class="maskable-val" data-real="{{ format_currency($total_sales_all) }}">{{ format_currency($total_sales_all) }}</strong></span>
                    <a href="{{ route('penjualan.index') }}" class="card-footer-link">View Invoices</a>
                </div>
            </div>
        </div>

        <!-- Supplier Payable (Vendor Due) -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="stat-overview-card border-top-amber">
                <div class="card-header-flex">
                    <span class="card-title-text">SUPPLIER PAYABLE</span>
                    <span class="card-badge-pill badge-amber-soft">VENDOR DUE</span>
                </div>
                <div class="card-big-value maskable-val" style="color: #d97706;" data-real="{{ format_currency($supplier_payable) }}">
                    {{ format_currency($supplier_payable) }}
                </div>
                <div class="card-footer-flex">
                    <span>Pending Supplier Bills</span>
                    <a href="{{ route('pembelian.index') }}" class="card-footer-link" style="color: #d97706;">Pay Dues &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Total Operational Expenses -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="stat-overview-card border-top-slate">
                <div class="card-header-flex">
                    <span class="card-title-text">TOTAL EXPENSES</span>
                    <span class="card-badge-pill badge-slate-soft">EXPENSES</span>
                </div>
                <div class="card-big-value maskable-val" style="color: #475569;" data-real="{{ format_currency($total_expenses_all) }}">
                    {{ format_currency($total_expenses_all) }}
                </div>
                <div class="card-footer-flex">
                    <span>Daily Operating Costs</span>
                    <a href="{{ route('pengeluaran.index') }}" class="card-footer-link">View Expenses</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. ROW 3: CHARTS & ANALYTICS -->
    <div class="row">
        <!-- Monthly Financial & Profit Growth (8 Cols) -->
        <div class="col-lg-8 col-md-7 col-sm-12">
            <div class="chart-box-card">
                <div class="chart-box-header">
                    <h3 class="chart-box-title">
                        <i class="fa fa-line-chart" style="color: #3b82f6;"></i> Monthly Financial &amp; Profit Growth ({{ $currentYear }})
                    </h3>
                    <span class="chart-legend-badge">Paid vs Unpaid vs Profit vs Expenses</span>
                </div>
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="monthlyGrowthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Categories Revenue Donut (4 Cols) -->
        <div class="col-lg-4 col-md-5 col-sm-12">
            <div class="chart-box-card">
                <div class="chart-box-header">
                    <h3 class="chart-box-title">
                        <i class="fa fa-pie-chart" style="color: #10b981;"></i> Top Categories Revenue
                    </h3>
                    <a href="{{ route('kategori.index') }}" class="chart-legend-badge" style="text-decoration: none;">Categories</a>
                </div>
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="categoryDonutChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Custom Date Range Modal -->
<div class="modal fade" id="modalCustomRange" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="border-radius: 10px; overflow: hidden;">
            <div class="modal-header" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 14px 18px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Select Custom Range</h4>
            </div>
            <div class="modal-body" style="padding: 16px 18px;">
                <div class="form-group" style="margin-bottom: 12px;">
                    <label style="font-size: 12px; font-weight: 600; color: #334155;">From Date</label>
                    <input type="date" id="customStartDate" class="form-control input-sm" value="{{ date('Y-m-01') }}">
                </div>
                <div class="form-group" style="margin-bottom: 4px;">
                    <label style="font-size: 12px; font-weight: 600; color: #334155;">To Date</label>
                    <input type="date" id="customEndDate" class="form-control input-sm" value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 10px 18px;">
                <button type="button" class="btn btn-default btn-sm btn-flat" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm btn-flat" onclick="applyCustomDateRange()">Apply Period</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- ChartJS Bundle with CDN Fallback -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.bundle.min.js"></script>
<script>
    if (typeof Chart === 'undefined') {
        document.write('<script src="{{ asset('AdminLTE-2/bower_components/chart.js/Chart.js') }}"><\/script>');
    }
</script>

<script>
    let isMasked = false;
    let growthChart, categoryChart;

    $(function () {
        // Load initial privacy mask state from localStorage
        let savedMask = localStorage.getItem('dashboard_hide_seek');
        if (savedMask === 'true') {
            isMasked = true;
            applyMaskingUI();
        }

        // Period filter click handlers
        $('.period-btn').on('click', function () {
            let period = $(this).data('period');
            if (!period) return; // For Custom Date button

            $('.period-btn').removeClass('active');
            $(this).addClass('active');

            fetchPeriodData(period);
        });

        // Initialize Charts
        initGrowthChart();
        initCategoryChart();
    });

    // Toggle Hide/Seek Privacy Mode
    function toggleHideSeek() {
        isMasked = !isMasked;
        localStorage.setItem('dashboard_hide_seek', isMasked);
        applyMaskingUI();
    }

    function applyMaskingUI() {
        let btn = $('#btnHideSeek');
        let icon = btn.find('i');
        let label = $('#hideSeekLabel');

        if (isMasked) {
            btn.addClass('is-masked');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
            label.text('Hide/Show');

            $('.maskable-val').each(function () {
                let realVal = $(this).attr('data-real');
                if (!realVal) {
                    $(this).attr('data-real', $(this).text().trim());
                }
                $(this).text('Rs ******');
            });
        } else {
            btn.removeClass('is-masked');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
            label.text('Hide/Show');

            $('.maskable-val').each(function () {
                let realVal = $(this).attr('data-real');
                if (realVal) {
                    $(this).text(realVal);
                }
            });
        }
    }

    // Fetch Period Specific Metrics via AJAX
    function fetchPeriodData(period, customStart = null, customEnd = null) {
        let params = { period: period };
        if (period === 'custom') {
            params.start_date = customStart;
            params.end_date = customEnd;
        }

        $.get('{{ route('dashboard.period_data') }}', params)
            .done(response => {
                $('#stripPeriodLabel').text(response.period_label);

                $('#stripPaidSales').attr('data-real', response.paid_sales_formatted);
                $('#stripUnpaidSales').attr('data-real', response.unpaid_sales_formatted);
                $('#stripSales').attr('data-real', response.sales_formatted);
                $('#stripCogs').attr('data-real', response.cogs_formatted);
                $('#stripExpenses').attr('data-real', response.expenses_formatted);
                $('#stripNetProfit').attr('data-real', response.net_profit_formatted);
                $('#stripDiscounts').attr('data-real', response.discounts_formatted);

                $('#stripInvoices').html(
                    '<span class="badge" style="background:#dcfce7; color:#15803d; font-size:11px; padding:3px 6px;">' + response.paid_invoices + ' Paid</span>' +
                    '<span class="badge" style="background:#fee2e2; color:#b91c1c; font-size:11px; padding:3px 6px;">' + response.unpaid_invoices + ' Unpaid</span>'
                );

                applyMaskingUI();
            })
            .fail(errors => {
                showErrorToast('Failed to load period overview');
            });
    }

    function openCustomDateModal() {
        $('#modalCustomRange').modal('show');
    }

    function applyCustomDateRange() {
        let sDate = $('#customStartDate').val();
        let eDate = $('#customEndDate').val();

        if (!sDate || !eDate) {
            showWarningToast('Please select both start and end dates');
            return;
        }

        $('#modalCustomRange').modal('hide');
        $('.period-btn').removeClass('active');
        $('button:contains("Custom Date")').addClass('active');

        fetchPeriodData('custom', sDate, eDate);
    }

    // Chart 1: Monthly Financial Growth Chart
    function initGrowthChart() {
        let canvas = document.getElementById('monthlyGrowthChart');
        if (!canvas) return;
        let ctx = canvas.getContext('2d');
        let labels = {!! json_encode($monthly_labels) !!};
        let paidSalesData = {!! json_encode($monthly_paid_sales) !!};
        let unpaidSalesData = {!! json_encode($monthly_unpaid_sales) !!};
        let profitData = {!! json_encode($monthly_profit) !!};
        let expenseData = {!! json_encode($monthly_expenses) !!};

        growthChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Paid Sales (Cash)',
                        backgroundColor: '#10b981',
                        borderColor: '#059669',
                        borderWidth: 1,
                        data: paidSalesData
                    },
                    {
                        label: 'Unpaid Sales (Due)',
                        backgroundColor: '#ef4444',
                        borderColor: '#dc2626',
                        borderWidth: 1,
                        data: unpaidSalesData
                    },
                    {
                        label: 'Net Profit',
                        backgroundColor: '#3b82f6',
                        borderColor: '#2563eb',
                        borderWidth: 1,
                        data: profitData
                    },
                    {
                        label: 'Expenses',
                        backgroundColor: '#f59e0b',
                        borderColor: '#d97706',
                        borderWidth: 1,
                        data: expenseData
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        fontFamily: 'Inter',
                        fontColor: '#475569',
                        fontSize: 12,
                        fontStyle: 'bold'
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: { display: false },
                        ticks: { fontFamily: 'Inter', fontColor: '#64748b' }
                    }],
                    yAxes: [{
                        gridLines: { color: '#f1f5f9' },
                        ticks: {
                            fontFamily: 'Inter',
                            fontColor: '#64748b',
                            callback: function(value) {
                                return 'Rs ' + value.toLocaleString();
                            }
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            let label = data.datasets[tooltipItem.datasetIndex].label || '';
                            let val = tooltipItem.yLabel;
                            return label + ': Rs ' + val.toLocaleString();
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Top Categories Revenue Donut
    function initCategoryChart() {
        let canvas = document.getElementById('categoryDonutChart');
        if (!canvas) return;
        let ctx = canvas.getContext('2d');
        let labels = {!! json_encode($cat_labels) !!};
        let dataValues = {!! json_encode($cat_amounts) !!};

        let hasData = dataValues && dataValues.some(v => v > 0);
        let chartData = hasData ? dataValues : [1];
        let chartLabels = hasData ? labels : ['No Category Sales Yet'];
        let chartColors = hasData 
            ? ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899']
            : ['#e2e8f0'];

        categoryChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: chartColors,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 65,
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        fontFamily: 'Inter',
                        fontColor: '#475569',
                        fontSize: 11
                    }
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            let label = data.labels[tooltipItem.index] || '';
                            let val = data.datasets[0].data[tooltipItem.index];
                            return label + ': ' + (hasData ? 'Rs ' + val.toLocaleString() : '0');
                        }
                    }
                }
            }
        });
    }
</script>
@endpush