@extends('layouts.master')

@section('title')
Kitchen Orders
@endsection

@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    /* Hide AdminLTE default duplicate content-header */
    .content-header {
        display: none !important;
    }

    .kitchen-page-wrapper {
        margin-top: 5px;
    }

    /* Top Page Header */
    .kitchen-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }

    .kitchen-heading {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
        font-size: 22px;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* KPI Summary Stats Cards */
    .kpi-row-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .kpi-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 18px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .kpi-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
    }

    .kpi-stat-title {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 4px;
    }

    .kpi-stat-num {
        font-size: 26px;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.1;
    }

    .card-active-kot { border-top: 3px solid #ea580c; }
    .card-busy-tables { border-top: 3px solid #2563eb; }
    .card-delivery-orders { border-top: 3px solid #16a34a; }
    .card-total-kots { border-top: 3px solid #7c3aed; }

    /* Filter & Controls Card */
    .filter-panel-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 18px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .filter-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .filter-panel-title {
        font-size: 14px;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }

    .quick-preset-group {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-quick-preset {
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 11px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-quick-preset:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .btn-quick-preset.active {
        background: #ea580c;
        border-color: #ea580c;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(234, 88, 12, 0.25);
    }

    .filter-form-label {
        font-size: 11.5px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 4px;
        display: block;
    }

    .filter-form-control {
        height: 36px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-size: 12px;
        font-weight: 600;
        color: #1e293b;
        box-shadow: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .filter-form-control:focus {
        border-color: #ea580c;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15);
    }

    .live-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f0fdf4;
        color: #15803d;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        border: 1px solid #bbf7d0;
    }

    .pulse-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #16a34a;
        animation: pulseGreen 1.6s infinite;
    }

    @keyframes pulseGreen {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(22, 163, 74, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
    }

    .btn-refresh-kds {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-size: 12.5px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-refresh-kds:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    /* Orders Grid */
    .kitchen-orders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    /* Order Card */
    .kitchen-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease;
    }

    .kitchen-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        border-color: #cbd5e1;
    }

    .kitchen-card-header {
        padding: 12px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .token-badge-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .token-pill {
        font-family: 'JetBrains Mono', monospace;
        font-size: 15px;
        font-weight: 900;
        background: #fff7ed;
        color: #c2410c;
        border: 1px solid #fed7aa;
        padding: 3px 8px;
        border-radius: 6px;
    }

    .invoice-label {
        font-size: 11.5px;
        color: #64748b;
        font-weight: 600;
    }

    .dining-type-badge {
        font-size: 11px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .badge-dine-in {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .badge-takeaway {
        background: #fefce8;
        color: #a16207;
        border: 1px solid #fef08a;
    }

    .badge-delivery {
        background: #faf5ff;
        color: #7e22ce;
        border: 1px solid #e9d5ff;
    }

    .kitchen-card-meta {
        padding: 8px 14px;
        font-size: 12px;
        color: #64748b;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #ffffff;
    }

    .time-elapsed-tag {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
        background: #f1f5f9;
        color: #475569;
    }

    .time-warning {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .kitchen-items-list {
        padding: 12px 14px;
        flex-grow: 1;
        max-height: 240px;
        overflow-y: auto;
    }

    .kitchen-item-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 7px 0;
        border-bottom: 1px dashed #f1f5f9;
    }

    .kitchen-item-row:last-child {
        border-bottom: none;
    }

    .item-qty-tag {
        min-width: 24px;
        height: 24px;
        border-radius: 6px;
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #fed7aa;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .item-name-text {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
    }

    .item-cooking-note {
        font-size: 11px;
        font-weight: 700;
        color: #b45309;
        background: #fffbeb;
        border: 1px solid #fde68a;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
        margin-top: 3px;
    }

    .card-order-note {
        margin: 6px 14px 10px 14px;
        padding: 8px 10px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        color: #475569;
    }

    .kitchen-card-footer {
        padding: 10px 14px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        gap: 8px;
    }

    .btn-reprint-kot {
        flex: 1;
        background: #ea580c;
        color: #ffffff !important;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        text-align: center;
        text-decoration: none !important;
        transition: all 0.15s ease;
    }

    .btn-reprint-kot:hover {
        background: #c2410c;
        color: #ffffff !important;
    }

    .btn-view-bill {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #334155 !important;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        text-align: center;
        text-decoration: none !important;
        transition: all 0.15s ease;
    }

    .btn-view-bill:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #0f172a !important;
    }

    .empty-kitchen-box {
        grid-column: 1 / -1;
        text-align: center;
        padding: 50px 20px;
        background: #ffffff;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        color: #64748b;
    }

    /* Pagination Bar */
    .kitchen-pagination-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .pagination-info-text {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
    }

    .pagination-btn-group {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
    }

    .btn-page-nav {
        padding: 6px 12px;
        font-size: 12.5px;
        font-weight: 700;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-page-nav:hover:not(:disabled) {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .btn-page-nav.active {
        background: #ea580c;
        border-color: #ea580c;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(234, 88, 12, 0.25);
    }

    .btn-page-nav:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="kitchen-page-wrapper">
    <!-- Top Bar -->
    <div class="kitchen-top-bar">
        <div>
            <h2 class="kitchen-heading">
                Kitchen Orders
            </h2>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <span class="live-status-badge">
                <span class="pulse-dot"></span> Live Sync (10s)
            </span>
            <button type="button" class="btn-refresh-kds" onclick="fetchOrders(1, true)">
                <i class="fa fa-refresh"></i> Refresh
            </button>
        </div>
    </div>

    <!-- KPI Row Grid -->
    <div class="kpi-row-grid">
        <div class="kpi-stat-card card-active-kot">
            <span class="kpi-stat-title">Active In Kitchen</span>
            <span class="kpi-stat-num" id="kpiActiveCount">{{ $activeOrdersCount }}</span>
        </div>

        <div class="kpi-stat-card card-busy-tables">
            <span class="kpi-stat-title">Dine-In Tables Busy</span>
            <span class="kpi-stat-num" id="kpiBusyTables">{{ $dineInCount }}</span>
        </div>

        <div class="kpi-stat-card card-delivery-orders">
            <span class="kpi-stat-title">Delivery Orders</span>
            <span class="kpi-stat-num" id="kpiDeliveryCount">{{ $deliveryCount }}</span>
        </div>

        <div class="kpi-stat-card card-total-kots">
            <span class="kpi-stat-title">Matching Orders</span>
            <span class="kpi-stat-num" id="kpiTodayTotal">{{ $todayTotalCount }}</span>
        </div>
    </div>

    <!-- Filter & Date Controls Card -->
    <div class="filter-panel-card">
        <div class="filter-panel-header">
            <h4 class="filter-panel-title">Filters &amp; Date Range</h4>
            <div class="quick-preset-group">
                <span style="font-size: 12px; font-weight: 700; color: #64748b; margin-right: 4px;">Quick Dates:</span>
                <button type="button" class="btn-quick-preset active" data-preset="today">Today</button>
                <button type="button" class="btn-quick-preset" data-preset="yesterday">Yesterday</button>
                <button type="button" class="btn-quick-preset" data-preset="week">This Week</button>
                <button type="button" class="btn-quick-preset" data-preset="month">This Month</button>
                <button type="button" class="btn-quick-preset" data-preset="all">All Time</button>
                <button type="button" id="btnResetKitchenFilter" class="btn-quick-preset" style="color: #ef4444; border-color: #fca5a5; background: #ffffff;">Reset</button>
            </div>
        </div>

        <div class="row" style="margin-top: 4px;">
            <!-- Start Date -->
            <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                <label class="filter-form-label">From Date</label>
                <input type="date" id="kitchen_start_date" class="form-control filter-form-control">
            </div>

            <!-- End Date -->
            <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                <label class="filter-form-label">To Date</label>
                <input type="date" id="kitchen_end_date" class="form-control filter-form-control">
            </div>

            <!-- Status Tab -->
            <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                <label class="filter-form-label">Status Tab</label>
                <select id="kitchen_status_tab" class="form-control filter-form-control">
                    <option value="active" selected>Active Orders</option>
                    <option value="all">All Orders</option>
                </select>
            </div>

            <!-- Order / Dining Type -->
            <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                <label class="filter-form-label">Order Type</label>
                <select id="kitchen_order_type" class="form-control filter-form-control">
                    <option value="all" selected>All Types</option>
                    <option value="Dine-In">Dine-In</option>
                    <option value="Takeaway">Takeaway</option>
                    <option value="Delivery">Delivery</option>
                </select>
            </div>

            <!-- Search -->
            <div class="col-md-4 col-sm-8 col-xs-12" style="margin-bottom: 10px;">
                <label class="filter-form-label">Search</label>
                <input type="text" id="kitchenSearchInput" class="form-control filter-form-control" placeholder="Search Token / Table / Invoice / Customer...">
            </div>
        </div>
    </div>

    <!-- Orders Grid -->
    <div class="kitchen-orders-grid" id="kitchenOrdersGrid">
        <div class="empty-kitchen-box">
            <h4 style="font-weight: 700; color: #334155; margin: 0;">Loading kitchen orders...</h4>
        </div>
    </div>

    <!-- Pagination Footer -->
    <div class="kitchen-pagination-card" id="kitchenPaginationCard" style="display: none;">
        <div class="pagination-info-text" id="kitchenPaginationInfo">
            Showing 0 to 0 of 0 orders
        </div>

        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 600; color: #64748b;">
                <span>Per Page:</span>
                <select id="kitchenPerPage" class="form-control" style="width: 70px; height: 32px; border-radius: 6px; font-size: 12px; font-weight: 700; padding: 2px 6px;">
                    <option value="6">6</option>
                    <option value="9" selected>9</option>
                    <option value="12">12</option>
                    <option value="18">18</option>
                </select>
            </div>

            <div class="pagination-btn-group" id="kitchenPaginationBtns">
                <!-- Page navigation buttons dynamically inserted -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let lastPage = 1;
    let autoRefreshTimer = null;

    $(function() {
        // Default today's date in inputs
        let todayStr = getTodayFormatted();
        $('#kitchen_start_date').val(todayStr);
        $('#kitchen_end_date').val(todayStr);

        fetchOrders(1);

        // Auto sync every 10s
        autoRefreshTimer = setInterval(function() {
            fetchOrders(currentPage, false);
        }, 10000);

        // Real-time filter listeners
        $('#kitchen_start_date, #kitchen_end_date').on('change', function() {
            $('.btn-quick-preset').removeClass('active');
            fetchOrders(1, true);
        });

        $('#kitchen_status_tab, #kitchen_order_type, #kitchenPerPage').on('change', function() {
            fetchOrders(1, true);
        });

        // Search with debounce
        let searchTimeout;
        $('#kitchenSearchInput').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                fetchOrders(1, true);
            }, 300);
        });

        // Quick Preset handler
        $('.btn-quick-preset').on('click', function() {
            if ($(this).attr('id') === 'btnResetKitchenFilter') return;

            $('.btn-quick-preset').removeClass('active');
            $(this).addClass('active');

            let preset = $(this).data('preset');
            let today = new Date();
            let start = '', end = '';

            const formatDate = (d) => {
                let month = '' + (d.getMonth() + 1);
                let day = '' + d.getDate();
                let year = d.getFullYear();
                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;
                return [year, month, day].join('-');
            };

            if (preset === 'today') {
                start = formatDate(today);
                end = formatDate(today);
            } else if (preset === 'yesterday') {
                let yest = new Date();
                yest.setDate(yest.getDate() - 1);
                start = formatDate(yest);
                end = formatDate(yest);
            } else if (preset === 'week') {
                let curr = new Date();
                let first = curr.getDate() - (curr.getDay() === 0 ? 6 : curr.getDay() - 1);
                let firstday = new Date(curr.setDate(first));
                start = formatDate(firstday);
                end = formatDate(new Date());
            } else if (preset === 'month') {
                let firstday = new Date(today.getFullYear(), today.getMonth(), 1);
                start = formatDate(firstday);
                end = formatDate(new Date());
            } else {
                start = '';
                end = '';
            }

            $('#kitchen_start_date').val(start);
            $('#kitchen_end_date').val(end);
            fetchOrders(1, true);
        });

        // Reset Filter
        $('#btnResetKitchenFilter').on('click', function() {
            let todayStr = getTodayFormatted();
            $('#kitchen_start_date').val(todayStr);
            $('#kitchen_end_date').val(todayStr);
            $('#kitchen_status_tab').val('active');
            $('#kitchen_order_type').val('all');
            $('#kitchenSearchInput').val('');

            $('.btn-quick-preset').removeClass('active');
            $('[data-preset="today"]').addClass('active');

            fetchOrders(1, true);
        });
    });

    function getTodayFormatted() {
        let d = new Date();
        let month = '' + (d.getMonth() + 1);
        let day = '' + d.getDate();
        let year = d.getFullYear();
        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
    }

    function fetchOrders(page = 1, showLoading = false) {
        currentPage = page;
        let perPage = $('#kitchenPerPage').val() || 9;
        let startDate = $('#kitchen_start_date').val();
        let endDate = $('#kitchen_end_date').val();
        let statusTab = $('#kitchen_status_tab').val() || 'active';
        let typeFilter = $('#kitchen_order_type').val() || 'all';
        let search = $('#kitchenSearchInput').val() || '';

        if (showLoading && page === 1) {
            $('#kitchenOrdersGrid').html(`
                <div class="empty-kitchen-box">
                    <h4 style="font-weight: 700; color: #334155; margin: 0;">Loading kitchen orders...</h4>
                </div>
            `);
        }

        $.ajax({
            url: "{{ route('kitchen.data') }}",
            type: "GET",
            data: {
                page: currentPage,
                per_page: perPage,
                start_date: startDate,
                end_date: endDate,
                tab: statusTab,
                type: typeFilter,
                search: search
            },
            dataType: "json",
            success: function(res) {
                if (res.total_count !== undefined) {
                    $('#kpiTodayTotal').text(res.total_count);
                }
                if (res.busy_tables_count !== undefined) {
                    $('#kpiBusyTables').text(res.busy_tables_count);
                }
                if (res.delivery_count !== undefined) {
                    $('#kpiDeliveryCount').text(res.delivery_count);
                }
                if (res.total_count !== undefined && statusTab === 'active') {
                    $('#kpiActiveCount').text(res.total_count);
                }

                lastPage = res.last_page || 1;
                renderKitchenOrders(res.orders || []);
                renderPagination(res);
            }
        });
    }

    function renderKitchenOrders(orders) {
        let container = $('#kitchenOrdersGrid');
        container.empty();

        if (!orders || orders.length === 0) {
            container.html(`
                <div class="empty-kitchen-box">
                    <h4 style="font-weight: 800; color: #1e293b; margin: 0 0 4px 0;">No Kitchen Orders Found</h4>
                    <p style="font-size: 13px; margin: 0; color: #64748b;">No active food items match the current date and filter selection.</p>
                </div>
            `);
            return;
        }

        orders.forEach(ord => {
            let typeBadgeClass = 'badge-dine-in';
            let typeLabel = ord.tipe_order;
            if (ord.tipe_order === 'Takeaway') {
                typeBadgeClass = 'badge-takeaway';
            } else if (ord.tipe_order === 'Delivery') {
                typeBadgeClass = 'badge-delivery';
            } else {
                typeLabel = ord.nomor_meja || 'Dine-In';
            }

            let elapsedMins = ord.elapsed_minutes || 0;
            let timeBadgeClass = 'time-elapsed-tag';
            if (elapsedMins >= 15) {
                timeBadgeClass += ' time-warning';
            }

            let itemsHtml = '';
            (ord.items || []).forEach(item => {
                let noteHtml = item.catatan ? `<div class="item-cooking-note">Note: ${item.catatan}</div>` : '';
                itemsHtml += `
                    <div class="kitchen-item-row">
                        <span class="item-qty-tag">${item.jumlah}</span>
                        <div style="flex:1;">
                            <div class="item-name-text">${item.nama_produk}</div>
                            ${noteHtml}
                        </div>
                    </div>
                `;
            });

            let orderNoteHtml = ord.catatan ? `<div class="card-order-note"><strong>Instructions:</strong> ${ord.catatan}</div>` : '';
            let deliveryInfoHtml = (ord.tipe_order === 'Delivery' && ord.alamat_pengiriman) ? `
                <div style="font-size: 11.5px; color: #0369a1; background: #f0f9ff; border: 1px solid #e0f2fe; padding: 7px 12px; margin: 6px 14px 10px 14px; border-radius: 6px;">
                    <strong>Deliver to:</strong> ${ord.customer} (${ord.telepon_pelanggan || ''})<br>
                    <span style="color: #475569;">${ord.alamat_pengiriman}</span>
                </div>
            ` : '';

            let cardHtml = `
                <div class="kitchen-card">
                    <div>
                        <div class="kitchen-card-header">
                            <div class="token-badge-title">
                                <span class="token-pill">#${ord.token}</span>
                                <div>
                                    <div style="font-weight: 800; font-size: 13.5px; color: #0f172a;">${ord.invoice}</div>
                                    <div class="invoice-label">${ord.created_date} &bull; ${ord.created_time}</div>
                                </div>
                            </div>
                            <span class="dining-type-badge ${typeBadgeClass}">${typeLabel}</span>
                        </div>

                        <div class="kitchen-card-meta">
                            <span>Cashier: ${ord.cashier}</span>
                            <span class="${timeBadgeClass}">${elapsedMins}m ago</span>
                        </div>

                        ${deliveryInfoHtml}

                        <div class="kitchen-items-list">
                            ${itemsHtml}
                        </div>

                        ${orderNoteHtml}
                    </div>

                    <div class="kitchen-card-footer">
                        <a href="${ord.kot_url}" target="_blank" class="btn-reprint-kot">
                            <i class="fa fa-print"></i> Print KOT
                        </a>
                        <a href="${ord.receipt_url}" target="_blank" class="btn-view-bill" title="Print Customer Receipt">
                            Receipt
                        </a>
                    </div>
                </div>
            `;

            container.append(cardHtml);
        });
    }

    function renderPagination(res) {
        let card = $('#kitchenPaginationCard');
        if (!res || res.total_count === 0) {
            card.hide();
            return;
        }

        card.show();
        let from = res.from || 0;
        let to = res.to || 0;
        let total = res.total_count || 0;
        let cur = res.current_page || 1;
        let last = res.last_page || 1;

        $('#kitchenPaginationInfo').text(`Showing ${from} to ${to} of ${total} orders`);

        let btns = $('#kitchenPaginationBtns');
        btns.empty();

        // Prev
        let prevDisabled = (cur <= 1) ? 'disabled' : '';
        btns.append(`<button type="button" class="btn-page-nav" ${prevDisabled} onclick="fetchOrders(${cur - 1}, true)">&laquo; Prev</button>`);

        // Page Numbers
        let startPage = Math.max(1, cur - 2);
        let endPage = Math.min(last, cur + 2);

        if (startPage > 1) {
            btns.append(`<button type="button" class="btn-page-nav" onclick="fetchOrders(1, true)">1</button>`);
            if (startPage > 2) {
                btns.append(`<span style="padding: 0 4px; color: #94a3b8;">...</span>`);
            }
        }

        for (let p = startPage; p <= endPage; p++) {
            let active = (p === cur) ? 'active' : '';
            btns.append(`<button type="button" class="btn-page-nav ${active}" onclick="fetchOrders(${p}, true)">${p}</button>`);
        }

        if (endPage < last) {
            if (endPage < last - 1) {
                btns.append(`<span style="padding: 0 4px; color: #94a3b8;">...</span>`);
            }
            btns.append(`<button type="button" class="btn-page-nav" onclick="fetchOrders(${last}, true)">${last}</button>`);
        }

        // Next
        let nextDisabled = (cur >= last) ? 'disabled' : '';
        btns.append(`<button type="button" class="btn-page-nav" ${nextDisabled} onclick="fetchOrders(${cur + 1}, true)">Next &raquo;</button>`);
    }
</script>
@endpush
