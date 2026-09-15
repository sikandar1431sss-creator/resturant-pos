@extends('layouts.master')

@section('title')
Kitchen Display System (KDS)
@endsection

@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700;800&display=swap" rel="stylesheet">

<style>
    /* Completely hide AdminLTE default sidebar, header, and footer for a pure full-screen KDS */
    .main-sidebar, 
    .main-header, 
    .main-footer, 
    .content-header {
        display: none !important;
    }

    .wrapper {
        background: #f1f5f9 !important;
        overflow-x: hidden !important;
    }

    .content-wrapper {
        margin-left: 0 !important;
        padding-top: 0 !important;
        width: 100% !important;
        min-height: 100vh !important;
        background-color: #f1f5f9 !important;
    }

    .content {
        padding: 14px 18px !important;
        background: #f1f5f9 !important;
        min-height: 100vh !important;
    }

    body {
        background-color: #f1f5f9 !important;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* KDS Top Header */
    .kds-header-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px 22px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
    }

    .kds-branding {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .kds-logo-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 22px;
        box-shadow: 0 4px 14px rgba(234, 88, 12, 0.3);
    }

    .kds-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .kds-live-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfdf5;
        color: #059669;
        font-size: 11px;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
        border: 1px solid #a7f3d0;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .pulse-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseGreen 1.6s infinite;
    }

    @keyframes pulseGreen {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .kds-clock {
        font-family: 'JetBrains Mono', monospace;
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        background: #f8fafc;
        padding: 7px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        letter-spacing: 0.02em;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Stats Quick Chips */
    .kds-stat-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .kds-stat-chip {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 6px 12px;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .kds-stat-num {
        font-weight: 800;
        font-size: 13.5px;
        padding: 2px 8px;
        border-radius: 6px;
        color: #ffffff;
    }

    .stat-pending { background: #3b82f6; }
    .stat-cooking { background: #f59e0b; }
    .stat-ready { background: #10b981; }

    /* Control Actions */
    .kds-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-kds-ctrl {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #334155;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .btn-kds-ctrl:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }

    .btn-kds-ctrl.active-ctrl {
        background: #ea580c;
        border-color: #ea580c;
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(234, 88, 12, 0.3);
    }

    /* Filters Bar */
    .kds-filter-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .tab-pills-group {
        display: flex;
        background: #e2e8f0;
        padding: 4px;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        gap: 4px;
    }

    .tab-pill-btn {
        background: transparent;
        border: none;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        padding: 7px 18px;
        border-radius: 9px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .tab-pill-btn.active {
        background: #ffffff;
        color: #ea580c;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .type-filter-group {
        display: flex;
        gap: 6px;
    }

    .btn-type-filter {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
        font-size: 12.5px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    .btn-type-filter.active, .btn-type-filter:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }

    /* Orders Grid */
    .kds-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(305px, 1fr));
        gap: 18px;
    }

    /* Single Order Card */
    .kds-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04), 0 1px 3px rgba(15, 23, 42, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        animation: cardPopIn 0.3s ease;
    }

    .kds-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px -4px rgba(15, 23, 42, 0.08);
    }

    @keyframes cardPopIn {
        0% { opacity: 0; transform: scale(0.94); }
        100% { opacity: 1; transform: scale(1); }
    }

    /* Urgency Color Accents */
    .kds-card.urgency-normal {
        border-top: 4.5px solid #10b981;
    }
    .kds-card.urgency-warning {
        border-top: 4.5px solid #f59e0b;
    }
    .kds-card.urgency-urgent {
        border-top: 4.5px solid #ef4444;
        border-color: #fca5a5;
        animation: pulseBorderLight 2s infinite;
    }

    @keyframes pulseBorderLight {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.35); }
        70% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }

    /* Card Header Top */
    .card-header-top {
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
    }

    .card-token-title {
        display: flex;
        align-items: baseline;
        gap: 6px;
    }

    .card-token-num {
        font-family: 'JetBrains Mono', monospace;
        font-size: 23px;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -0.03em;
    }

    .card-inv-sub {
        font-size: 11.5px;
        color: #64748b;
        font-weight: 700;
    }

    .card-timer-badge {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12.5px;
        font-weight: 800;
        padding: 4px 9px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid transparent;
    }

    .timer-normal { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
    .timer-warning { background: #fffbeb; color: #b45309; border-color: #fde68a; }
    .timer-urgent { background: #fef2f2; color: #b91c1c; border-color: #fecdd3; }

    /* Order Meta Bar */
    .card-meta-bar {
        padding: 8px 16px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12px;
        border-bottom: 1px solid #e2e8f0;
    }

    .order-type-badge {
        font-weight: 800;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10.5px;
        letter-spacing: 0.03em;
    }

    .type-dine-in { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .type-takeaway { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .type-delivery { background: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff; }

    /* Items List Container */
    .card-items-body {
        padding: 14px 16px;
        flex-grow: 1;
        max-height: 290px;
        overflow-y: auto;
    }

    .card-item-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 7px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-item-row:last-child {
        border-bottom: none;
    }

    .item-qty-circle {
        min-width: 28px;
        height: 28px;
        border-radius: 7px;
        background: #ea580c;
        color: #ffffff;
        font-family: 'JetBrains Mono', monospace;
        font-size: 14px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(234, 88, 12, 0.25);
    }

    .item-info {
        flex-grow: 1;
    }

    .item-name {
        font-size: 14.5px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.3;
    }

    .item-category {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 1px;
    }

    /* Notes Box */
    .card-notes-alert {
        margin: 8px 16px 12px 16px;
        padding: 9px 12px;
        background: #fffbeb;
        border: 1.5px dashed #f59e0b;
        border-radius: 8px;
        color: #92400e;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.35;
    }

    /* Card Action Footer */
    .card-actions-footer {
        padding: 12px 16px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 8px;
    }

    .btn-action-main {
        flex-grow: 1;
        border: none;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-start-cook {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(245, 158, 11, 0.28);
    }
    .btn-start-cook:hover {
        transform: translateY(-1px);
        background: #f59e0b;
        box-shadow: 0 5px 14px rgba(245, 158, 11, 0.38);
    }

    .btn-mark-ready {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(16, 185, 129, 0.28);
    }
    .btn-mark-ready:hover {
        transform: translateY(-1px);
        background: #10b981;
        box-shadow: 0 5px 14px rgba(16, 185, 129, 0.38);
    }

    .btn-mark-served {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(2, 132, 199, 0.28);
    }
    .btn-mark-served:hover {
        transform: translateY(-1px);
        background: #0284c7;
        box-shadow: 0 5px 14px rgba(2, 132, 199, 0.38);
    }

    .btn-action-kot {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #ea580c;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .btn-action-kot:hover {
        background: #fff7ed;
        border-color: #fdba74;
        color: #ea580c;
        transform: translateY(-1px);
    }

    /* Empty State */
    .kds-empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 70px 20px;
        background: #ffffff;
        border: 2px dashed #cbd5e1;
        border-radius: 20px;
        color: #64748b;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .kds-empty-icon {
        font-size: 52px;
        color: #94a3b8;
        margin-bottom: 14px;
    }
</style>
@endpush

@section('content')
<div class="kds-wrapper">

    <!-- Top Header Bar (Clean Professional Light Surface) -->
    <div class="kds-header-bar">
        <div class="kds-branding">
            <div class="kds-logo-icon">
                <i class="fa fa-cutlery"></i>
            </div>
            <div>
                <h1 class="kds-title">
                    <span>Kitchen Display System (KDS)</span>
                    <span class="kds-live-indicator">
                        <span class="pulse-dot"></span> LIVE SYNC
                    </span>
                </h1>
                <div style="font-size: 12px; color: #64748b; margin-top: 2px; font-weight: 600;">
                    {{ $setting->nama_perusahaan ?? 'Fast Food Restaurant' }} &bull; Kitchen Order Terminal
                </div>
            </div>
        </div>

        <!-- Quick Stats Chips -->
        <div class="kds-stat-group">
            <div class="kds-stat-chip">
                <span>Pending</span>
                <span class="kds-stat-num stat-pending" id="statPending">{{ $pendingCount }}</span>
            </div>
            <div class="kds-stat-chip">
                <span>Cooking</span>
                <span class="kds-stat-num stat-cooking" id="statCooking">{{ $cookingCount }}</span>
            </div>
            <div class="kds-stat-chip">
                <span>Ready</span>
                <span class="kds-stat-num stat-ready" id="statReady">{{ $readyCount }}</span>
            </div>
            <div class="kds-stat-chip">
                <span>Served Today</span>
                <span class="kds-stat-num" style="background: #475569;" id="statServed">{{ $servedTodayCount }}</span>
            </div>
        </div>

        <!-- Right Controls -->
        <div class="kds-controls">
            @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1))
            <a href="{{ route('dashboard') }}" class="btn-kds-ctrl" title="Back to Dashboard">
                <i class="fa fa-th-large" style="color: #f97316;"></i> Dashboard
            </a>
            @endif

            <div class="kds-clock">
                <i class="fa fa-clock-o" style="color: #ea580c;"></i>
                <span id="kdsClock">--:--:-- --</span>
            </div>

            <button type="button" class="btn-kds-ctrl active-ctrl" id="btnToggleSound" title="Toggle New Order Chime">
                <i class="fa fa-volume-up" id="soundIcon"></i> Sound ON
            </button>

            <button type="button" class="btn-kds-ctrl" id="btnToggleFullscreen" title="Full Screen View">
                <i class="fa fa-arrows-alt"></i> Fullscreen
            </button>

            <button type="button" class="btn-kds-ctrl" onclick="fetchOrders(true)" title="Force Refresh">
                <i class="fa fa-refresh" id="refreshIcon"></i>
            </button>

            <!-- Chef User Capsule & Logout -->
            <div style="display: flex; align-items: center; gap: 8px; margin-left: 4px;">
                <div class="kds-stat-chip" style="background: #ffffff; border: 1px solid #cbd5e1; color: #0f172a; padding: 6px 12px;">
                    <i class="fa fa-user-circle-o" style="color: #ea580c; font-size: 15px;"></i>
                    <span style="font-weight: 700;">{{ auth()->user()->name ?? 'Chef' }}</span>
                </div>

                <a href="#" class="btn-kds-ctrl" style="color: #ef4444 !important; border-color: #fca5a5 !important; background: #fff5f5 !important;" title="Logout" onclick="event.preventDefault(); document.getElementById('kds-logout-form').submit();">
                    <i class="fa fa-power-off"></i> Logout
                </a>
                <form id="kds-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

    </div>

    <!-- Filter & Status Tabs -->
    <div class="kds-filter-bar">
        <div class="tab-pills-group">
            <button class="tab-pill-btn active" data-tab="active" onclick="switchTab('active')">
                <i class="fa fa-fire"></i> Active Orders (<span id="tabActiveCount">0</span>)
            </button>
            <button class="tab-pill-btn" data-tab="ready" onclick="switchTab('ready')">
                <i class="fa fa-check-circle"></i> Ready for Pickup (<span id="tabReadyCount">0</span>)
            </button>
            <button class="tab-pill-btn" data-tab="served" onclick="switchTab('served')">
                <i class="fa fa-history"></i> Recent Served
            </button>
        </div>

        <div class="type-filter-group" style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
            @if(isset($isCashier) && $isCashier)
                <div class="kds-stat-chip" style="background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; font-size: 12px;">
                    <i class="fa fa-user-circle"></i> My Counter: <strong>{{ auth()->user()->name }}</strong>
                </div>
            @elseif(!empty($cashiersList) && count($cashiersList) > 0)
                <select id="cashierFilterSelect" class="btn-type-filter" style="outline:none; cursor:pointer;" onchange="setCashierFilter(this.value)">
                    <option value="all">👨‍💼 All Cashiers / Staff</option>
                    @foreach($cashiersList as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->getRoleNames()->first() ?? 'Staff' }})</option>
                    @endforeach
                </select>
            @endif

            <button class="btn-type-filter active" data-type="all" onclick="setTypeFilter('all')">All Types</button>
            <button class="btn-type-filter" data-type="Dine-In" onclick="setTypeFilter('Dine-In')">🍽️ Dine-In</button>
            <button class="btn-type-filter" data-type="Takeaway" onclick="setTypeFilter('Takeaway')">🛍️ Takeaway</button>
            <button class="btn-type-filter" data-type="Delivery" onclick="setTypeFilter('Delivery')">🛵 Delivery</button>
        </div>
    </div>


    <!-- Live Orders Grid -->
    <div class="kds-grid" id="ordersGrid">
        <div class="kds-empty-state">
            <div class="kds-empty-icon"><i class="fa fa-spinner fa-spin"></i></div>
            <div style="font-size: 16px; font-weight: 700; color: #64748b;">Loading Kitchen Orders...</div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let currentTab = 'active';
    let currentType = 'all';
    let currentCashier = 'all';
    let soundEnabled = true;
    let knownOrderIds = new Set();
    let isFirstLoad = true;
    let pollInterval = null;

    // Digital Clock
    function updateClock() {
        const now = new Date();
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        const strTime = `${String(hours).padStart(2, '0')}:${minutes}:${seconds} ${ampm}`;
        document.getElementById('kdsClock').textContent = strTime;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Web Audio Synthesizer Beep (Clear pleasant chime)
    function playOrderChime() {
        if (!soundEnabled) return;
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();

            const playTone = (freq, start, duration) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, ctx.currentTime + start);
                gain.gain.setValueAtTime(0.3, ctx.currentTime + start);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + start + duration);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + start);
                osc.stop(ctx.currentTime + start + duration);
            };

            playTone(587.33, 0, 0.25); // D5
            playTone(880.00, 0.2, 0.45); // A5
        } catch (e) {
            console.log('Audio chime error:', e);
        }
    }

    // Toggle Sound
    $('#btnToggleSound').on('click', function() {
        soundEnabled = !soundEnabled;
        if (soundEnabled) {
            $(this).addClass('active-ctrl').html('<i class="fa fa-volume-up"></i> Sound ON');
            playOrderChime();
        } else {
            $(this).removeClass('active-ctrl').html('<i class="fa fa-volume-off"></i> Sound Muted');
        }
    });

    // Fullscreen Toggle
    $('#btnToggleFullscreen').on('click', function() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                alert(`Error attempting to enable full-screen mode: ${err.message}`);
            });
            $(this).addClass('active-ctrl');
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
                $(this).removeClass('active-ctrl');
            }
        }
    });

    // Switch Tab
    function switchTab(tab) {
        currentTab = tab;
        $('.tab-pill-btn').removeClass('active');
        $(`.tab-pill-btn[data-tab="${tab}"]`).addClass('active');
        fetchOrders(true);
    }

    // Set Type Filter
    function setTypeFilter(type) {
        currentType = type;
        $('.btn-type-filter').removeClass('active');
        $(`.btn-type-filter[data-type="${type}"]`).addClass('active');
        fetchOrders(true);
    }

    // Set Cashier Filter (For Admin/Manager/Kitchen)
    function setCashierFilter(cashierId) {
        currentCashier = cashierId;
        fetchOrders(true);
    }

    // Fetch Orders
    function fetchOrders(animate = false) {
        if (animate) {
            $('#refreshIcon').addClass('fa-spin');
        }

        $.ajax({
            url: "{{ route('kitchen.data') }}",
            type: "GET",
            data: {
                tab: currentTab,
                type: currentType,
                cashier_id: currentCashier
            },
            dataType: "json",
            success: function(res) {

                $('#refreshIcon').removeClass('fa-spin');
                if (res.status === 'success') {
                    // Update Stats
                    $('#statPending').text(res.counts.pending);
                    $('#statCooking').text(res.counts.cooking);
                    $('#statReady').text(res.counts.ready);
                    $('#statServed').text(res.counts.served_today);

                    $('#tabActiveCount').text((res.counts.pending + res.counts.cooking));
                    $('#tabReadyCount').text(res.counts.ready);

                    // Check for new orders to trigger sound
                    let hasNewOrder = false;
                    res.orders.forEach(order => {
                        if (!knownOrderIds.has(order.id_penjualan)) {
                            knownOrderIds.add(order.id_penjualan);
                            if (!isFirstLoad) {
                                hasNewOrder = true;
                            }
                        }
                    });

                    if (hasNewOrder) {
                        playOrderChime();
                    }
                    isFirstLoad = false;

                    // Render Cards
                    renderOrders(res.orders);
                }
            },
            error: function(err) {
                $('#refreshIcon').removeClass('fa-spin');
                console.error("Failed to fetch KDS orders:", err);
            }
        });
    }

    // Render Order Cards
    function renderOrders(orders) {
        const grid = document.getElementById('ordersGrid');
        if (!orders || orders.length === 0) {
            let emptyMsg = "No active kitchen orders right now. Great job!";
            if (currentTab === 'ready') emptyMsg = "No orders waiting for pickup.";
            if (currentTab === 'served') emptyMsg = "No orders served recently.";

            grid.innerHTML = `
                <div class="kds-empty-state">
                    <div class="kds-empty-icon"><i class="fa fa-coffee"></i></div>
                    <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">Kitchen is Clear</div>
                    <div style="font-size: 13.5px; color: #64748b;">${emptyMsg}</div>
                </div>
            `;
            return;
        }

        let html = '';
        orders.forEach(order => {
            // Type badge
            let typeBadgeClass = 'type-dine-in';
            let typeLabel = `🍽️ ${order.tipe_order} (${order.nomor_meja})`;
            if (order.tipe_order === 'Takeaway') {
                typeBadgeClass = 'type-takeaway';
                typeLabel = '🛍️ Takeaway';
            } else if (order.tipe_order === 'Delivery') {
                typeBadgeClass = 'type-delivery';
                typeLabel = '🛵 Delivery';
            }

            // Urgency Timer
            let timerClass = 'timer-normal';
            if (order.urgency === 'warning') timerClass = 'timer-warning';
            if (order.urgency === 'urgent') timerClass = 'timer-urgent';

            const elapsedMins = order.elapsed_minutes;
            const elapsedSecs = order.elapsed_seconds % 60;
            const timeDisplay = `${elapsedMins}m ${elapsedSecs < 10 ? '0' : ''}${elapsedSecs}s`;

            // Items List
            let itemsHtml = '';
            order.items.forEach(item => {
                itemsHtml += `
                    <div class="card-item-row">
                        <div class="item-qty-circle">${item.jumlah}</div>
                        <div class="item-info">
                            <div class="item-name">${item.nama_produk}</div>
                            <div class="item-category">${item.kategori}</div>
                        </div>
                    </div>
                `;
            });

            // Special notes box
            let notesHtml = '';
            if (order.catatan && order.catatan.trim() !== '') {
                notesHtml = `
                    <div class="card-notes-alert">
                        <strong>⚠️ Special Note:</strong> ${order.catatan}
                    </div>
                `;
            }

            // Action Button
            let actionBtnHtml = '';
            if (order.kitchen_status === 'pending') {
                actionBtnHtml = `
                    <button class="btn-action-main btn-start-cook" onclick="updateOrderStatus(${order.id_penjualan}, 'cooking')">
                        <i class="fa fa-fire"></i> Start Cooking
                    </button>
                `;
            } else if (order.kitchen_status === 'cooking') {
                actionBtnHtml = `
                    <button class="btn-action-main btn-mark-ready" onclick="updateOrderStatus(${order.id_penjualan}, 'ready')">
                        <i class="fa fa-check"></i> Mark Ready (Bump)
                    </button>
                `;
            } else if (order.kitchen_status === 'ready') {
                actionBtnHtml = `
                    <button class="btn-action-main btn-mark-served" onclick="updateOrderStatus(${order.id_penjualan}, 'served')">
                        <i class="fa fa-handshake-o"></i> Mark Served
                    </button>
                `;
            } else {
                // Served (Recent)
                actionBtnHtml = `
                    <button class="btn-action-main" style="background:#475569; color:#fff;" onclick="updateOrderStatus(${order.id_penjualan}, 'pending')">
                        <i class="fa fa-undo"></i> Recall Order
                    </button>
                `;
            }

            html += `
                <div class="kds-card urgency-${order.urgency}">
                    <div>
                        <!-- Header Top -->
                        <div class="card-header-top">
                            <div class="card-token-title">
                                <span class="card-token-num">#${order.token}</span>
                                <span class="card-inv-sub">${order.invoice}</span>
                            </div>
                            <div class="card-timer-badge ${timerClass}">
                                <i class="fa fa-clock-o"></i> ${timeDisplay}
                            </div>
                        </div>

                        <!-- Meta Bar -->
                        <div class="card-meta-bar">
                            <span class="order-type-badge ${typeBadgeClass}">${typeLabel}</span>
                            <span style="color: #64748b; font-weight: 700;">${order.created_time}</span>
                        </div>

                        <!-- Items List -->
                        <div class="card-items-body">
                            ${itemsHtml}
                        </div>

                        <!-- Notes Alert -->
                        ${notesHtml}
                    </div>

                    <!-- Action Footer -->
                    <div class="card-actions-footer">
                        ${actionBtnHtml}
                        <a href="${order.kot_url}" target="_blank" class="btn-action-kot" title="Print Kitchen KOT">
                            <i class="fa fa-print"></i>
                        </a>
                    </div>
                </div>
            `;
        });

        grid.innerHTML = html;
    }

    // Update Status Action
    function updateOrderStatus(id, newStatus) {
        $.ajax({
            url: `/kitchen/${id}/status`,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                status: newStatus
            },
            dataType: "json",
            success: function(res) {
                if (res.status === 'success') {
                    fetchOrders(false);
                }
            },
            error: function(err) {
                alert("Could not update order status. Please try again.");
                console.error(err);
            }
        });
    }

    // Auto-poll every 6 seconds
    $(document).ready(function() {
        fetchOrders(true);
        pollInterval = setInterval(function() {
            fetchOrders(false);
        }, 6000);
    });
</script>
@endpush
