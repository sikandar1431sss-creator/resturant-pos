@extends('layouts.master')

@section('title')
    Invoices
@endsection

@push('css')
<style>
    /* Hide AdminLTE default duplicate content-header & breadcrumbs */
    .content-header {
        display: none !important;
    }

    .invoices-page-wrapper {
        margin-top: 5px;
    }

    /* Top Page Header */
    .invoices-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 14px;
    }

    .invoices-heading {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
        font-size: 22px;
        letter-spacing: -0.02em;
    }

    /* Filter Box */
    .filter-panel-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 16px;
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

    /* Badges */
    .badge-status-paid {
        background: #10b981;
        color: #fff;
        font-weight: 700;
        font-size: 11px;
        padding: 4px 9px;
        border-radius: 4px;
    }
    .badge-status-unpaid {
        background: #ef4444;
        color: #fff;
        font-weight: 700;
        font-size: 11px;
        padding: 4px 9px;
        border-radius: 4px;
    }

    /* Action Buttons in Table */
    .table-actions-group {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        flex-wrap: nowrap !important;
        vertical-align: middle !important;
    }

    .btn-table-action {
        width: 34px !important;
        height: 34px !important;
        min-width: 34px !important;
        max-width: 34px !important;
        border-radius: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 14px !important;
        line-height: 1 !important;
        border: 1px solid transparent !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer !important;
        text-decoration: none !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
        padding: 0 !important;
        margin: 0 !important;
        box-sizing: border-box !important;
        vertical-align: middle !important;
    }

    .btn-table-action i {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        font-size: 14px !important;
        line-height: 1 !important;
        color: inherit !important;
        display: inline-block !important;
    }

    .btn-table-action:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.18) !important;
    }

    .btn-table-action:active {
        transform: scale(0.95) !important;
    }

    .btn-table-action.btn-pay {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #ffffff !important;
    }
    .btn-table-action.btn-pay:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
        color: #ffffff !important;
    }

    .btn-table-action.btn-print {
        background-color: #f1f5f9 !important;
        color: #334155 !important;
        border-color: #cbd5e1 !important;
    }
    .btn-table-action.btn-print:hover {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
        border-color: #94a3b8 !important;
    }

    .btn-table-action.btn-edit {
        background-color: #2563eb !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
    }
    .btn-table-action.btn-edit:hover {
        background-color: #1d4ed8 !important;
        border-color: #1d4ed8 !important;
        color: #ffffff !important;
    }

    .btn-table-action.btn-view {
        background-color: #0284c7 !important;
        border-color: #0284c7 !important;
        color: #ffffff !important;
    }
    .btn-table-action.btn-view:hover {
        background-color: #0369a1 !important;
        border-color: #0369a1 !important;
        color: #ffffff !important;
    }

    .btn-table-action.btn-delete {
        background-color: #ef4444 !important;
        border-color: #ef4444 !important;
        color: #ffffff !important;
    }
    .btn-table-action.btn-delete:hover {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="invoices-page-wrapper">
    <!-- Top Page Header -->
    <div class="invoices-top-bar">
        <div>
            <h2 class="invoices-heading">Invoices</h2>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <button type="button" class="btn btn-flat" onclick="showDraftListModal()" style="background: #ffffff; color: #334155; font-weight: 700; border-radius: 8px; padding: 8px 16px; font-size: 13px; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                <i class="fa fa-pause-circle" style="font-size: 14px; color: #ea580c;"></i> Parked Drafts <span class="badge" id="invoicesDraftCountBadge" style="display:none; background:#ea580c; color:#fff; font-size:10.5px; margin-left:4px; font-weight:800; border-radius:10px; padding:2px 7px;">0</span>
            </button>
            @if(auth()->user()->can('pos.create_order') || auth()->user()->can('pos.access') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <a href="{{ route('transaksi.baru') }}" class="btn btn-flat" style="background: #ea580c; color: #fff; font-weight: 800; border-radius: 8px; padding: 9px 20px; font-size: 13.5px; box-shadow: none; display: inline-flex; align-items: center; gap: 8px; border: none; transition: all 0.2s ease;">
                <i class="fa fa-plus-circle"></i> New Invoice
            </a>
            @endif
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filter-panel-card">
        <div class="filter-panel-header">
            <h4 class="filter-panel-title">Filters</h4>
            <div class="quick-preset-group">
                <span style="font-size: 12px; font-weight: 700; color: #64748b; margin-right: 4px;">Quick Dates:</span>
                <button type="button" class="btn-quick-preset active" data-preset="all">All Time</button>
                <button type="button" class="btn-quick-preset" data-preset="today">Today</button>
                <button type="button" class="btn-quick-preset" data-preset="yesterday">Yesterday</button>
                <button type="button" class="btn-quick-preset" data-preset="week">This Week</button>
                <button type="button" class="btn-quick-preset" data-preset="month">This Month</button>
                <button type="button" id="btn-reset-filters" class="btn-quick-preset" style="color: #ef4444; border-color: #fca5a5; background: #ffffff;">Reset</button>
            </div>
        </div>

        <div class="row" style="margin-top: 4px;">
            <!-- Start Date -->
            <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                <label class="filter-form-label">From Date</label>
                <input type="date" id="filter_start_date" class="form-control filter-form-control">
            </div>

            <!-- End Date -->
            <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                <label class="filter-form-label">To Date</label>
                <input type="date" id="filter_end_date" class="form-control filter-form-control">
            </div>

            <!-- Payment Status -->
            <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                <label class="filter-form-label">Payment Status</label>
                <select id="filter_status" class="form-control filter-form-control">
                    <option value="all" selected>All Status</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                </select>
            </div>

            <!-- Payment Method -->
            <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                <label class="filter-form-label">Payment Method</label>
                <select id="filter_payment_method" class="form-control filter-form-control">
                    <option value="all" selected>All Methods</option>
                    <option value="cash">Cash</option>
                    <option value="card">Debit Card</option>
                    <option value="online">E-Wallet / Online</option>
                </select>
            </div>

            <!-- Order / Dining Type -->
            <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                <label class="filter-form-label">Order Type</label>
                <select id="filter_order_type" class="form-control filter-form-control">
                    <option value="all" selected>All Types</option>
                    <option value="Dine-In">Dine-In</option>
                    <option value="Takeaway">Takeaway</option>
                    <option value="Delivery">Delivery</option>
                </select>
            </div>

            <!-- Staff / Cashier -->
            <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                <label class="filter-form-label">Cashier / Staff</label>
                <select id="filter_cashier" class="form-control filter-form-control">
                    <option value="all" selected>All Staff</option>
                    @if(isset($users) && $users->isNotEmpty())
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04); overflow: hidden; background: #ffffff;">
                <div class="box-body table-responsive" style="padding: 16px;">
                    <table class="table table-striped table-penjualan table-hover" style="width: 100%;">
                        <thead>
                            <th width="4%">#</th>
                            <th>Invoice #</th>
                            <th>Date &amp; Time</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total Bill</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th width="18%"><i class="fa fa-cog"></i> Action</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PAY NOW MODAL -->
<div class="modal fade" id="modal-pay-invoice" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #1e3a68; color: #fff; padding: 14px 18px;">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
                <h4 class="modal-title" style="font-weight: 800; font-size: 14.5px; color: #fff;">Settle Customer Payment</h4>
            </div>
            <form id="payInvoiceForm" onsubmit="submitPayInvoice(event)">
                @csrf
                <input type="hidden" id="payIdPenjualan">

                <div class="modal-body" style="padding: 18px;">
                    <!-- Invoice Summary Card -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px;">
                        <div style="display: flex; justify-content: space-between; font-size: 12.5px; margin-bottom: 4px;">
                            <span class="text-muted">Invoice:</span>
                            <strong id="payInvNumber" style="color: #ea580c; font-size: 13px;">#INV-00000</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 12.5px; margin-bottom: 6px;">
                            <span class="text-muted">Customer:</span>
                            <strong id="payCustomerName" style="color: #0f172a;">Walk-in Customer</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px; padding-top: 6px; border-top: 1px dashed #cbd5e1;">
                            <strong style="color: #0f172a;">Total Payable:</strong>
                            <strong id="payTotalAmount" style="color: #10b981; font-size: 17px; font-weight: 900;">RS 0</strong>
                        </div>
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="form-group" style="margin-bottom: 4px;">
                        <label style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Select Payment Method:</label>
                        <select id="paySelectedMethod" class="form-control" style="border-radius: 8px; font-weight: 700; height: 42px;">
                            <option value="cash" selected>Cash</option>
                            <option value="card">Debit Card</option>
                            <option value="online">E-Wallet / Online</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 18px;">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" style="border-radius: 6px; font-weight: 600;">Cancel</button>
                    <button type="submit" class="btn btn-success btn-flat" style="border-radius: 6px; font-weight: 700; padding: 7px 16px;">
                        Mark as Paid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QUICK EDIT INVOICE MODAL -->
<div class="modal fade" id="modal-quick-edit-invoice" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #1e3a68; color: #fff; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center;">
                <h4 class="modal-title" style="font-weight: 800; font-size: 15px; color: #fff;">
                    Quick Edit Invoice <span id="quickEditInvNumber" style="color: #ffaa5a; font-weight: 900;">#INV-00000</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9; margin: 0;">&times;</button>
            </div>
            <form id="quickEditInvoiceForm" onsubmit="submitQuickEditInvoice(event)">
                @csrf
                <input type="hidden" id="quickEditIdPenjualan">

                <div class="modal-body" style="padding: 20px;">
                    <!-- Invoice Summary Top Box -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <span class="text-muted" style="font-size: 12px;">Invoice Total:</span><br>
                            <strong id="quickEditTotalAmount" style="color: #0f172a; font-size: 16px; font-weight: 900;">RS 0</strong>
                        </div>
                        <div>
                            <span class="text-muted" style="font-size: 12px;">Items Count:</span><br>
                            <span id="quickEditTotalItem" class="badge" style="background: #1e3a68; color: #fff; font-size: 12px; padding: 4px 8px;">0 items</span>
                        </div>
                        <div>
                            <span class="text-muted" style="font-size: 12px;">Created:</span><br>
                            <span id="quickEditCreatedAt" style="font-size: 12px; color: #64748b; font-weight: 600;">--</span>
                        </div>
                        <div>
                            <a id="quickEditPosLink" href="#" class="btn btn-sm btn-warning btn-flat" style="font-weight: 800; border-radius: 6px; color: #000; box-shadow: 0 2px 6px rgba(245,158,11,0.3);">
                                <i class="fa fa-shopping-cart"></i> Edit Dishes / Items in POS
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Dining Type -->
                        <div class="col-md-6 form-group">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155;">Dining / Order Type:</label>
                            <select id="quickEditDiningType" class="form-control" style="border-radius: 8px; font-weight: 700; height: 40px;" onchange="handleQuickDiningChange(this.value)">
                                <option value="Dine-In">Dine-In</option>
                                <option value="Takeaway">Takeaway</option>
                                <option value="Delivery">Delivery</option>
                            </select>
                        </div>

                        <!-- Table Selection -->
                        <div class="col-md-6 form-group" id="quickEditTableGroup">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155;">Table Selection:</label>
                            <select id="quickEditTableSelect" class="form-control" style="border-radius: 8px; font-weight: 700; height: 40px;">
                                <option value="Table 1">Table 1</option>
                                <option value="Table 2">Table 2</option>
                                <option value="Table 3">Table 3</option>
                                <option value="Table 4">Table 4</option>
                                <option value="Table 5">Table 5</option>
                                <option value="VIP Table">VIP Table</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Customer / Member -->
                        <div class="col-md-6 form-group">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155;">Customer / Member:</label>
                            <select id="quickEditMemberSelect" class="form-control" style="border-radius: 8px; font-weight: 600; height: 40px;">
                                <option value="">Walk-in Customer</option>
                            </select>
                        </div>

                        <!-- Discount (%) -->
                        <div class="col-md-6 form-group">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155;">Discount (%):</label>
                            <div class="input-group">
                                <input type="number" id="quickEditDiscount" class="form-control" min="0" max="100" step="0.5" style="border-radius: 8px 0 0 8px; font-weight: 700; height: 40px;" placeholder="0">
                                <span class="input-group-addon" style="border-radius: 0 8px 8px 0; font-weight: 700;">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Payment Method -->
                        <div class="col-md-6 form-group">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155;">Payment Method:</label>
                            <select id="quickEditPaymentMethod" class="form-control" style="border-radius: 8px; font-weight: 700; height: 40px;">
                                <option value="cash">Cash</option>
                                <option value="card">Debit Card</option>
                                <option value="online">E-Wallet / Online</option>
                            </select>
                        </div>

                        <!-- Payment Status -->
                        <div class="col-md-6 form-group">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155;">Payment Status:</label>
                            <select id="quickEditPaymentStatus" class="form-control" style="border-radius: 8px; font-weight: 700; height: 40px;">
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 12.5px; font-weight: 700; color: #334155;">Order Remarks / Notes:</label>
                        <textarea id="quickEditCatatan" class="form-control" rows="2" placeholder="e.g. Special cooking notes, instructions..." style="border-radius: 8px; font-size: 13px;"></textarea>
                    </div>
                </div>

                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center;">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" style="border-radius: 6px; font-weight: 600;">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-flat" style="border-radius: 6px; font-weight: 700; padding: 8px 20px;">
                        <i class="fa fa-check-circle"></i> Save Changes
                    </button>
                </div>
            </form>
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

@includeIf('penjualan.detail')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        loadDraftCountBadge();

        // Initialize DataTable with Filter Data
        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('penjualan.data') }}',
                data: function (d) {
                    d.start_date = $('#filter_start_date').val();
                    d.end_date = $('#filter_end_date').val();
                    d.status_pembayaran = $('#filter_status').val();
                    d.metode_pembayaran = $('#filter_payment_method').val();
                    d.tipe_order = $('#filter_order_type').val();
                    d.id_user = $('#filter_cashier').val();
                }
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false,
                    render: function (data, type, row, meta) {
                        if (meta && meta.settings) {
                            let total = meta.settings.fnRecordsDisplay();
                            let start = meta.settings._iDisplayStart || 0;
                            let num = total - (start + meta.row);
                            if (num > 0) {
                                return '<strong style="color: #64748b;">' + num + '</strong>';
                            }
                        }
                        return '<strong style="color: #64748b;">' + (data || 1) + '</strong>';
                    }
                },
                {data: 'invoice'},
                {data: 'tanggal'},
                {data: 'kode_member'},
                {data: 'total_item'},
                {data: 'bayar'},
                {data: 'metode_pembayaran'},
                {data: 'status_pembayaran'},
                {data: 'kasir'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        // Detail table
        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                {data: 'subtotal'},
            ]
        });

        // Quick Preset Date Click Handler (Real-time)
        $('.btn-quick-preset').on('click', function () {
            if ($(this).attr('id') === 'btn-reset-filters') return;

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
                // all time
                start = '';
                end = '';
            }

            $('#filter_start_date').val(start);
            $('#filter_end_date').val(end);
            table.ajax.reload();
        });

        // Real-time filtering listeners on any input change
        $('#filter_status, #filter_payment_method, #filter_order_type, #filter_cashier').on('change', function () {
            table.ajax.reload();
        });

        $('#filter_start_date, #filter_end_date').on('change input', function () {
            $('.btn-quick-preset').removeClass('active');
            table.ajax.reload();
        });

        // Reset Filters Button (Real-time)
        $('#btn-reset-filters').on('click', function () {
            $('#filter_start_date').val('');
            $('#filter_end_date').val('');
            $('#filter_status').val('all');
            $('#filter_payment_method').val('all');
            $('#filter_order_type').val('all');
            $('#filter_cashier').val('all');

            $('.btn-quick-preset').removeClass('active');
            $('[data-preset="all"]').addClass('active');

            table.ajax.reload();
        });
    });

    function markAsPaid(id, invoice, amount, customer) {
        $('#payIdPenjualan').val(id);
        $('#payInvNumber').text(invoice);
        $('#payCustomerName').text(customer || 'Walk-in Customer');
        $('#payTotalAmount').text('{{ get_currency_symbol() }} ' + parseFloat(amount).toLocaleString());
        $('#paySelectedMethod').val('cash');
        $('#modal-pay-invoice').modal('show');
    }

    function submitPayInvoice(e) {
        e.preventDefault();
        let id = $('#payIdPenjualan').val();
        let method = $('#paySelectedMethod').val();
        let token = $('meta[name="csrf-token"]').attr('content') || $('[name="_token"]').val();

        $.ajax({
            url: `{{ url('/penjualan') }}/${id}/settle-payment`,
            type: 'POST',
            data: {
                '_token': token,
                'metode_pembayaran': method
            },
            success: function(response) {
                $('#modal-pay-invoice').modal('hide');
                table.ajax.reload();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Payment Received!',
                        text: response.message,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#ff521d',
                        cancelButtonColor: '#0f172a',
                        confirmButtonText: 'Print Receipt (New Tab)',
                        cancelButtonText: 'Done'
                    }).then((res) => {
                        if (res.isConfirmed && response.print_url) {
                            window.open(response.print_url, '_blank');
                        }
                    });
                } else {
                    showSuccessToast(response.message);
                }
            },
            error: function(errors) {
                let msg = errors.responseJSON ? (errors.responseJSON.message || errors.responseJSON.error) : 'Failed to mark invoice as paid';
                showErrorToast(msg);
            }
        });
    }

    function quickEditInvoice(id) {
        $.get(`{{ url('/penjualan') }}/${id}/info`)
        .done(data => {
            $('#quickEditIdPenjualan').val(data.id_penjualan);
            $('#quickEditInvNumber').text(data.invoice);
            $('#quickEditTotalAmount').text(data.bayar_rp);
            $('#quickEditTotalItem').text(data.total_item + ' items');
            $('#quickEditCreatedAt').text(data.created_at);
            $('#quickEditPosLink').attr('href', data.edit_pos_url);

            $('#quickEditDiningType').val(data.tipe_order);
            handleQuickDiningChange(data.tipe_order);
            $('#quickEditTableSelect').val(data.nomor_meja);

            // Populate Members dropdown
            let memSelect = $('#quickEditMemberSelect');
            memSelect.empty();
            memSelect.append('<option value="">Walk-in Customer</option>');
            if (data.members && data.members.length > 0) {
                data.members.forEach(m => {
                    let selected = (m.id_member == data.id_member) ? 'selected' : '';
                    memSelect.append(`<option value="${m.id_member}" ${selected}>${m.nama} (${m.kode_member || m.telepon || 'Member'})</option>`);
                });
            }

            $('#quickEditDiscount').val(data.diskon);
            $('#quickEditPaymentMethod').val(data.metode_pembayaran);
            $('#quickEditPaymentStatus').val(data.status_pembayaran);
            $('#quickEditCatatan').val(data.catatan || '');

            $('#modal-quick-edit-invoice').modal('show');
        })
        .fail(() => {
            showErrorToast('Failed to load invoice information');
        });
    }

    function handleQuickDiningChange(val) {
        if (val === 'Dine-In') {
            $('#quickEditTableGroup').show();
        } else {
            $('#quickEditTableGroup').hide();
        }
    }

    function submitQuickEditInvoice(e) {
        e.preventDefault();
        let id = $('#quickEditIdPenjualan').val();
        let dining = $('#quickEditDiningType').val();
        let tableVal = (dining === 'Dine-In') ? $('#quickEditTableSelect').val() : dining;

        let payload = {
            '_token': $('[name=csrf-token]').attr('content'),
            'tipe_order': dining,
            'nomor_meja': tableVal,
            'id_member': $('#quickEditMemberSelect').val(),
            'diskon': $('#quickEditDiscount').val(),
            'metode_pembayaran': $('#quickEditPaymentMethod').val(),
            'status_pembayaran': $('#quickEditPaymentStatus').val(),
            'catatan': $('#quickEditCatatan').val()
        };

        $.post(`{{ url('/penjualan') }}/${id}/update-info`, payload)
        .done(response => {
            $('#modal-quick-edit-invoice').modal('hide');
            table.ajax.reload();
            showSuccessToast(response.message);
        })
        .fail(errors => {
            let msg = errors.responseJSON ? (errors.responseJSON.message || errors.responseJSON.error) : 'Failed to update invoice';
            showErrorToast(msg);
        });
    }

    function showDetail(url, id) {
        $('#modal-detail').modal('show');
        if (id) {
            $('#detailInvoiceNum').text('#INV-' + String(id).padStart(5, '0'));
            $('#btnDetailEditPos').attr('href', `{{ url('/penjualan') }}/${id}/edit`);
        }
        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        showConfirmDialog('Delete Order Record?', 'Are you sure you want to delete this sales transaction?', 'Yes, delete', function() {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    showSuccessToast('Transaction deleted successfully');
                    table.ajax.reload();
                    loadDraftCountBadge();
                })
                .fail((errors) => {
                    showErrorToast('Unable to delete transaction record');
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
                let count = drafts.length;
                let topBadge = $('#invoicesDraftCountBadge');
                if (topBadge.length) {
                    if (count > 0) {
                        topBadge.text(count).show();
                    } else {
                        topBadge.hide();
                    }
                }
            })
            .fail(() => {
                // silently fail
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
                $('#draftModalCount').text(drafts.length + ' Parked');
                let topBadge = $('#invoicesDraftCountBadge');
                if (topBadge.length) {
                    if (drafts.length > 0) {
                        topBadge.text(drafts.length).show();
                    } else {
                        topBadge.hide();
                    }
                }
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

            tbody.append(`
                <tr>
                    <td>
                        <span class="draft-inv-code">${d.invoice}</span>
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
                loadDraftCountBadge();
                if (typeof table !== 'undefined') {
                    table.ajax.reload();
                }
            })
            .fail(() => {
                showErrorToast('Failed to delete draft');
            });
        });
    }
</script>
@endpush