@extends('layouts.master')

@section('title')
Create Invoice
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
        box-shadow: 0 4px 10px rgba(249, 115, 22, 0.28);
        transition: all 0.2s ease;
    }

    .btn-pos-pill-new:hover {
        background: #ea580c !important;
        transform: translateY(-1px);
        color: #ffffff !important;
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
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        user-select: none;
        height: auto;
    }

    .pos-item-card:hover {
        transform: translateY(-2px);
        border-color: #f97316;
        box-shadow: 0 6px 14px -3px rgba(249, 115, 22, 0.2);
    }

    .pos-item-card:active {
        transform: scale(0.97);
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
        transition: transform 0.2s ease;
        padding: 2px;
    }

    .pos-item-card:hover .pos-item-img {
        transform: scale(1.06);
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
        transform: scale(1.1);
        background: #ea580c;
    }

    /* RIGHT: CURRENT ORDER WIDGET */
    .pos-order-widget {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
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
        box-shadow: 0 8px 18px rgba(255, 87, 34, 0.35);
        transition: all 0.2s ease;
    }

    .btn-place-order:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(255, 87, 34, 0.45);
        color: #ffffff;
    }

    .btn-place-order:active {
        transform: scale(0.98);
    }

    /* Modal Details */
    .modal-content {
        border-radius: 14px !important;
        border: none !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="pos-screen-wrapper">
    <!-- Top POS Header -->
    <div class="pos-top-bar">
        <div>
            <h1 class="pos-heading-title">
                Create Invoice
                @if($isEditMode)
                    <span class="badge" style="background: #2563eb; color: #fff; font-size: 12px; font-weight: 800; padding: 5px 12px; border-radius: 6px; margin-left: 8px; vertical-align: middle; box-shadow: 0 2px 6px rgba(37,99,235,0.35);">
                        <i class="fa fa-edit"></i> EDITING #INV-{{ tambah_nol_didepan($penjualan->id_penjualan, 5) }}
                    </span>
                @endif
            </h1>
            <div class="pos-heading-sub">
                @if($isEditMode)
                    <span style="color: #2563eb; font-weight: 700;">Edit Mode Active</span> &bull; Modify items, table, customer or discounts and click Save &amp; Update
                @else
                    Dashboard &bull; Create Invoice
                @endif
            </div>
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
            <button type="button" class="btn btn-pos-pill-outline" onclick="showDraftListModal()">
                <i class="fa fa-clock-o"></i> Draft List
            </button>
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
                        <option value="deals">🎁 Deals</option>
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
                        <i class="fa fa-gift text-warning"></i> Deals
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
                                <i class="fa fa-gift"></i> DEAL
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
                        <i class="fa fa-info-circle"></i> ORDER INFO
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
                    <input type="hidden" name="diterima" id="form_diterima" value="{{ $penjualan->diterima ?? 0 }}">
                    <input type="hidden" name="status_pembayaran" id="form_status_pembayaran" value="{{ $penjualan->status_pembayaran ?? 'unpaid' }}">
                    <input type="hidden" name="metode_pembayaran" id="form_metode_pembayaran" value="{{ $penjualan->metode_pembayaran ?? 'cash' }}">
                    <input type="hidden" name="id_member" id="form_id_member" value="{{ $memberSelected->id_member ?? '' }}">
                    <input type="hidden" name="nomor_meja" id="form_nomor_meja" value="{{ $penjualan->nomor_meja ?? 'Table 1' }}">
                    <input type="hidden" name="tipe_order" id="form_tipe_order" value="{{ $penjualan->tipe_order ?? 'Dine-In' }}">
                </form>

            </div>
        </div>
    </div>
</div>

<!-- ORDER INFO MODAL (Triggered by ⓘ ORDER INFO button) -->
<div class="modal fade" id="modal-order-info" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="background: #1e3a68; color: #fff; border-radius: 12px 12px 0 0;">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
                <h4 class="modal-title" style="font-weight: 800; font-size: 14px; color: #fff;"><i class="fa fa-info-circle"></i> Order &amp; Table Settings</h4>
            </div>
            <div class="modal-body" style="padding: 16px;">
                <div class="form-group">
                    <label style="font-size: 12px; font-weight: 700; color: #334155;">Order Dining Type:</label>
                    <select id="modalDiningSelect" class="form-control" style="border-radius: 8px; font-weight: 700;" onchange="handleModalDiningChange(this.value)">
                        <option value="Dine-In" {{ ($penjualan->tipe_order ?? 'Dine-In') === 'Dine-In' ? 'selected' : '' }}>Dine-In</option>
                        <option value="Takeaway" {{ ($penjualan->tipe_order ?? '') === 'Takeaway' ? 'selected' : '' }}>Takeaway</option>
                        <option value="Delivery" {{ ($penjualan->tipe_order ?? '') === 'Delivery' ? 'selected' : '' }}>Delivery</option>
                    </select>
                </div>

                <div class="form-group" id="modalTableFormGroup">
                    <label style="font-size: 12px; font-weight: 700; color: #334155;">Table Selection:</label>
                    <select id="modalTableSelect" class="form-control" style="border-radius: 8px; font-weight: 700;">
                        <option value="Table 1" {{ ($penjualan->nomor_meja ?? 'Table 1') === 'Table 1' ? 'selected' : '' }}>Table 1</option>
                        <option value="Table 2" {{ ($penjualan->nomor_meja ?? '') === 'Table 2' ? 'selected' : '' }}>Table 2</option>
                        <option value="Table 3" {{ ($penjualan->nomor_meja ?? '') === 'Table 3' ? 'selected' : '' }}>Table 3</option>
                        <option value="Table 4" {{ ($penjualan->nomor_meja ?? '') === 'Table 4' ? 'selected' : '' }}>Table 4</option>
                        <option value="Table 5" {{ ($penjualan->nomor_meja ?? '') === 'Table 5' ? 'selected' : '' }}>Table 5</option>
                        <option value="VIP Table" {{ ($penjualan->nomor_meja ?? '') === 'VIP Table' ? 'selected' : '' }}>VIP Table</option>
                    </select>
                </div>

                <div class="form-group">
                    <label style="font-size: 12px; font-weight: 700; color: #334155;">Customer / Member:</label>
                    <div style="display: flex; gap: 6px;">
                        <input type="text" id="modalCustomerName" class="form-control" readonly value="{{ $memberSelected->nama ?? 'Walk In Customer' }}" style="border-radius: 8px; font-weight: 700;">
                        <button type="button" class="btn btn-default" onclick="tampilMember()" style="border-radius: 8px;" title="Select Member"><i class="fa fa-user-plus"></i></button>
                    </div>
                </div>

                <div class="form-group">
                    <label style="font-size: 12px; font-weight: 700; color: #334155;">Extra Discount ({{ get_currency_symbol() }}):</label>
                    <input type="number" id="modalExtraDiscountInput" class="form-control" placeholder="0" min="0" value="{{ $penjualan->diskon ?? 0 }}" style="border-radius: 8px; font-weight: 700;" oninput="applyModalExtraDiscount(this.value)">
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-primary btn-block btn-flat" data-dismiss="modal" style="border-radius: 8px; font-weight: 700;">Apply &amp; Close</button>
            </div>
        </div>
    </div>
</div>

<!-- DRAFT & TABLE ORDERS MODAL -->
<div class="modal fade" id="modal-draft-list" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h4 class="modal-title">Parked Draft Invoices &amp; Table Orders</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -8px;"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="padding: 16px 20px;">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="draftListTable" style="margin-bottom: 0;">
                        <thead>
                            <tr style="background: #f8fafc;">
                                <th>Invoice</th>
                                <th>Table &amp; Type</th>
                                <th>Items</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Time</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="draftListBody">
                            <tr>
                                <td colspan="7" class="text-center" style="padding: 24px; color: #94a3b8;">
                                    <i class="fa fa-spinner fa-spin fa-2x"></i><br>Loading draft orders...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #f1f5f9; border-radius: 0 0 14px 14px;">
                <a href="{{ route('transaksi.baru') }}" class="btn btn-sm btn-primary btn-flat" style="border-radius: 6px;"><i class="fa fa-plus"></i> New Clean Order</a>
                <button type="button" class="btn btn-sm btn-default btn-flat" data-dismiss="modal" style="border-radius: 6px;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ITEM NOTES MODAL -->
<div class="modal fade" id="modal-notes" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> Kitchen Cooking Notes</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="noteDetailId">
                <div class="form-group">
                    <label style="font-size: 12px; color: #475569;">Special Instructions:</label>
                    <textarea id="itemNoteInput" class="form-control" rows="3" placeholder="e.g. No onions, Extra spicy, Dressing on the side..."></textarea>
                </div>
                <button type="button" class="btn btn-primary btn-block btn-flat" onclick="saveItemNote()" style="border-radius: 8px;">Save Note</button>
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
    let selectedPaymentMethod = '{{ strtolower($penjualan->metode_pembayaran ?? "cash") }}';

    $(function () {
        loadCart();

        // Initialize edit values
        setPaymentMethod(selectedPaymentMethod);
        $('#modalDiningSelect').val('{{ $penjualan->tipe_order ?? "Dine-In" }}');
        handleModalDiningChange('{{ $penjualan->tipe_order ?? "Dine-In" }}');
        $('#modalTableSelect').val('{{ $penjualan->nomor_meja ?? "Table 1" }}');
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
        selectedPaymentMethod = method;
        $('#posPaymentMethodSelect').val(method);
        $('#form_metode_pembayaran').val(method);
    }

    function openOrderInfoModal() {
        $('#modal-order-info').modal('show');
    }

    function handleModalDiningChange(val) {
        if (val === 'Dine-In') {
            $('#modalTableFormGroup').show();
            $('#form_tipe_order').val('Dine-In');
            $('#form_nomor_meja').val($('#modalTableSelect').val() || 'Table 1');
        } else {
            $('#modalTableFormGroup').hide();
            $('#form_tipe_order').val(val);
            $('#form_nomor_meja').val(val);
        }
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
            $('#lblGrandTotal').text(CURRENCY_SYMBOL + ' 0.00');
            $('#form_total').val(0);
            $('#form_total_item').val(0);
            $('#form_bayar').val(0);
            $('#form_diterima').val(0);
            return;
        }

        let html = '';
        data.items.forEach(item => {
            let noteText = localStorage.getItem('item_note_' + item.id_detail) || '';
            let noteHtml = noteText ? `<div style="font-size: 10px; color: #f97316; font-style: italic; margin-top: 2px;"><i class="fa fa-tag"></i> ${noteText}</div>` : '';

            html += `
                <div class="pos-food-card">
                    <div class="pos-food-info">
                        <div class="pos-food-name" title="${item.nama_produk}">${item.nama_produk}</div>
                        <div class="pos-food-price">${CURRENCY_SYMBOL} ${item.harga_jual}</div>
                        ${noteHtml}
                        <div>
                            <button type="button" class="btn-food-note" onclick="addCookingNote(${item.id_detail})">
                                <i class="fa fa-pencil"></i> ${noteText ? 'Edit Note' : 'Add Note'}
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
        let finalGrandTotal = Math.max(0, data.total - totalDiscount);

        $('#lblSubtotal').text(CURRENCY_SYMBOL + ' ' + (data.total_rp || data.total.toLocaleString()));
        $('#lblDiscountAndTax').text(totalDiscount > 0 ? ('-' + CURRENCY_SYMBOL + ' ' + totalDiscount.toLocaleString()) : (CURRENCY_SYMBOL + ' 0.00'));
        $('#lblGrandTotal').text(CURRENCY_SYMBOL + ' ' + finalGrandTotal.toLocaleString());

        // Form Fields
        $('#form_total').val(data.total);
        $('#form_total_item').val(data.total_item);
        $('#form_bayar').val(finalGrandTotal);
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

    // Add Kitchen Cooking Note Modal
    function addCookingNote(detailId) {
        $('#noteDetailId').val(detailId);
        $('#itemNoteInput').val(localStorage.getItem('item_note_' + detailId) || '');
        $('#modal-notes').modal('show');
    }

    function saveItemNote() {
        let detailId = $('#noteDetailId').val();
        let note = $('#itemNoteInput').val().trim();
        localStorage.setItem('item_note_' + detailId, note);
        $('#modal-notes').modal('hide');
        loadCart();
        showSuccessToast('Kitchen note saved');
    }

    // PLACE ORDER SUBMIT (Big orange button)
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
            'diterima': $('#form_diterima').val() || 0,
            'status_pembayaran': $('#form_status_pembayaran').val() || 'unpaid',
            'metode_pembayaran': selectedPaymentMethod,
            'id_member': $('#form_id_member').val(),
            'nomor_meja': $('#modalTableSelect').val() || 'Table 1',
            'tipe_order': $('#modalDiningSelect').val() || 'Dine-In'
        };

        $.post('{{ route('transaksi.simpan') }}', data)
            .done(response => {
                let isEdit = (response.is_editing || IS_EDIT_MODE);
                let swalTitle = isEdit ? 'Invoice Updated Successfully!' : 'Order Placed Successfully!';
                let swalHtml = isEdit ?
                    `<strong>${response.invoice}</strong> has been updated successfully.` :
                    `<strong>${response.invoice}</strong> saved as <span class="label label-danger" style="font-size:12px;">UNPAID</span>.<br>Customer will pay before or after meal.`;
                let nextBtnText = isEdit ? 'Go to Invoices' : 'Next Order';
                let nextUrl = isEdit ? "{{ route('penjualan.index') }}" : "{{ route('transaksi.baru') }}";

                Swal.fire({
                    title: swalTitle,
                    html: swalHtml,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#ff521d',
                    cancelButtonColor: '#0f172a',
                    confirmButtonText: 'Print Receipt (New Tab)',
                    cancelButtonText: nextBtnText
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open(response.print_url, '_blank');
                        setTimeout(() => {
                            window.location.href = nextUrl;
                        }, 800);
                    } else {
                        window.location.href = nextUrl;
                    }
                });
            })
            .fail(errors => {
                showErrorToast('Failed to save order');
            });
    }

    // SAVE DRAFT INVOICE
    function saveAsDraft() {
        if (!currentCartData.items || currentCartData.items.length === 0) {
            showWarningToast('Cart is empty, nothing to save as draft.');
            return;
        }

        let table = $('#modalTableSelect').val() || 'Table 1';
        let dining = $('#modalDiningSelect').val() || 'Dine-In';

        $.post('{{ route('transaksi.draft') }}', {
            '_token': $('[name=csrf-token]').attr('content'),
            'id_penjualan': '{{ $id_penjualan }}',
            'nomor_meja': table,
            'tipe_order': dining,
            'diskon': $('#form_diskon').val(),
            'id_member': $('#form_id_member').val()
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
            showErrorToast(msg);
        });
    }

    // SHOW DRAFT ORDERS LIST MODAL
    function showDraftListModal() {
        $('#modal-draft-list').modal('show');
        loadDraftList();
    }

    function loadDraftList() {
        $('#draftListBody').html(`
            <tr>
                <td colspan="7" class="text-center" style="padding: 24px; color: #94a3b8;">
                    <i class="fa fa-spinner fa-spin fa-2x"></i><br>Loading draft orders...
                </td>
            </tr>
        `);

        $.get('{{ route('transaksi.draft_list') }}')
            .done(drafts => {
                let tbody = $('#draftListBody');
                tbody.empty();

                if (!drafts || drafts.length === 0) {
                    tbody.html(`
                        <tr>
                            <td colspan="7" class="text-center" style="padding: 30px; color: #94a3b8;">
                                <i class="fa fa-check-circle-o" style="font-size: 32px; color: #10b981; margin-bottom: 8px;"></i>
                                <h4 style="font-weight: 700; color: #475569; margin: 0 0 4px 0;">No Active Draft Orders</h4>
                                <p style="font-size: 12px; margin: 0;">All table and takeaway orders have been settled.</p>
                            </td>
                        </tr>
                    `);
                    return;
                }

                drafts.forEach(d => {
                    let diningBadgeClass = (d.tipe_order === 'Dine-In') ? 'label-primary' : (d.tipe_order === 'Delivery' ? 'label-info' : 'label-warning');

                    tbody.append(`
                        <tr>
                            <td><strong style="color: #ea580c;">${d.invoice}</strong></td>
                            <td>
                                <strong style="color: #0f172a;">${d.nomor_meja}</strong><br>
                                <span class="label ${diningBadgeClass}" style="font-size: 10px;">${d.tipe_order}</span>
                            </td>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: #334155; font-weight: 700;">${d.total_item} items</span>
                                <div style="font-size: 11px; color: #64748b; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${d.items_summary}</div>
                            </td>
                            <td><span class="text-muted"><i class="fa fa-user"></i> ${d.member}</span></td>
                            <td><strong style="color: #0f172a; font-size: 13.5px;">${d.bayar}</strong></td>
                            <td><span style="font-size: 11.5px; color: #64748b;">${d.created_at}</span></td>
                            <td style="text-align: right; white-space: nowrap;">
                                <a href="${d.resume_url}" class="btn btn-xs btn-success btn-flat" style="font-weight: 700; border-radius: 4px; padding: 4px 8px;">
                                    Resume
                                </a>
                                <button type="button" onclick="deleteDraftItem('${d.delete_url}')" class="btn btn-xs btn-danger btn-flat" style="border-radius: 4px; padding: 4px 8px;" title="Delete draft">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
            })
            .fail(() => {
                $('#draftListBody').html(`
                    <tr>
                        <td colspan="7" class="text-center text-danger" style="padding: 20px;">
                            <i class="fa fa-exclamation-triangle"></i> Failed to load draft list.
                        </td>
                    </tr>
                `);
            });
    }

    function deleteDraftItem(url) {
        showConfirmDialog('Delete Draft?', 'Are you sure you want to delete this drafted order?', 'Yes, delete', function() {
            $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done(res => {
                showSuccessToast('Draft deleted');
                loadDraftList();
            })
            .fail(() => {
                showErrorToast('Failed to delete draft');
            });
        });
    }

    function showQrMenuModal() {
        Swal.fire({
            title: 'QR Menu Live Orders',
            text: 'All contactless table orders from customer QR scans stream here live.',
            icon: 'info',
            confirmButtonColor: '#f97316'
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