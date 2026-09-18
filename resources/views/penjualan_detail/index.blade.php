@extends('layouts.master')

@section('title')
Create New Invoice
@endsection

@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    /* Hide AdminLTE default content-header */
    .content-header {
        display: none !important;
    }

    /* POS Wrapper */
    .pos-screen-wrapper {
        margin: 0 -5px 15px -5px;
    }

    /* Top POS Header Bar */
    .pos-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pos-heading-title {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.02em;
    }

    .pos-heading-sub {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
        font-weight: 500;
    }

    .pos-top-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-pos-pill-new {
        background: #f97316 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 8px 18px !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: none !important;
        transition: background-color 0.2s ease;
    }

    .btn-pos-pill-new:hover {
        background: #ea580c !important;
        color: #ffffff !important;
        box-shadow: none !important;
    }

    .btn-pos-pill-outline {
        background: #ffffff !important;
        color: #334155 !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 8px 14px !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
    }

    .btn-pos-pill-outline:hover {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
    }

    /* Left Card: Menu Showcase */
    .pos-menu-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.04);
        margin-bottom: 20px;
    }

    /* Filter Row */
    .pos-filter-row {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .pos-search-box {
        flex: 1;
        min-width: 180px;
        position: relative;
    }

    .pos-search-box i {
        position: absolute;
        left: 12px;
        top: 12px;
        color: #94a3b8;
        font-size: 13px;
    }

    .pos-search-input {
        width: 100%;
        height: 38px;
        padding-left: 34px;
        padding-right: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        color: #0f172a;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .pos-search-input:focus {
        border-color: #f97316;
        outline: none;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
    }

    .pos-select-filter {
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        color: #475569;
        padding: 0 10px;
        background: #ffffff;
        min-width: 130px;
        cursor: pointer;
    }

    .pos-select-filter:focus {
        border-color: #f97316;
        outline: none;
    }

    /* Category Filter Pills Bar */
    .pos-cat-scroll {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 14px;
    }

    .pos-pill-tab {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #475569;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.18s ease;
        user-select: none;
    }

    .pos-pill-tab:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .pos-pill-tab.active {
        background: #f97316;
        color: #ffffff;
        border-color: #ea580c;
        box-shadow: 0 2px 6px rgba(249, 115, 22, 0.28);
    }

    /* Product Cards Grid: Exactly 5 items per row */
    .pos-product-grid {
        display: grid !important;
        grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
        grid-auto-rows: max-content;
        align-content: start;
        align-items: start;
        gap: 8px;
        padding-bottom: 8px;
    }

    @media (max-width: 991px) {
        .pos-product-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 576px) {
        .pos-product-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }

    .pos-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px 6px;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        transition: border-color 0.15s ease, background-color 0.15s ease;
        position: relative;
        box-shadow: none;
        user-select: none;
        height: auto;
    }

    .pos-item-card:hover {
        border-color: #f97316;
        background: #fffbf7;
        box-shadow: none;
    }

    .pos-item-card:active {
        border-color: #ea580c;
    }

    .pos-item-img-container {
        width: 100%;
        height: 75px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 6px;
        background: #f8fafc;
        border-radius: 6px;
        overflow: hidden;
    }

    .pos-item-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 2px;
    }

    .pos-item-card:hover .pos-item-img {
        /* No transform / scale on hover */
    }

    .pos-item-name {
        font-size: 11.5px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px 0;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 30px;
    }

    .pos-item-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 2px;
        padding-top: 4px;
        border-top: 1px solid #f1f5f9;
    }

    .pos-item-price {
        font-size: 13px;
        font-weight: 800;
        color: #ea580c;
        letter-spacing: -0.01em;
    }

    .pos-item-add-btn {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #f97316;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .pos-item-card:hover .pos-item-add-btn {
        background: #ea580c;
    }

    /* RIGHT: CURRENT ORDER WIDGET */
    .pos-order-widget {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: none !important;
        overflow: hidden;
        margin-bottom: 20px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Dark Navy Header Bar */
    .pos-widget-header {
        background: #1e3a68;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pos-widget-title {
        color: #ffffff;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin: 0;
    }

    .btn-order-info-pill {
        background: #ffffff;
        color: #1e3a68;
        border: none;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 11.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-order-info-pill:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    /* Main Content Body */
    .pos-widget-body {
        padding: 16px;
    }

    /* Items List Container */
    .pos-order-items-scroll {
        max-height: 290px;
        overflow-y: auto;
        padding-right: 2px;
        margin-bottom: 16px;
    }

    /* Individual Food Item Card */
    .pos-food-card {
        background: #f4f6fa;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 9px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.15s ease;
    }

    .pos-food-card:hover {
        background: #edf2f7;
    }

    .pos-food-info {
        flex: 1;
        min-width: 0;
        padding-right: 10px;
    }

    .pos-food-name {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 2px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pos-food-price {
        font-size: 12.5px;
        font-weight: 700;
        color: #334155;
    }

    .btn-food-note {
        background: none;
        border: none;
        padding: 0;
        font-size: 10px;
        color: #64748b;
        cursor: pointer;
        font-weight: 600;
        text-decoration: underline;
        margin-top: 2px;
    }

    .btn-food-note:hover {
        color: #f97316;
    }

    /* Circular Stepper & Trash */
    .pos-food-stepper {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-circle-minus {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #94a3b8;
        color: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 900;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-circle-minus:hover {
        background: #64748b;
    }

    .pos-food-qty {
        font-size: 13px;
        font-weight: 800;
        color: #1e293b;
        min-width: 24px;
        text-align: center;
    }

    .btn-circle-plus {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #1e3a68;
        color: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 900;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-circle-plus:hover {
        background: #0f172a;
    }

    .pos-trash-btn {
        margin-left: 8px;
        color: #94a3b8;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .pos-trash-btn:hover {
        color: #ef4444;
        transform: scale(1.15);
    }

    .pos-empty-cart-box {
        text-align: center;
        padding: 30px 10px;
        color: #94a3b8;
    }

    .pos-empty-cart-box i {
        font-size: 30px;
        color: #cbd5e1;
        margin-bottom: 8px;
    }

    /* Subtotal & Totals Breakdown */
    .pos-subtotal-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .pos-subtotal-row.tax {
        color: #94a3b8;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .pos-dotted-divider {
        border-top: 1.5px dotted #cbd5e1;
        margin: 10px 0;
    }

    .pos-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin: 8px 0;
    }

    .pos-solid-divider {
        border-top: 1.5px solid #e2e8f0;
        margin: 12px 0 14px 0;
    }

    /* PAYMENT METHOD DROPDOWN SELECTOR */
    .pos-pm-label {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pos-pm-select-wrapper {
        position: relative;
        margin-bottom: 12px;
    }

    .pos-pm-select {
        width: 100%;
        height: 42px;
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        padding: 0 36px 0 14px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pos-pm-select:focus {
        border-color: #f97316;
        background: #ffffff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
    }

    .pos-pm-select-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #64748b;
        font-size: 12px;
    }

    /* Secondary Fast Action Buttons Bar */
    .pos-secondary-actions {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
    }

    .btn-sec-pill {
        flex: 1;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        border-radius: 8px;
        padding: 9px 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none !important;
    }

    .btn-sec-pill:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    /* Big Bright Orange Place Order Button at the very bottom */
    .btn-place-order {
        width: 100%;
        height: 50px;
        background: linear-gradient(180deg, #ff6b3d 0%, #ff521d 100%);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 800;
        letter-spacing: 0.01em;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: none !important;
        transition: background 0.2s ease;
    }

    .btn-place-order:hover {
        background: #ea580c;
        box-shadow: none !important;
        color: #ffffff;
    }

    .btn-place-order:active {
        background: #c2410c;
        box-shadow: none !important;
    }

    /* Quick Cooking Notes Chips */
    .chips-container {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 12px;
    }

    .btn-quick-chip {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #1e293b;
        font-size: 11.5px;
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-quick-chip:hover {
        background: #ffedd5;
        border-color: #fdba74;
        color: #ea580c;
        transform: translateY(-1px);
    }

    .btn-quick-chip.active {
        background: #ea580c;
        border-color: #ea580c;
        color: #ffffff;
    }

    /* Visual Table Selector Grid */
    .table-grid-picker {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        max-height: 220px;
        overflow-y: auto;
        padding: 4px;
        margin-top: 6px;
    }

    .table-card-btn {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px 6px;
        text-align: center;
        cursor: pointer;
        transition: all 0.15s ease;
        position: relative;
    }

    .table-card-btn.table-available {
        border-color: #86efac;
        background: #f0fdf4;
    }

    .table-card-btn.table-available:hover {
        border-color: #22c55e;
        background: #dcfce7;
        transform: translateY(-1px);
    }

    .table-card-btn.table-selected {
        border-color: #16a34a !important;
        background: #16a34a !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(22, 163, 74, 0.35);
    }

    .table-card-btn.table-selected * {
        color: #ffffff !important;
    }

    .table-card-btn.table-occupied {
        border-color: #fca5a5;
        background: #fff1f2;
        cursor: not-allowed;
    }

    .table-card-btn.table-occupied:hover {
        border-color: #ef4444;
        background: #ffe4e6;
        transform: none;
    }

    .table-card-title {
        font-size: 12px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .table-status-pill {
        font-size: 9px;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 10px;
        display: inline-block;
        text-transform: uppercase;
    }

    .status-available-pill {
        background: #bbf7d0;
        color: #15803d;
    }

    .status-occupied-pill {
        background: #fecaca;
        color: #b91c1c;
    }

    /* Modal Details */
    .modal-content {
        border-radius: 14px !important;
        border: none !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
    }

    /* ==========================================================================
       ENTERPRISE PARKED DRAFT INVOICES MODAL
       ========================================================================== */
    .draft-modal-dialog {
        max-width: 1080px !important;
        width: 95% !important;
        margin: 30px auto;
    }

    .draft-modal-content {
        border-radius: 16px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        overflow: hidden;
        background: #ffffff;
    }

    .draft-modal-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .draft-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .draft-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #ffedd5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .draft-modal-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .draft-count-pill {
        background: #ea580c;
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        padding: 3px 9px;
        border-radius: 20px;
        letter-spacing: 0.02em;
    }

    .draft-modal-sub {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
        font-weight: 500;
    }

    .draft-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .draft-search-box {
        position: relative;
        min-width: 260px;
    }

    .draft-search-box i {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 12.5px;
    }

    .draft-search-input {
        width: 100%;
        height: 36px;
        padding: 0 12px 0 32px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 12px;
        font-weight: 600;
        color: #1e293b;
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .draft-search-input:focus {
        background: #ffffff;
        border-color: #ea580c;
        outline: none;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12);
    }

    .btn-draft-refresh {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-draft-refresh:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }

    .draft-close-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f1f5f9;
        border: none;
        color: #64748b;
        font-size: 18px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .draft-close-btn:hover {
        background: #fee2e2;
        color: #ef4444;
    }

    .draft-modal-body {
        padding: 0;
        max-height: calc(82vh - 160px);
        overflow-y: auto;
    }

    .draft-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .draft-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 12px 16px;
        border-top: none;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .draft-table tbody tr {
        transition: background-color 0.15s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .draft-table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .draft-table tbody td {
        padding: 13px 16px;
        vertical-align: middle;
        font-size: 12.5px;
        color: #334155;
        border-top: none;
        border-bottom: 1px solid #f1f5f9;
    }

    .draft-inv-code {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-weight: 800;
        color: #ea580c;
        font-size: 13px;
        display: block;
    }

    .draft-cashier-sub {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 1px;
    }

    .draft-type-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .draft-type-dinein {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }

    .draft-type-takeaway {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fef3c7;
    }

    .draft-type-delivery {
        background: #f0fdfa;
        color: #0d9488;
        border: 1px solid #ccfbf1;
    }

    .draft-customer-name {
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .draft-customer-phone {
        font-size: 11px;
        color: #64748b;
        margin-top: 2px;
    }

    .draft-item-count-badge {
        background: #f1f5f9;
        color: #334155;
        font-weight: 800;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 3px;
        border: 1px solid #e2e8f0;
    }

    .draft-items-preview {
        font-size: 11.5px;
        color: #64748b;
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .draft-amount-val {
        font-weight: 800;
        font-size: 14px;
        color: #0f172a;
        text-align: right;
    }

    .draft-status-pill {
        display: inline-block;
        font-size: 10px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-top: 2px;
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fee2e2;
    }

    .draft-actions-wrap {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
    }

    .btn-draft-resume {
        background: #ea580c !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 6px !important;
        padding: 6px 14px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none !important;
        transition: background-color 0.15s ease;
        box-shadow: none !important;
    }

    .btn-draft-resume:hover {
        background: #c2410c !important;
        color: #ffffff !important;
    }

    .btn-draft-delete {
        background: #ffffff !important;
        color: #ef4444 !important;
        border: 1px solid #fecaca !important;
        border-radius: 6px !important;
        padding: 6px 10px !important;
        font-size: 12px !important;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-draft-delete:hover {
        background: #fee2e2 !important;
        border-color: #f87171 !important;
        color: #dc2626 !important;
    }

    .draft-modal-footer {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .draft-live-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }

    .live-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseGreen 2s infinite;
    }

    @keyframes pulseGreen {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>
@endpush

@section('content')
<div class="pos-screen-wrapper">
    <!-- Top POS Header -->
    <div class="pos-top-bar">
        <div>
            <h1 class="pos-heading-title">
                Create New Invoice
                @if($isEditMode)
                    <span class="badge" style="background: #2563eb; color: #fff; font-size: 12px; font-weight: 800; padding: 5px 12px; border-radius: 6px; margin-left: 8px; vertical-align: middle; box-shadow: none;">
                        <i class="fa fa-edit"></i> EDITING #INV-{{ tambah_nol_didepan($penjualan->id_penjualan, 5) }}
                    </span>
                @endif
            </h1>
            @if($isEditMode)
            <div class="pos-heading-sub">
                <span style="color: #2563eb; font-weight: 700;">Edit Mode Active</span> &bull; Modify items, table, customer or discounts and click Save &amp; Update
            </div>
            @endif
        </div>
        <div class="pos-top-actions">
            @if($isEditMode)
                <a href="{{ route('transaksi.cancel_edit') }}" class="btn btn-pos-pill-outline" style="color: #ef4444 !important; border-color: #fca5a5 !important; background: #fff5f5 !important;" title="Cancel editing without saving changes">
                    <i class="fa fa-times-circle"></i> Cancel Edit
                </a>
            @endif
            <a href="{{ route('transaksi.baru') }}" class="btn btn-pos-pill-new">
                <i class="fa fa-plus"></i> New
            </a>
            @if(auth()->user()->can('pos.drafts') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <button type="button" class="btn btn-pos-pill-outline" onclick="showDraftListModal()">
                <i class="fa fa-pause-circle"></i> Draft Invoices <span class="badge" id="posDraftCountBadge" style="display:none; background:#ea580c; color:#fff; font-size:10.5px; margin-left:4px; font-weight:800; border-radius:10px; padding:2px 7px;">0</span>
            </button>
            @endif
        </div>

    </div>

    <div class="row">
        <!-- LEFT: Products Grid (Strictly 5 items per row) -->
        <div class="col-lg-7 col-md-7 col-sm-12">
            <div class="pos-menu-card">
                <!-- Search & Filters -->
                <div class="pos-filter-row">
                    <div class="pos-search-box">
                        <i class="fa fa-search"></i>
                        <input type="text" id="foodSearchInput" class="pos-search-input" placeholder="Search in products">
                    </div>
                    <select id="catSelectFilter" class="pos-select-filter">
                        <option value="all">All Categories</option>
                        <option value="deals">Deals</option>
                        @foreach($kategori as $cat)
                            @if($cat->nama_kategori !== 'Deals' && $cat->nama_kategori !== 'Deals & Combos')
                            <option value="{{ $cat->id_kategori }}">{{ $cat->nama_kategori }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Category Pills Bar -->
                <div class="pos-cat-scroll">
                    <span class="pos-pill-tab active" data-category="all">All Items</span>
                    <span class="pos-pill-tab" data-category="deals" style="background: #fffbeb; border-color: #fde68a; color: #b45309; font-weight: 800;">
                        Deals
                    </span>
                    @foreach($kategori as $cat)
                        @if($cat->nama_kategori !== 'Deals' && $cat->nama_kategori !== 'Deals & Combos')
                        <span class="pos-pill-tab" data-category="{{ $cat->id_kategori }}">{{ $cat->nama_kategori }}</span>
                        @endif
                    @endforeach
                </div>

                <!-- Product Grid (Strictly 5 Per Row) -->
                <div class="pos-product-grid" id="posProductGrid">
                    @forelse($produk as $item)
                    @php
                        if (!empty($item->foto)) {
                            $imgUrl = url($item->foto);
                        } else {
                            $name = strtolower($item->nama_produk);
                            $catName = strtolower($item->kategori->nama_kategori ?? '');
                            
                            if (str_contains($name, 'burger')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=250&auto=format&fit=crop&q=80';
                            } elseif (str_contains($name, 'pizza')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=250&auto=format&fit=crop&q=80';
                            } elseif (str_contains($name, 'salad')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=250&auto=format&fit=crop&q=80';
                            } elseif (str_contains($name, 'onion') || str_contains($name, 'ring')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1639024471285-0afc3855f488?w=250&auto=format&fit=crop&q=80';
                            } elseif (str_contains($name, 'bacon') || str_contains($name, 'meat')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1608897013039-887f21d8c804?w=250&auto=format&fit=crop&q=80';
                            } elseif (str_contains($name, 'chicken') || str_contains($name, 'fried')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=250&auto=format&fit=crop&q=80';
                            } elseif (str_contains($catName, 'beverage') || str_contains($name, 'drink') || str_contains($name, 'coke') || str_contains($name, 'tea')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=250&auto=format&fit=crop&q=80';
                            } else {
                                $imgUrl = 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=250&auto=format&fit=crop&q=80';
                            }
                        }
                    @endphp
                    <div class="pos-item-card" 
                         data-id="{{ $item->id_produk }}" 
                         data-code="{{ $item->kode_produk }}" 
                         data-category="{{ $item->id_kategori ?? '' }}" 
                         data-brand="{{ strtolower($item->merk ?? '') }}"
                         data-name="{{ strtolower($item->nama_produk) }}"
                         data-is-deal="{{ (($item->merk ?? '') === 'Deal Combo' || ($item->kategori->nama_kategori ?? '') === 'Deals' || ($item->kategori->nama_kategori ?? '') === 'Deals & Combos') ? '1' : '0' }}"
                         onclick="addItemToCart('{{ $item->id_produk }}')">
                        @if(($item->merk ?? '') === 'Deal Combo' || ($item->kategori->nama_kategori ?? '') === 'Deals' || ($item->kategori->nama_kategori ?? '') === 'Deals & Combos')
                            <span class="badge" style="position: absolute; top: 6px; left: 6px; z-index: 2; background: #f59e0b; color: #fff; font-size: 9.5px; font-weight: 800; padding: 2px 6px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                                DEAL
                            </span>
                        @endif
                        <div class="pos-item-img-container">
                            <img src="{{ $imgUrl }}" alt="{{ $item->nama_produk }}" class="pos-item-img">
                        </div>
                        <h4 class="pos-item-name" title="{{ $item->nama_produk }}">{{ $item->nama_produk }}</h4>
                        <div class="pos-item-bottom">
                            <span class="pos-item-price">{{ format_currency($item->harga_jual) }}</span>
                            <span class="pos-item-add-btn"><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center" style="grid-column: 1 / -1; padding: 50px 20px; color: #94a3b8;">
                        <i class="fa fa-th-large" style="font-size: 36px; margin-bottom: 10px; color: #cbd5e1;"></i>
                        <h4 style="font-weight: 700; color: #475569;">No Menu Dishes Found</h4>
                        <a href="{{ route('produk.index') }}" class="btn btn-sm btn-primary btn-flat" style="border-radius: 8px;">Add Dishes in Menu</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- RIGHT: EXACT USER CURRENT ORDER CARD -->
        <div class="col-lg-5 col-md-5 col-sm-12">
            <div class="pos-order-widget">
                
                <!-- 1. Dark Blue Header with ORDER INFO Pill Button -->
                <div class="pos-widget-header">
                    <span class="pos-widget-title">CURRENT ORDER</span>
                    <button type="button" class="btn-order-info-pill" onclick="openOrderInfoModal()">
                        ORDER INFO
                    </button>
                </div>

                <div class="pos-widget-body">
                    
                    <!-- 2. Scrollable Food Items List (Rendered as clean rounded cards) -->
                    <div class="pos-order-items-scroll" id="posOrderItemsContainer">
                        <div class="pos-empty-cart-box">
                            <i class="fa fa-shopping-basket"></i>
                            <p style="margin: 0; font-size: 12px;">No items in current order.<br>Click any food item on the left to add.</p>
                        </div>
                    </div>

                    <!-- 3. Totals Breakdown -->
                    <div class="pos-subtotal-row">
                        <span>Subtotal</span>
                        <strong id="lblSubtotal">{{ get_currency_symbol() }} 0.00</strong>
                    </div>
                    <div class="pos-subtotal-row tax">
                        <span>Tax(%) / Disc</span>
                        <span id="lblDiscountAndTax">{{ get_currency_symbol() }} 0.00</span>
                    </div>
                    <div class="pos-subtotal-row" id="rowDeliveryFee" style="display: none; color: #0284c7;">
                        <span>Delivery Fee (+)</span>
                        <strong id="lblDeliveryFee">{{ get_currency_symbol() }} 0.00</strong>
                    </div>

                    <div class="pos-dotted-divider"></div>

                    <div class="pos-total-row">
                        <span>Total</span>
                        <strong id="lblGrandTotal" style="font-size: 18px;">{{ get_currency_symbol() }} 0.00</strong>
                    </div>

                    <div class="pos-solid-divider"></div>

                    <!-- 4. PAYMENT METHOD DROPDOWN SELECTOR -->
                    <div class="pos-pm-label">
                        Payment Method
                    </div>
                    <div class="pos-pm-select-wrapper">
                        <select id="posPaymentMethodSelect" class="pos-pm-select" onchange="setPaymentMethod(this.value)">
                            <option value="cash" selected>Cash</option>
                            <option value="card">Debit Card</option>
                            <option value="online">E-Wallet / Online</option>
                        </select>
                        <i class="fa fa-chevron-down pos-pm-select-icon"></i>
                    </div>

                    <!-- 5. Secondary Fast Actions (Print in New Tab / Draft List) -->
                    <div class="pos-secondary-actions">
                        <a href="{{ route('transaksi.nota_kecil') }}" target="_blank" class="btn-sec-pill" title="Print Receipt in New Tab">
                            Print Receipt
                        </a>
                        <button type="button" class="btn-sec-pill" onclick="saveAsDraft()" title="Park as Draft">
                            Draft Order
                        </button>
                    </div>

                    <!-- 6. Big Bright Orange Place Order Button (At the very bottom) -->
                    <button type="button" class="btn-place-order" onclick="placeOrderSubmit()">
                        @if($isEditMode)
                            <i class="fa fa-check-circle"></i> Save &amp; Update Invoice
                        @else
                            Place Order
                        @endif
                    </button>

                </div>

                <!-- Hidden form for checkout submit -->
                <form action="{{ route('transaksi.simpan') }}" class="form-penjualan hide" method="post" id="posMainForm">
                    @csrf
                    <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                    <input type="hidden" name="total" id="form_total" value="{{ $penjualan->total_harga ?? 0 }}">
                    <input type="hidden" name="total_item" id="form_total_item" value="{{ $penjualan->total_item ?? 0 }}">
                    <input type="hidden" name="bayar" id="form_bayar" value="{{ $penjualan->bayar ?? 0 }}">
                    <input type="hidden" name="diskon" id="form_diskon" value="{{ $penjualan->diskon ?? $diskon }}">
                    <input type="hidden" name="ongkir" id="form_ongkir" value="{{ $penjualan->ongkir ?? 0 }}">
                    <input type="hidden" name="diterima" id="form_diterima" value="{{ $penjualan->diterima ?? 0 }}">
                    <input type="hidden" name="status_pembayaran" id="form_status_pembayaran" value="{{ $penjualan->status_pembayaran ?? 'unpaid' }}">
                    <input type="hidden" name="metode_pembayaran" id="form_metode_pembayaran" value="{{ $penjualan->metode_pembayaran ?? 'cash' }}">
                    <input type="hidden" name="id_member" id="form_id_member" value="{{ $memberSelected->id_member ?? '' }}">
                    <input type="hidden" name="nomor_meja" id="form_nomor_meja" value="{{ $penjualan->nomor_meja ?? 'Table 1' }}">
                    <input type="hidden" name="tipe_order" id="form_tipe_order" value="{{ $penjualan->tipe_order ?? 'Dine-In' }}">
                    <input type="hidden" name="nama_pelanggan" id="form_nama_pelanggan" value="{{ $penjualan->nama_pelanggan ?? '' }}">
                    <input type="hidden" name="telepon_pelanggan" id="form_telepon_pelanggan" value="{{ $penjualan->telepon_pelanggan ?? '' }}">
                    <input type="hidden" name="alamat_pengiriman" id="form_alamat_pengiriman" value="{{ $penjualan->alamat_pengiriman ?? '' }}">
                </form>

            </div>
        </div>
    </div>
</div>

<!-- ORDER INFO MODAL (Triggered by ORDER INFO button) -->
<div class="modal fade" id="modal-order-info" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="border-radius: 14px;">
            <div class="modal-header" style="background: #1e3a68; color: #fff; border-radius: 14px 14px 0 0;">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
                <h4 class="modal-title" style="font-weight: 700; font-size: 15px; color: #fff;">Order, Table &amp; Delivery Settings</h4>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <div class="form-group">
                    <label style="font-size: 12px; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.04em;">1. Dining Type:</label>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" id="btnTypeDineIn" class="btn btn-default btn-dining-pill {{ ($penjualan->tipe_order ?? 'Dine-In') === 'Dine-In' ? 'active' : '' }}" style="flex:1; font-weight: 700; border-radius: 8px; padding: 9px;" onclick="selectDiningType('Dine-In')">
                            Dine-In
                        </button>
                        <button type="button" id="btnTypeTakeaway" class="btn btn-default btn-dining-pill {{ ($penjualan->tipe_order ?? '') === 'Takeaway' ? 'active' : '' }}" style="flex:1; font-weight: 700; border-radius: 8px; padding: 9px;" onclick="selectDiningType('Takeaway')">
                            Takeaway
                        </button>
                        <button type="button" id="btnTypeDelivery" class="btn btn-default btn-dining-pill {{ ($penjualan->tipe_order ?? '') === 'Delivery' ? 'active' : '' }}" style="flex:1; font-weight: 700; border-radius: 8px; padding: 9px;" onclick="selectDiningType('Delivery')">
                            Delivery
                        </button>
                    </div>
                </div>

                <!-- DINE-IN TABLE SELECTION -->
                <div class="form-group" id="modalTableFormGroup">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <label style="font-size: 12px; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.04em; margin: 0;">2. Select Table:</label>
                        <span style="font-size: 11px; color: #64748b;">
                            <span style="display:inline-block; width:8px; height:8px; background:#22c55e; border-radius:50%;"></span> Free
                            <span style="display:inline-block; width:8px; height:8px; background:#ef4444; border-radius:50%; margin-left: 6px;"></span> Occupied
                        </span>
                    </div>
                    <div class="table-grid-picker" id="tableGridContainer">
                        @foreach($mejaList as $tbl)
                            @php
                                $isOccupied = $tbl->status === 'occupied';
                                $isCur = ($penjualan->nomor_meja ?? '') === $tbl->nomor_meja && (!$isOccupied || $isEditMode);
                            @endphp
                            <div class="table-card-btn {{ $isOccupied ? 'table-occupied' : 'table-available' }} {{ $isCur ? 'table-selected' : '' }}" 
                                 data-table="{{ $tbl->nomor_meja }}"
                                 data-status="{{ $tbl->status }}"
                                 id="tbl_card_{{ preg_replace('/[^a-zA-Z0-9]/', '_', $tbl->nomor_meja) }}"
                                 onclick="chooseTableCard('{{ $tbl->nomor_meja }}', '{{ $tbl->status }}', '{{ $tbl->id_penjualan_aktif ? '#INV-'.tambah_nol_didepan($tbl->id_penjualan_aktif, 5) : '' }}')">
                                <div class="table-card-title">{{ $tbl->nomor_meja }}</div>
                                <span class="table-status-pill {{ $tbl->status === 'occupied' ? 'status-occupied-pill' : 'status-available-pill' }}">
                                    {{ $tbl->status === 'occupied' ? 'Occupied' : 'Free' }}
                                </span>
                                @if($tbl->id_penjualan_aktif && $tbl->status === 'occupied')
                                    <div style="font-size: 8.5px; color: #ef4444; font-weight: 700;">#INV-{{ tambah_nol_didepan($tbl->id_penjualan_aktif, 5) }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" id="modalSelectedTable" value="{{ $penjualan->nomor_meja ?? '' }}">
                </div>

                <!-- DELIVERY SECTION (Visible only for Delivery) -->
                <div id="modalDeliveryFormGroup" style="display: none; background: #f0f9ff; border: 1.5px solid #bae6fd; border-radius: 10px; padding: 14px; margin-bottom: 14px;">
                    <div style="font-weight: 800; font-size: 12.5px; color: #0369a1; margin-bottom: 10px;">
                        Customer Delivery Information
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" style="margin-bottom: 8px;">
                                <label style="font-size: 11px; font-weight: 700; color: #334155;">Customer Name:</label>
                                <input type="text" id="modalDeliveryName" class="form-control" placeholder="e.g. Customer Name" value="{{ $penjualan->nama_pelanggan ?? '' }}" style="border-radius: 6px; font-size: 12.5px;" oninput="$('#form_nama_pelanggan').val(this.value)">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" style="margin-bottom: 8px;">
                                <label style="font-size: 11px; font-weight: 700; color: #334155;">Phone Number:</label>
                                <input type="text" id="modalDeliveryPhone" class="form-control" placeholder="0300-1234567" value="{{ $penjualan->telepon_pelanggan ?? '' }}" style="border-radius: 6px; font-size: 12.5px;" oninput="$('#form_telepon_pelanggan').val(this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 8px;">
                        <label style="font-size: 11px; font-weight: 700; color: #334155;">Delivery Address:</label>
                        <textarea id="modalDeliveryAddress" class="form-control" rows="2" placeholder="House #, Street, Block, Area..." style="border-radius: 6px; font-size: 12px;" oninput="$('#form_alamat_pengiriman').val(this.value)">{{ $penjualan->alamat_pengiriman ?? '' }}</textarea>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 11px; font-weight: 700; color: #334155;">Delivery Fee / Charges ({{ get_currency_symbol() }}):</label>
                        <input type="number" id="modalDeliveryFeeInput" class="form-control" placeholder="0" min="0" value="{{ $penjualan->ongkir ?? 0 }}" style="border-radius: 6px; font-size: 13px; font-weight: 700;" oninput="applyDeliveryFee(this.value)">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 11px; font-weight: 700; color: #334155;">Registered Member:</label>
                            <div style="display: flex; gap: 4px;">
                                <input type="text" id="modalCustomerName" class="form-control" readonly value="{{ $memberSelected->nama ?? 'Walk In Customer' }}" style="border-radius: 6px; font-size: 12px; font-weight: 700;">
                                <button type="button" class="btn btn-default" onclick="tampilMember()" style="border-radius: 6px; font-weight: 700; font-size: 12px; padding: 6px 12px;" title="Select Member">Select</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 11px; font-weight: 700; color: #334155;">Extra Discount (%):</label>
                            <input type="number" id="modalExtraDiscountInput" class="form-control" placeholder="0" min="0" value="{{ $penjualan->diskon ?? 0 }}" style="border-radius: 6px; font-weight: 700; font-size: 12px;" oninput="applyModalExtraDiscount(this.value)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-radius: 0 0 14px 14px;">
                <button type="button" class="btn btn-primary btn-block btn-flat" data-dismiss="modal" style="border-radius: 8px; font-weight: 700;">Apply &amp; Continue</button>
            </div>
        </div>
    </div>
</div>

<!-- PARKED DRAFT & TABLE ORDERS MODAL -->
<div class="modal fade" id="modal-draft-list" tabindex="-1" role="dialog" aria-labelledby="draftModalTitle">
    <div class="modal-dialog draft-modal-dialog" role="document">
        <div class="modal-content draft-modal-content">
            <!-- Modal Header -->
            <div class="draft-modal-header">
                <div class="draft-header-left">
                    <div class="draft-header-icon">
                        <i class="fa fa-pause-circle"></i>
                    </div>
                    <div>
                        <h4 class="draft-modal-title" id="draftModalTitle">
                            Parked Draft Invoices &amp; Table Orders
                            <span class="draft-count-pill" id="draftModalCount">0 Parked</span>
                        </h4>
                        <div class="draft-modal-sub">
                            Resume on-hold tables, takeaway orders, or pending invoices
                        </div>
                    </div>
                </div>
                <div class="draft-header-actions">
                    <div class="draft-search-box">
                        <i class="fa fa-search"></i>
                        <input type="text" id="draftSearchInput" class="draft-search-input" placeholder="Search invoice, table, customer..." onkeyup="filterDraftTable()">
                    </div>
                    <button type="button" class="btn-draft-refresh" onclick="loadDraftList()" title="Refresh List">
                        <i class="fa fa-refresh" id="draftRefreshIcon"></i>
                    </button>
                    <button type="button" class="draft-close-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>

            <!-- Modal Body Table -->
            <div class="draft-modal-body">
                <div class="table-responsive" style="margin-bottom: 0;">
                    <table class="draft-table" id="draftListTable">
                        <thead>
                            <tr>
                                <th style="width: 15%;">Invoice</th>
                                <th style="width: 16%;">Table / Type</th>
                                <th style="width: 16%;">Customer</th>
                                <th style="width: 20%;">Items Details</th>
                                <th style="width: 13%; text-align: right;">Total Bill</th>
                                <th style="width: 10%; text-align: center;">Parked Time</th>
                                <th style="width: 10%; text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="draftListTableBody">
                            <tr>
                                <td colspan="7" class="text-center" style="padding: 40px 20px; color: #64748b;">
                                    <i class="fa fa-spinner fa-spin fa-2x" style="color: #ea580c; margin-bottom: 8px;"></i>
                                    <div style="font-weight: 700; color: #1e293b; font-size: 14px;">Loading parked draft orders...</div>
                                    <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">Fetching active on-hold records from server</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="draft-modal-footer">
                <div class="draft-live-indicator">
                    <span class="live-dot"></span>
                    <span>Live synced with restaurant table occupancy</span>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" style="border-radius: 6px; font-weight: 600; font-size: 12.5px; border-color: #cbd5e1; background: #ffffff;">Close</button>
                    <a href="{{ route('transaksi.baru') }}" class="btn btn-flat" style="background: #ea580c; color: #fff; font-weight: 700; font-size: 12.5px; border-radius: 6px; border: none; padding: 7px 16px;">
                        <i class="fa fa-plus-circle"></i> New Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ITEM COOKING NOTES / ADDONS MODAL -->
<div class="modal fade" id="modal-item-notes" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="max-width: 380px;">
        <div class="modal-content" style="border-radius: 14px;">
            <div class="modal-header" style="background: #ea580c; color: #fff; border-radius: 14px 14px 0 0;">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
                <h4 class="modal-title" style="font-weight: 700; font-size: 14px; color: #fff;">Cooking Notes &amp; Tags</h4>
            </div>
            <div class="modal-body" style="padding: 16px;">
                <input type="hidden" id="noteDetailId">
                
                <label style="font-size: 11px; font-weight: 800; color: #475569; text-transform: uppercase; margin-bottom: 6px; display: block;">Quick Tags:</label>
                <div class="chips-container">
                    <button type="button" class="btn-quick-chip" onclick="toggleQuickChip('+ Extra Mayo')">+ Extra Mayo</button>
                    <button type="button" class="btn-quick-chip" onclick="toggleQuickChip('+ Extra Sauce')">+ Extra Sauce</button>
                    <button type="button" class="btn-quick-chip" onclick="toggleQuickChip('+ Extra Cheese')">+ Extra Cheese</button>
                    <button type="button" class="btn-quick-chip" onclick="toggleQuickChip('+ Extra Spicy')">+ Extra Spicy</button>
                    <button type="button" class="btn-quick-chip" onclick="toggleQuickChip('Less Spicy')">Less Spicy</button>
                    <button type="button" class="btn-quick-chip" onclick="toggleQuickChip('- No Onion')">- No Onion</button>
                    <button type="button" class="btn-quick-chip" onclick="toggleQuickChip('- No Tomato')">- No Tomato</button>
                    <button type="button" class="btn-quick-chip" onclick="toggleQuickChip('Separate Packing')">Separate Packing</button>
                    <button type="button" class="btn-quick-chip" onclick="toggleQuickChip('Crispy')">Crispy</button>
                    <button type="button" class="btn-quick-chip" onclick="toggleQuickChip('Sauce On Side')">Sauce On Side</button>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-size: 11px; font-weight: 700; color: #334155;">Custom Instructions:</label>
                    <textarea id="itemNoteInput" class="form-control" rows="3" placeholder="e.g. Extra mayo, No onions..." style="border-radius: 8px; font-size: 12.5px; font-weight: 600;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-radius: 0 0 14px 14px; display: flex; gap: 8px;">
                <button type="button" class="btn btn-default btn-flat" style="flex:1; border-radius: 6px;" onclick="clearItemNote()">Clear</button>
                <button type="button" class="btn btn-primary btn-flat" style="flex:2; border-radius: 6px; font-weight: 700; background: #ea580c; border-color: #ea580c;" onclick="saveItemNote()">Save Note</button>
            </div>
        </div>
    </div>
</div>

@includeIf('penjualan_detail.member')
@endsection

@push('scripts')
<script>
    let CURRENCY_SYMBOL = '{{ get_currency_symbol() }}';
    let IS_EDIT_MODE = {{ $isEditMode ? 'true' : 'false' }};
    let currentCartData = { items: [], total: 0, total_item: 0, product_discount: 0 };
    let extraDiscount = {{ (float)($penjualan->diskon ?? 0) }};
    let deliveryFee = {{ (float)($penjualan->ongkir ?? 0) }};
    let currentDiningType = '{{ $penjualan->tipe_order ?? "Dine-In" }}';
    let currentTable = '{{ $penjualan->nomor_meja ?? "Table 1" }}';
    let selectedPaymentMethod = '{{ $isEditMode ? strtolower($penjualan->metode_pembayaran ?? "cash") : "cash" }}';

    $(function () {
        loadCart();
        loadTablesStatus();
        loadDraftCountBadge();

        // Initialize values
        setPaymentMethod(selectedPaymentMethod || 'cash');
        selectDiningType(currentDiningType);
        $('#modalDeliveryFeeInput').val(deliveryFee);
        $('#modalExtraDiscountInput').val(extraDiscount);

        // Search in products
        $('#foodSearchInput').on('keyup', function () {
            filterMenuGrid();
        });

        // Category dropdown filter
        $('#catSelectFilter').on('change', function () {
            let cat = $(this).val();
            $('.pos-pill-tab').removeClass('active');
            $(`.pos-pill-tab[data-category="${cat}"]`).addClass('active');
            filterMenuGrid();
        });

        // Category pill tab click handler
        $('.pos-pill-tab').on('click', function () {
            $('.pos-pill-tab').removeClass('active');
            $(this).addClass('active');
            let cat = $(this).data('category');
            $('#catSelectFilter').val(cat);
            filterMenuGrid();
        });
    });

    function setPaymentMethod(method) {
        selectedPaymentMethod = method || 'cash';
        $('#posPaymentMethodSelect').val(selectedPaymentMethod);
        $('#form_metode_pembayaran').val(selectedPaymentMethod);
    }

    function openOrderInfoModal() {
        loadTablesStatus();
        $('#modal-order-info').modal('show');
    }

    function selectDiningType(type) {
        currentDiningType = type;
        $('#form_tipe_order').val(type);

        $('.btn-dining-pill').removeClass('active').css({ 'background': '#f8fafc', 'color': '#334155', 'border-color': '#e2e8f0' });
        if (type === 'Dine-In') {
            $('#btnTypeDineIn').addClass('active').css({ 'background': '#1e3a68', 'color': '#fff', 'border-color': '#1e3a68' });
            $('#modalTableFormGroup').show();
            $('#modalDeliveryFormGroup').hide();
            $('#rowDeliveryFee').hide();
            $('#form_nomor_meja').val(currentTable || 'Table 1');
        } else if (type === 'Delivery') {
            $('#btnTypeDelivery').addClass('active').css({ 'background': '#0284c7', 'color': '#fff', 'border-color': '#0284c7' });
            $('#modalTableFormGroup').hide();
            $('#modalDeliveryFormGroup').show();
            if (deliveryFee > 0) {
                $('#rowDeliveryFee').show();
            }
            $('#form_nomor_meja').val('Delivery');
        } else {
            // Takeaway
            $('#btnTypeTakeaway').addClass('active').css({ 'background': '#f59e0b', 'color': '#fff', 'border-color': '#f59e0b' });
            $('#modalTableFormGroup').hide();
            $('#modalDeliveryFormGroup').hide();
            $('#rowDeliveryFee').hide();
            $('#form_nomor_meja').val('Takeaway');
        }

        renderCartUI(currentCartData);
    }

    function chooseTableCard(tableName, status, activeInvoice = '') {
        if (status === 'occupied') {
            if (!IS_EDIT_MODE || tableName !== '{{ $penjualan->nomor_meja ?? "" }}') {
                Swal.fire({
                    title: '⚠️ ' + tableName + ' is Busy!',
                    html: `This table is currently occupied by active order <strong>${activeInvoice || 'in progress'}</strong>.<br><br><span style="color:#ef4444; font-weight:700;">Please select an available (Free 🟢) table.</span>`,
                    icon: 'warning',
                    confirmButtonColor: '#ff521d'
                });
                return;
            }
        }

        currentTable = tableName;
        $('#modalSelectedTable').val(tableName);
        $('#form_nomor_meja').val(tableName);

        $('.table-card-btn').removeClass('table-selected');
        let cardId = '#tbl_card_' + tableName.replace(/[^a-zA-Z0-9]/g, '_');
        $(cardId).addClass('table-selected');

        showSuccessToast('Selected ' + tableName);
    }

    function loadTablesStatus() {
        $.get('{{ route('transaksi.tables_status') }}')
            .done(tables => {
                let container = $('#tableGridContainer');
                if (!tables || tables.length === 0) return;

                container.empty();
                let currentTableStillValid = false;

                tables.forEach(tbl => {
                    let isOcc = (tbl.status === 'occupied');
                    let isCur = (currentTable === tbl.nomor_meja);
                    
                    if (isCur && (!isOcc || IS_EDIT_MODE)) {
                        currentTableStillValid = true;
                    }

                    let cardClass = isOcc ? 'table-occupied' : 'table-available';
                    if (isCur && (!isOcc || IS_EDIT_MODE)) cardClass += ' table-selected';

                    let badgeClass = isOcc ? 'status-occupied-pill' : 'status-available-pill';
                    let badgeText = isOcc ? 'Occupied' : 'Free';
                    let subText = (tbl.active_invoice && isOcc) ? `<div style="font-size:8.5px; color:#ef4444; font-weight:700;">${tbl.active_invoice}</div>` : '';

                    container.append(`
                        <div class="table-card-btn ${cardClass}" 
                             data-table="${tbl.nomor_meja}" 
                             data-status="${tbl.status}"
                             id="tbl_card_${tbl.nomor_meja.replace(/[^a-zA-Z0-9]/g, '_')}"
                             onclick="chooseTableCard('${tbl.nomor_meja}', '${tbl.status}', '${tbl.active_invoice || ''}')">
                            <div class="table-card-title">${tbl.nomor_meja}</div>
                            <span class="table-status-pill ${badgeClass}">${badgeText}</span>
                            ${subText}
                        </div>
                    `);
                });

                // If current table is occupied by another order, auto-select first available free table
                if (!currentTableStillValid && !IS_EDIT_MODE) {
                    let firstFree = tables.find(t => t.status === 'available');
                    if (firstFree) {
                        currentTable = firstFree.nomor_meja;
                        $('#modalSelectedTable').val(currentTable);
                        $('#form_nomor_meja').val(currentTable);
                        let cardId = '#tbl_card_' + currentTable.replace(/[^a-zA-Z0-9]/g, '_');
                        $('.table-card-btn').removeClass('table-selected');
                        $(cardId).addClass('table-selected');
                    }
                }
            });
    }

    function applyDeliveryFee(val) {
        deliveryFee = Math.max(0, parseFloat(val) || 0);
        $('#form_ongkir').val(deliveryFee);
        if (currentDiningType === 'Delivery' && deliveryFee > 0) {
            $('#rowDeliveryFee').show();
        } else {
            $('#rowDeliveryFee').hide();
        }
        renderCartUI(currentCartData);
    }

    function applyModalExtraDiscount(val) {
        extraDiscount = Math.max(0, parseFloat(val) || 0);
        $('#form_diskon').val(extraDiscount);
        renderCartUI(currentCartData);
    }

    function filterMenuGrid() {
        let keyword = ($('#foodSearchInput').val() || '').toLowerCase().trim();
        let selectedCat = ($('#catSelectFilter').val() || 'all').toString();

        let visibleCount = 0;
        $('#posProductGrid .pos-item-card').each(function () {
            let name = ($(this).data('name') || '').toString().toLowerCase();
            let code = ($(this).data('code') || '').toString().toLowerCase();
            let cat = ($(this).data('category') || '').toString();
            let brand = ($(this).data('brand') || '').toString().toLowerCase();
            let isDeal = ($(this).data('is-deal') || '0').toString();

            let matchKeyword = (keyword === '' || name.indexOf(keyword) > -1 || code.indexOf(keyword) > -1);
            let matchCat = false;
            if (selectedCat === 'all') {
                matchCat = true;
            } else if (selectedCat === 'deals') {
                matchCat = (isDeal === '1' || brand === 'deal combo');
            } else {
                matchCat = (cat === selectedCat);
            }

            if (matchKeyword && matchCat) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });

        if (visibleCount === 0 && $('#posProductGrid .pos-item-card').length > 0) {
            if ($('#noFilterResultsMsg').length === 0) {
                $('#posProductGrid').append('<div id="noFilterResultsMsg" class="text-center" style="grid-column: 1 / -1; padding: 40px 20px; color: #94a3b8;"><i class="fa fa-search" style="font-size: 32px; margin-bottom: 8px; color: #cbd5e1; display: block;"></i><h4 style="font-weight: 700; color: #475569;">No items found in this category</h4><p style="font-size: 12.5px;">Try selecting "All Items" or searching for a different keyword.</p></div>');
            }
            $('#noFilterResultsMsg').show();
        } else {
            $('#noFilterResultsMsg').hide();
        }
    }

    function loadCart() {
        $.get('{{ route('transaksi.data', $id_penjualan) }}')
            .done(response => {
                if (response.currency_symbol) {
                    CURRENCY_SYMBOL = response.currency_symbol;
                }
                currentCartData = response;
                renderCartUI(response);
            })
            .fail(errors => {
                showErrorToast('Unable to load cart');
            });
    }

    function renderCartUI(data) {
        let container = $('#posOrderItemsContainer');
        container.empty();

        if (!data.items || data.items.length === 0) {
            container.html(`
                <div class="pos-empty-cart-box">
                    <i class="fa fa-shopping-basket"></i>
                    <p style="margin: 0; font-size: 12px;">No items in current order.<br>Click any food item on the left to add.</p>
                </div>
            `);
            $('#lblSubtotal').text(CURRENCY_SYMBOL + ' 0.00');
            $('#lblDiscountAndTax').text(CURRENCY_SYMBOL + ' 0.00');
            $('#lblDeliveryFee').text(CURRENCY_SYMBOL + ' 0.00');
            $('#lblGrandTotal').text(CURRENCY_SYMBOL + ' 0.00');
            $('#form_total').val(0);
            $('#form_total_item').val(0);
            $('#form_bayar').val(0);
            $('#form_diterima').val(0);
            return;
        }

        let html = '';
        data.items.forEach(item => {
            let noteText = item.catatan || '';
            let noteHtml = noteText ? `<div style="font-size: 11px; color: #ea580c; font-weight: 700; margin-top: 3px;"><i class="fa fa-tag"></i> ${noteText}</div>` : '';

            html += `
                <div class="pos-food-card">
                    <div class="pos-food-info">
                        <div class="pos-food-name" title="${item.nama_produk}">${item.nama_produk}</div>
                        <div class="pos-food-price">${CURRENCY_SYMBOL} ${item.harga_jual}</div>
                        ${noteHtml}
                        <div>
                            <button type="button" class="btn-food-note" onclick="addCookingNote(${item.id_detail}, '${escapeHtml(noteText)}')">
                                <i class="fa fa-pencil"></i> ${noteText ? 'Edit Note' : '+ Note / Add-ons'}
                            </button>
                        </div>
                    </div>
                    <div class="pos-food-stepper">
                        <button type="button" class="btn-circle-minus" onclick="updateItemQuantity(${item.id_detail}, ${item.jumlah - 1})"><i class="fa fa-minus"></i></button>
                        <span class="pos-food-qty">${item.jumlah}</span>
                        <button type="button" class="btn-circle-plus" onclick="updateItemQuantity(${item.id_detail}, ${item.jumlah + 1})"><i class="fa fa-plus"></i></button>
                        <span class="pos-trash-btn" onclick="deleteData('${item.delete_url}')" title="Remove item">
                            <i class="fa fa-trash-o"></i>
                        </span>
                    </div>
                </div>
            `;
        });

        container.html(html);

        // Update Summary Calculations
        let memberDiscPercent = extraDiscount;
        let couponDiscountAmount = (memberDiscPercent / 100) * data.total;
        let totalDiscount = couponDiscountAmount + (data.product_discount || 0);
        let activeDeliveryFee = (currentDiningType === 'Delivery') ? deliveryFee : 0;
        let finalGrandTotal = Math.max(0, (data.total - totalDiscount) + activeDeliveryFee);

        $('#lblSubtotal').text(CURRENCY_SYMBOL + ' ' + (data.total_rp || data.total.toLocaleString()));
        $('#lblDiscountAndTax').text(totalDiscount > 0 ? ('-' + CURRENCY_SYMBOL + ' ' + totalDiscount.toLocaleString()) : (CURRENCY_SYMBOL + ' 0.00'));
        $('#lblDeliveryFee').text('+' + CURRENCY_SYMBOL + ' ' + activeDeliveryFee.toLocaleString());
        $('#lblGrandTotal').text(CURRENCY_SYMBOL + ' ' + finalGrandTotal.toLocaleString());

        // Form Fields
        $('#form_total').val(data.total);
        $('#form_total_item').val(data.total_item);
        $('#form_bayar').val(finalGrandTotal);
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    function addItemToCart(id_produk) {
        $.post('{{ route('transaksi.store') }}', {
            '_token': $('[name=csrf-token]').attr('content'),
            'id_penjualan': '{{ $id_penjualan }}',
            'id_produk': id_produk
        })
        .done(response => {
            loadCart();
            showSuccessToast('Item added to order');
        })
        .fail(errors => {
            showErrorToast('Failed to add item to cart');
        });
    }

    function updateItemQuantity(id_detail, newQty) {
        if (newQty < 1) {
            showWarningToast('Minimum quantity is 1. Tap trash icon to remove.');
            return;
        }
        $.post(`{{ url('/transaksi') }}/${id_detail}`, {
            '_token': $('[name=csrf-token]').attr('content'),
            '_method': 'put',
            'jumlah': newQty
        })
        .done(response => {
            loadCart();
        })
        .fail(errors => {
            showErrorToast('Unable to update quantity');
        });
    }

    function deleteData(url) {
        showConfirmDialog('Remove Item?', 'Do you want to remove this dish from the order?', 'Yes, remove', function() {
            $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done((response) => {
                loadCart();
                showSuccessToast('Item removed from cart');
            })
            .fail((errors) => {
                showErrorToast('Unable to remove item');
            });
        });
    }

    // 1-Click Cooking Notes Modal
    function addCookingNote(detailId, currentNote) {
        $('#noteDetailId').val(detailId);
        $('#itemNoteInput').val(currentNote || '');
        $('#modal-notes').modal('show');
    }

    function toggleQuickChip(tag) {
        let input = $('#itemNoteInput');
        let val = input.val().trim();
        if (val.indexOf(tag) > -1) {
            // Remove tag
            val = val.replace(tag, '').replace(/,\s*,/g, ',').replace(/^,\s*/, '').replace(/,\s*$/, '').trim();
        } else {
            // Append tag
            val = val ? (val + ', ' + tag) : tag;
        }
        input.val(val);
    }

    function clearItemNote() {
        $('#itemNoteInput').val('');
    }

    function saveItemNote() {
        let detailId = $('#noteDetailId').val();
        let note = $('#itemNoteInput').val().trim();

        $.post(`{{ url('/transaksi/item-note') }}/${detailId}`, {
            '_token': $('[name=csrf-token]').attr('content'),
            'catatan': note
        })
        .done(response => {
            $('#modal-notes').modal('hide');
            loadCart();
            showSuccessToast('Kitchen instruction saved');
        })
        .fail(errors => {
            showErrorToast('Failed to save note');
        });
    }

    // PLACE ORDER SUBMIT
    function placeOrderSubmit() {
        if (!currentCartData.items || currentCartData.items.length === 0) {
            showWarningToast('Your cart is empty! Tap any dish on the left to add.');
            return;
        }

        let payable = parseFloat($('#form_bayar').val()) || 0;

        let data = {
            '_token': $('[name=csrf-token]').attr('content'),
            'id_penjualan': '{{ $id_penjualan }}',
            'total': $('#form_total').val(),
            'total_item': $('#form_total_item').val(),
            'bayar': payable,
            'diskon': $('#form_diskon').val(),
            'ongkir': (currentDiningType === 'Delivery') ? $('#form_ongkir').val() : 0,
            'diterima': $('#form_diterima').val() || 0,
            'status_pembayaran': $('#form_status_pembayaran').val() || 'unpaid',
            'metode_pembayaran': selectedPaymentMethod,
            'id_member': $('#form_id_member').val(),
            'nomor_meja': $('#form_nomor_meja').val() || 'Table 1',
            'tipe_order': $('#form_tipe_order').val() || 'Dine-In',
            'nama_pelanggan': $('#form_nama_pelanggan').val(),
            'telepon_pelanggan': $('#form_telepon_pelanggan').val(),
            'alamat_pengiriman': $('#form_alamat_pengiriman').val()
        };

        $.post('{{ route('transaksi.simpan') }}', data)
            .done(response => {
                let isEdit = (response.is_editing || IS_EDIT_MODE);
                let swalTitle = isEdit ? 'Invoice Updated Successfully!' : 'Order Placed & Sent to Kitchen!';
                let nextUrl = isEdit ? "{{ route('penjualan.index') }}" : "{{ route('transaksi.baru') }}";
                let kotUrl = response.kot_url || `{{ url('/kitchen/kot') }}/${response.id_penjualan}`;
                let receiptUrl = response.print_url;

                Swal.fire({
                    title: swalTitle,
                    html: `
                        <div style="font-size:14px; margin-top:8px;">
                            <strong>${response.invoice}</strong> saved.<br>
                            <span style="color:#64748b;">Direct Thermal Print Options:</span>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:8px; margin-top:16px;">
                            <button type="button" class="btn btn-warning" style="font-weight:700; padding:10px; border-radius:8px;" onclick="window.open('${kotUrl}', '_blank')">
                                Print Kitchen KOT Ticket [K]
                            </button>
                            <button type="button" class="btn btn-primary" style="font-weight:700; padding:10px; border-radius:8px;" onclick="window.open('${receiptUrl}', '_blank')">
                                Print Customer Bill [P]
                            </button>
                        </div>
                    `,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#ff521d',
                    cancelButtonColor: '#0f172a',
                    confirmButtonText: 'Start Next Order',
                    cancelButtonText: 'View Invoices'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('transaksi.baru') }}";
                    } else {
                        window.location.href = "{{ route('penjualan.index') }}";
                    }
                });
            })
            .fail(errors => {
                let msg = errors.responseJSON ? (errors.responseJSON.error || errors.responseJSON.message) : 'Failed to save order';
                Swal.fire({
                    title: 'Cannot Save Order',
                    html: `<div style="font-size:14px; color:#ef4444; font-weight:700;">${msg}</div>`,
                    icon: 'error',
                    confirmButtonColor: '#ff521d'
                });
            });
    }

    // SAVE DRAFT INVOICE
    function saveAsDraft() {
        if (!currentCartData.items || currentCartData.items.length === 0) {
            showWarningToast('Cart is empty, nothing to save as draft.');
            return;
        }

        $.post('{{ route('transaksi.draft') }}', {
            '_token': $('[name=csrf-token]').attr('content'),
            'id_penjualan': '{{ $id_penjualan }}',
            'nomor_meja': $('#form_nomor_meja').val() || 'Table 1',
            'tipe_order': $('#form_tipe_order').val() || 'Dine-In',
            'diskon': $('#form_diskon').val(),
            'ongkir': $('#form_ongkir').val() || 0,
            'id_member': $('#form_id_member').val(),
            'nama_pelanggan': $('#form_nama_pelanggan').val(),
            'telepon_pelanggan': $('#form_telepon_pelanggan').val(),
            'alamat_pengiriman': $('#form_alamat_pengiriman').val()
        })
        .done(response => {
            Swal.fire({
                title: 'Order Drafted!',
                text: response.message,
                icon: 'success',
                confirmButtonColor: '#f97316',
                confirmButtonText: 'Start Next Order'
            }).then(() => {
                window.location.href = "{{ route('transaksi.baru') }}";
            });
        })
        .fail(errors => {
            let msg = errors.responseJSON ? (errors.responseJSON.error || errors.responseJSON.message) : 'Failed to save draft invoice';
            Swal.fire({
                title: 'Cannot Park Draft',
                html: `<div style="font-size:14px; color:#ef4444; font-weight:700;">${msg}</div>`,
                icon: 'error',
                confirmButtonColor: '#ff521d'
            });
        });
    }

    // DRAFT & PARKED ORDERS MANAGEMENT
    let allDraftsData = [];

    function loadDraftCountBadge() {
        $.get('{{ route('transaksi.draft_list') }}')
            .done(res => {
                let drafts = Array.isArray(res) ? res : (res.data || []);
                allDraftsData = drafts;
                updateDraftCounts(drafts.length);
            })
            .fail(() => {
                // silently fail for badge count
            });
    }

    function showDraftListModal() {
        $('#modal-draft-list').modal('show');
        $('#draftSearchInput').val('');
        loadDraftList();
    }

    function loadDraftList() {
        let refreshIcon = $('#draftRefreshIcon');
        refreshIcon.addClass('fa-spin');

        $('#draftListTableBody').html(`
            <tr>
                <td colspan="7" class="text-center" style="padding: 40px 20px; color: #64748b;">
                    <i class="fa fa-spinner fa-spin fa-2x" style="color: #ea580c; margin-bottom: 8px;"></i>
                    <div style="font-weight: 700; color: #1e293b; font-size: 14px;">Loading parked draft orders...</div>
                    <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">Fetching active on-hold records from server</div>
                </td>
            </tr>
        `);

        $.get('{{ route('transaksi.draft_list') }}')
            .done(res => {
                refreshIcon.removeClass('fa-spin');
                let drafts = Array.isArray(res) ? res : (res.data || []);
                allDraftsData = drafts;
                updateDraftCounts(drafts.length);
                renderDraftsTable(drafts);
            })
            .fail(() => {
                refreshIcon.removeClass('fa-spin');
                $('#draftListTableBody').html(`
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 35px 20px;">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: inline-flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 10px;">
                                <i class="fa fa-exclamation-triangle"></i>
                            </div>
                            <h4 style="font-weight: 700; color: #1e293b; margin: 0 0 6px 0; font-size: 15px;">Failed to Load Drafts</h4>
                            <p style="font-size: 12.5px; color: #64748b; margin: 0 0 12px 0;">Could not connect to server or retrieve parked invoice list.</p>
                            <button type="button" class="btn btn-sm btn-flat" onclick="loadDraftList()" style="background: #ea580c; color: #fff; border-radius: 6px; font-weight: 700;">
                                <i class="fa fa-refresh"></i> Retry Again
                            </button>
                        </td>
                    </tr>
                `);
            });
    }

    function updateDraftCounts(count) {
        $('#draftModalCount').text(count + ' Parked');
        let topBadge = $('#posDraftCountBadge');
        if (topBadge.length) {
            if (count > 0) {
                topBadge.text(count).show();
            } else {
                topBadge.hide();
            }
        }
    }

    function renderDraftsTable(drafts) {
        let tbody = $('#draftListTableBody');
        tbody.empty();

        if (!drafts || drafts.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="7" class="text-center" style="padding: 45px 20px;">
                        <div style="width: 56px; height: 56px; border-radius: 50%; background: #f0fdf4; color: #10b981; display: inline-flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 12px; border: 1px solid #bbf7d0;">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <h4 style="font-weight: 800; color: #0f172a; margin: 0 0 4px 0; font-size: 16px;">No Active Draft Orders</h4>
                        <p style="font-size: 12.5px; color: #64748b; margin: 0 0 16px 0;">All table and takeaway orders have been settled or are currently empty.</p>
                        <a href="{{ route('transaksi.baru') }}" class="btn btn-sm btn-flat" style="background: #ea580c; color: #fff; font-weight: 700; border-radius: 6px; padding: 7px 18px;">
                            <i class="fa fa-plus-circle"></i> Start New Order
                        </a>
                    </td>
                </tr>
            `);
            return;
        }

        drafts.forEach(d => {
            let orderType = d.tipe_order || 'Dine-In';
            let typeBadge = '';
            if (orderType === 'Delivery') {
                typeBadge = `<span class="draft-type-pill draft-type-delivery"><i class="fa fa-motorcycle"></i> ${d.nomor_meja || 'Delivery'}</span>`;
            } else if (orderType === 'Takeaway') {
                typeBadge = `<span class="draft-type-pill draft-type-takeaway"><i class="fa fa-shopping-bag"></i> ${d.nomor_meja || 'Takeaway'}</span>`;
            } else {
                typeBadge = `<span class="draft-type-pill draft-type-dinein"><i class="fa fa-cutlery"></i> ${d.nomor_meja || 'Table'}</span>`;
            }

            let customerPhone = d.telepon_pelanggan ? `<div class="draft-customer-phone"><i class="fa fa-phone" style="font-size:10px;"></i> ${d.telepon_pelanggan}</div>` : '';

            let currentOrderBadge = d.is_current ? `<span class="label label-primary" style="font-size:9.5px; margin-left:4px; border-radius:3px;">Current</span>` : '';

            tbody.append(`
                <tr>
                    <td>
                        <span class="draft-inv-code">${d.invoice} ${currentOrderBadge}</span>
                        <div class="draft-cashier-sub"><i class="fa fa-user-circle-o"></i> ${d.cashier_name || 'Cashier'}</div>
                    </td>
                    <td>
                        ${typeBadge}
                    </td>
                    <td>
                        <div class="draft-customer-name">
                            <i class="fa fa-user text-muted" style="font-size: 11px;"></i>
                            <span>${d.member}</span>
                        </div>
                        ${customerPhone}
                    </td>
                    <td>
                        <span class="draft-item-count-badge">${d.total_item} ${d.total_item === 1 ? 'item' : 'items'}</span>
                        <div class="draft-items-preview" title="${d.items_summary}">
                            ${d.items_summary}
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <div class="draft-amount-val">${d.bayar}</div>
                        <span class="draft-status-pill">On-Hold</span>
                    </td>
                    <td style="text-align: center;">
                        <div style="font-weight: 700; font-size: 12px; color: #1e293b;">${d.time_ago || ''}</div>
                        <div style="font-size: 11px; color: #94a3b8; margin-top: 1px;">${d.created_at}</div>
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <div class="draft-actions-wrap">
                            <a href="${d.resume_url}" class="btn-draft-resume" title="Resume order in POS">
                                <i class="fa fa-play-circle"></i> Resume
                            </a>
                            <button type="button" onclick="deleteDraftItem('${d.delete_url}')" class="btn-draft-delete" title="Delete draft">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `);
        });
    }

    function filterDraftTable() {
        let q = ($('#draftSearchInput').val() || '').toLowerCase().trim();
        if (!q) {
            renderDraftsTable(allDraftsData);
            return;
        }

        let filtered = allDraftsData.filter(d => {
            return (d.invoice && d.invoice.toLowerCase().includes(q)) ||
                   (d.nomor_meja && d.nomor_meja.toLowerCase().includes(q)) ||
                   (d.tipe_order && d.tipe_order.toLowerCase().includes(q)) ||
                   (d.member && d.member.toLowerCase().includes(q)) ||
                   (d.items_summary && d.items_summary.toLowerCase().includes(q)) ||
                   (d.cashier_name && d.cashier_name.toLowerCase().includes(q));
        });

        renderDraftsTable(filtered);
    }

    function deleteDraftItem(url) {
        showConfirmDialog('Delete Draft?', 'Are you sure you want to delete this drafted order and release the table?', 'Yes, delete', function() {
            $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done(res => {
                showSuccessToast('Draft deleted & Table Released');
                loadDraftList();
                loadTablesStatus();
                loadDraftCountBadge();
            })
            .fail(() => {
                showErrorToast('Failed to delete draft');
            });
        });
    }

    function tampilMember() {
        $('#modal-member').modal('show');
    }

    function pilihMember(id, kode) {
        $('#form_id_member').val(id);
        $('#form_diskon').val('{{ $diskon }}');
        $('#modalCustomerName').val(kode);
        $('#modal-member').modal('hide');
        renderCartUI(currentCartData);
        showSuccessToast('VIP Member applied: {{ $diskon }}% discount');
    }
</script>
@endpush