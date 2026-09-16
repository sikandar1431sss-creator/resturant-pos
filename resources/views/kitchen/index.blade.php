@extends('layouts.master')

@section('title')
Kitchen Orders Monitor
@endsection

@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background-color: #f8fafc;
    }

    /* KPI Summary Stats Cards - Ultra Clean */
    .kpi-row-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .kpi-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .kpi-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
        border-color: #cbd5e1;
    }

    .kpi-stat-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 6px;
    }

    .kpi-stat-num {
        font-size: 30px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .card-active-kot {
        border-top: 3px solid #ea580c;
    }
    .card-busy-tables {
        border-top: 3px solid #2563eb;
    }
    .card-delivery-orders {
        border-top: 3px solid #16a34a;
    }
    .card-total-kots {
        border-top: 3px solid #7c3aed;
    }

    /* Filter & Controls Bar */
    .kds-control-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .type-pills-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-type-pill {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-type-pill:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .btn-type-pill.active {
        background: #ea580c;
        color: #ffffff;
        border-color: #ea580c;
        box-shadow: 0 2px 8px rgba(234, 88, 12, 0.2);
    }

    .kds-search-input {
        padding: 6px 14px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        font-size: 13px;
        color: #0f172a;
        outline: none;
        min-width: 220px;
        transition: all 0.15s ease;
    }

    .kds-search-input:focus {
        background: #ffffff;
        border-color: #ea580c;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1);
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
        border: 1px solid #e2e8f0;
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
        border-color: #cbd5e1;
        color: #0f172a;
    }

    /* Live Orders Grid */
    .kitchen-orders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
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
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Kitchen Orders Monitor</li>
@endsection

@section('content')
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
        <span class="kpi-stat-title">Today's Total KOTs</span>
        <span class="kpi-stat-num" id="kpiTodayTotal">{{ $todayTotalCount }}</span>
    </div>
</div>

<!-- Controls & Filters Toolbar -->
<div class="kds-control-card">
    <div class="type-pills-group">
        <button type="button" class="btn-type-pill active" data-type="all" onclick="setTypeFilter('all')">
            All Orders
        </button>
        <button type="button" class="btn-type-pill" data-type="Dine-In" onclick="setTypeFilter('Dine-In')">
            Dine-In
        </button>
        <button type="button" class="btn-type-pill" data-type="Takeaway" onclick="setTypeFilter('Takeaway')">
            Takeaway
        </button>
        <button type="button" class="btn-type-pill" data-type="Delivery" onclick="setTypeFilter('Delivery')">
            Delivery
        </button>
    </div>

    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <input type="text" id="kdsSearchInput" class="kds-search-input" placeholder="Search Token / Table / Invoice..." onkeyup="filterKitchenCards()">

        <span class="live-status-badge">
            <span class="pulse-dot"></span> Live Sync (10s)
        </span>
        <button type="button" class="btn-refresh-kds" onclick="fetchOrders(true)" title="Refresh Now">
            Refresh
        </button>
    </div>
</div>

<!-- Orders Grid -->
<div class="kitchen-orders-grid" id="kitchenOrdersGrid">
    <div class="empty-kitchen-box">
        <h4 style="font-weight: 700; color: #334155; margin: 0;">Loading kitchen orders...</h4>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentType = 'all';
    let cachedOrders = [];

    $(function() {
        fetchOrders();
        // Silent auto-refresh every 10 seconds
        setInterval(function() {
            fetchOrders(false);
        }, 10000);
    });

    function setTypeFilter(type) {
        currentType = type;
        $('.btn-type-pill').removeClass('active');
        $(`.btn-type-pill[data-type="${type}"]`).addClass('active');
        fetchOrders(true);
    }

    function fetchOrders(animate = false) {
        $.ajax({
            url: "{{ route('kitchen.data') }}",
            type: "GET",
            data: {
                tab: 'active',
                type: currentType
            },
            dataType: "json",
            success: function(res) {
                if (res.total_count !== undefined) {
                    $('#kpiActiveCount').text(res.total_count);
                }
                if (res.busy_tables_count !== undefined) {
                    $('#kpiBusyTables').text(res.busy_tables_count);
                }
                if (res.delivery_count !== undefined) {
                    $('#kpiDeliveryCount').text(res.delivery_count);
                }
                if (res.today_total_count !== undefined) {
                    $('#kpiTodayTotal').text(res.today_total_count);
                }

                cachedOrders = res.orders || [];
                renderKitchenOrders(cachedOrders);
            }
        });
    }

    function filterKitchenCards() {
        let query = ($('#kdsSearchInput').val() || '').toLowerCase().trim();
        if (!query) {
            renderKitchenOrders(cachedOrders);
            return;
        }

        let filtered = cachedOrders.filter(ord => {
            let tokenStr = (ord.token || '').toLowerCase();
            let invStr = (ord.invoice || '').toLowerCase();
            let tableStr = (ord.nomor_meja || '').toLowerCase();
            let custStr = (ord.customer || '').toLowerCase();
            return tokenStr.includes(query) || invStr.includes(query) || tableStr.includes(query) || custStr.includes(query);
        });

        renderKitchenOrders(filtered);
    }

    function renderKitchenOrders(orders) {
        let container = $('#kitchenOrdersGrid');
        container.empty();

        if (!orders || orders.length === 0) {
            container.html(`
                <div class="empty-kitchen-box">
                    <h4 style="font-weight: 800; color: #1e293b; margin: 0 0 4px 0;">All Kitchen Orders Prepared</h4>
                    <p style="font-size: 13px; margin: 0; color: #64748b;">No active food items waiting for cooking right now.</p>
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
                                    <div class="invoice-label">${ord.created_time}</div>
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
                            Print KOT
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
</script>
@endpush


