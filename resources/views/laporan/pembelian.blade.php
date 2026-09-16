@extends('layouts.master')

@section('title')
    Purchase Report
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Purchase Report</li>
@endsection

@push('css')
<style>
    .report-card-kpi {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px 18px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: transform 0.15s ease;
    }
    .report-card-kpi:hover {
        transform: translateY(-2px);
    }
    .kpi-title {
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 6px;
    }
    .kpi-val {
        font-size: 22px;
        font-weight: 900;
        margin: 0;
        line-height: 1.2;
    }
    .kpi-sub {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 4px;
        font-weight: 500;
    }
    .kpi-border-green { border-top: 3.5px solid #10b981 !important; }
    .kpi-border-blue { border-top: 3.5px solid #3b82f6 !important; }
    .kpi-border-red { border-top: 3.5px solid #ef4444 !important; }
    .kpi-border-dark { border-top: 3.5px solid #1e293b !important; }
    .kpi-border-purple { border-top: 3.5px solid #8b5cf6 !important; }
    .kpi-border-amber { border-top: 3.5px solid #f59e0b !important; }

    .filter-box-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 18px 20px;
        margin-bottom: 22px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }
    .period-quick-btn {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-size: 11.5px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .period-quick-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .nav-tabs-custom > .nav-tabs > li.active > a {
        border-top-color: #0284c7;
        font-weight: 800;
        color: #0f172a;
    }
    .nav-tabs-custom > .nav-tabs > li > a {
        font-weight: 600;
        color: #64748b;
    }
    @media print {
        .filter-box-card, .btn-print-hide, .main-sidebar, .main-header, .content-header {
            display: none !important;
        }
        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }
        .report-card-kpi {
            border: 1px solid #ccc !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        
        <!-- Filter Card -->
        <div class="filter-box-card">
            <form action="{{ route('laporan.pembelian') }}" method="GET" id="purchaseFilterForm">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                    <div>
                        <h4 style="font-weight: 800; font-size: 16px; color: #0f172a; margin: 0;">
                            <i class="fa fa-truck" style="color: #0284c7;"></i> Purchase &amp; Stock-In Report
                        </h4>
                        <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                            Period: <strong>{{ date('d M Y', strtotime($tanggalAwal)) }}</strong> &mdash; <strong>{{ date('d M Y', strtotime($tanggalAkhir)) }}</strong>
                        </div>
                    </div>

                    <div style="display: flex; gap: 6px; flex-wrap: wrap;" class="btn-print-hide">
                        <button type="button" class="period-quick-btn" onclick="setPeriod('today')">Today</button>
                        <button type="button" class="period-quick-btn" onclick="setPeriod('yesterday')">Yesterday</button>
                        <button type="button" class="period-quick-btn" onclick="setPeriod('this_week')">This Week</button>
                        <button type="button" class="period-quick-btn" onclick="setPeriod('this_month')">This Month</button>
                        <button type="button" class="period-quick-btn" onclick="setPeriod('last_month')">Last Month</button>
                        <button type="button" class="period-quick-btn" onclick="setPeriod('this_year')">This Year</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 11.5px; font-weight: 700; color: #475569;">From Date</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control input-sm" value="{{ $tanggalAwal }}" style="border-radius: 6px; font-weight: 600;">
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 11.5px; font-weight: 700; color: #475569;">To Date</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control input-sm" value="{{ $tanggalAkhir }}" style="border-radius: 6px; font-weight: 600;">
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6">
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 11.5px; font-weight: 700; color: #475569;">Supplier</label>
                            <select name="id_supplier" class="form-control input-sm" style="border-radius: 6px; font-weight: 600;">
                                <option value="all">All Suppliers</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id_supplier }}" {{ $idSupplier == $s->id_supplier ? 'selected' : '' }}>{{ $s->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6">
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 11.5px; font-weight: 700; color: #475569;">Payment Status</label>
                            <select name="payment_status" class="form-control input-sm" style="border-radius: 6px; font-weight: 600;">
                                <option value="all" {{ $paymentStatus === 'all' ? 'selected' : '' }}>All Statuses</option>
                                <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Fully Settled</option>
                                <option value="due" {{ $paymentStatus === 'due' ? 'selected' : '' }}>Pending Supplier Due</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6">
                        <div class="form-group" style="margin-bottom: 8px; padding-top: 23px; display: flex; gap: 6px;">
                            <button type="submit" class="btn btn-primary btn-sm btn-flat" style="border-radius: 6px; font-weight: 700; flex: 1;">
                                <i class="fa fa-filter"></i> Apply
                            </button>
                            <button type="button" onclick="window.print()" class="btn btn-default btn-sm btn-flat btn-print-hide" style="border-radius: 6px; font-weight: 700;" title="Print Report">
                                <i class="fa fa-print"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- 5 KPI Stat Cards -->
        <div class="row">
            <!-- Total Purchases -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-blue">
                    <div class="kpi-title" style="color: #0284c7;">Total Purchases Billed</div>
                    <div class="kpi-val" style="color: #0f172a;">{{ format_currency($totalPurchases) }}</div>
                    <div class="kpi-sub">Net stock purchase cost</div>
                </div>
            </div>

            <!-- Paid to Suppliers -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-green">
                    <div class="kpi-title" style="color: #15803d;">Paid to Suppliers</div>
                    <div class="kpi-val" style="color: #10b981;">{{ format_currency($paidPurchases) }}</div>
                    <div class="kpi-sub">Cash outflow to vendors</div>
                </div>
            </div>

            <!-- Supplier Dues -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-amber">
                    <div class="kpi-title" style="color: #b45309;">Pending Supplier Payables</div>
                    <div class="kpi-val" style="color: #d97706;">{{ format_currency($duePurchases) }}</div>
                    <div class="kpi-sub">Outstanding vendor balances</div>
                </div>
            </div>

            <!-- Total POs & Quantity -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-dark">
                    <div class="kpi-title" style="color: #475569;">Purchase Orders &amp; Units</div>
                    <div class="kpi-val" style="color: #0f172a;">{{ number_format($totalOrders) }} <span style="font-size: 14px; font-weight: 600; color: #64748b;">POs</span></div>
                    <div class="kpi-sub">Total <strong>{{ number_format($totalItemsIn) }}</strong> stock units in</div>
                </div>
            </div>
        </div>

        <!-- Breakdown Nav Tabs -->
        <div class="nav-tabs-custom" style="border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_purchases" data-toggle="tab">
                        <i class="fa fa-truck text-blue"></i> Purchase Orders ({{ $totalOrders }})
                    </a>
                </li>
                <li>
                    <a href="#tab_items" data-toggle="tab">
                        <i class="fa fa-cubes text-green"></i> Raw Material / Item Purchases
                    </a>
                </li>
                <li>
                    <a href="#tab_suppliers" data-toggle="tab">
                        <i class="fa fa-address-book text-orange"></i> Supplier Payables Summary
                    </a>
                </li>
            </ul>

            <div class="tab-content" style="padding: 18px;">
                <!-- TAB 1: Purchases List -->
                <div class="tab-pane active" id="tab_purchases">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover datatable-purchases" style="width: 100%;">
                            <thead>
                                <th width="4%">#</th>
                                <th width="10%">PO #</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Items</th>
                                <th>Gross Total</th>
                                <th>Discount</th>
                                <th>Net Payable</th>
                                <th>Paid</th>
                                <th>Due Balance</th>
                                <th>Status</th>
                                <th width="8%"><i class="fa fa-cog"></i></th>
                            </thead>
                            <tbody>
                                @forelse($purchases as $idx => $p)
                                    @php
                                        $discountAmt = ($p->diskon ?? 0) / 100 * $p->total_harga;
                                        $netTotal = $p->total_harga - $discountAmt;
                                        $dueAmt = max(0, $netTotal - $p->bayar);
                                        $isPaid = ($dueAmt <= 0);
                                    @endphp
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>
                                            <a href="{{ route('pembelian.create', $p->id_pembelian) }}" style="font-weight: 700; color: #0284c7;">
                                                PO #{{ $p->id_pembelian }}
                                            </a>
                                        </td>
                                        <td>{{ $p->created_at ? $p->created_at->format('d M Y, h:i A') : '--' }}</td>
                                        <td>
                                            <strong>{{ $p->supplier->nama ?? 'General Supplier' }}</strong>
                                            @if($p->supplier && $p->supplier->telepon)
                                                <br><small class="text-muted"><i class="fa fa-phone"></i> {{ $p->supplier->telepon }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center"><strong>{{ $p->total_item }}</strong></td>
                                        <td>{{ format_currency($p->total_harga) }}</td>
                                        <td>{{ $p->diskon > 0 ? $p->diskon . '%' : '0%' }}</td>
                                        <td><strong style="color: #0f172a;">{{ format_currency($netTotal) }}</strong></td>
                                        <td style="color: #15803d; font-weight: 700;">{{ format_currency($p->bayar) }}</td>
                                        <td style="color: {{ $dueAmt > 0 ? '#b91c1c' : '#64748b' }}; font-weight: 700;">
                                            {{ format_currency($dueAmt) }}
                                        </td>
                                        <td>
                                            @if($isPaid)
                                                <span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #86efac; font-weight:700;">Settled</span>
                                            @else
                                                <span class="badge" style="background:#fee2e2; color:#b91c1c; border:1px solid #fca5a5; font-weight:700;">Due</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('pembelian.index') }}" class="btn btn-xs btn-default btn-flat" title="Manage Purchase">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center text-muted" style="padding: 24px;">No purchase orders found for this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 2: Item-Wise Purchases -->
                <div class="tab-pane" id="tab_items">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover datatable-items" style="width: 100%;">
                            <thead>
                                <th width="4%">#</th>
                                <th>Raw Material / Product</th>
                                <th>Code</th>
                                <th>Supplier</th>
                                <th>Avg Purchase Price</th>
                                <th>Total Qty Inflow</th>
                                <th>Total Inflow Cost</th>
                            </thead>
                            <tbody>
                                @forelse($itemBreakdown as $idx => $it)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td><strong style="color: #0f172a;">{{ $it->nama_produk }}</strong></td>
                                        <td><span style="font-family: monospace; font-size: 11px; background:#f1f5f9; padding:2px 6px; border-radius:4px;">{{ $it->kode_produk }}</span></td>
                                        <td>{{ $it->nama_supplier ?: 'General Supplier' }}</td>
                                        <td>{{ format_currency($it->avg_unit_cost) }}</td>
                                        <td class="text-center"><span class="badge" style="background:#0f172a; color:#fff; font-size:12px; font-weight:700; padding:4px 8px;">{{ number_format($it->total_qty) }}</span></td>
                                        <td><strong style="color: #0284c7; font-size: 13.5px;">{{ format_currency($it->total_cost) }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted" style="padding: 24px;">No item purchase data found for this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 3: Supplier Payables & Balances -->
                <div class="tab-pane" id="tab_suppliers">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover datatable-suppliers" style="width: 100%;">
                            <thead>
                                <th width="4%">#</th>
                                <th>Supplier Name</th>
                                <th>Phone / Contact</th>
                                <th>Total POs</th>
                                <th>Total Billed</th>
                                <th>Total Paid</th>
                                <th>Outstanding Balance (Due)</th>
                                <th width="12%">Action</th>
                            </thead>
                            <tbody>
                                @forelse($supplierSummary as $idx => $sup)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td><strong style="color: #0f172a;">{{ $sup->nama }}</strong></td>
                                        <td>{{ $sup->telepon ?: '--' }}</td>
                                        <td class="text-center"><strong>{{ $sup->po_count }}</strong></td>
                                        <td>{{ format_currency($sup->total_billed) }}</td>
                                        <td style="color: #15803d; font-weight: 700;">{{ format_currency($sup->total_paid) }}</td>
                                        <td style="color: {{ $sup->total_due > 0 ? '#b91c1c' : '#15803d' }}; font-weight: 800; font-size: 13.5px;">
                                            {{ format_currency($sup->total_due) }}
                                        </td>
                                        <td>
                                            <a href="{{ route('pembelian.index') }}" class="btn btn-xs btn-primary btn-flat" style="border-radius: 4px; font-weight: 700;">
                                                <i class="fa fa-money"></i> View POs
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted" style="padding: 24px;">No supplier purchases found for this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('.datatable-purchases').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'asc']]
        });

        $('.datatable-items').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[5, 'desc']]
        });

        $('.datatable-suppliers').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[6, 'desc']]
        });
    });

    function setPeriod(type) {
        let today = new Date();
        let startDate = new Date();
        let endDate = new Date();

        if (type === 'today') {
            // today
        } else if (type === 'yesterday') {
            startDate.setDate(today.getDate() - 1);
            endDate.setDate(today.getDate() - 1);
        } else if (type === 'this_week') {
            let day = today.getDay();
            let diff = today.getDate() - day + (day === 0 ? -6 : 1);
            startDate = new Date(today.setDate(diff));
            endDate = new Date();
        } else if (type === 'this_month') {
            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            endDate = new Date();
        } else if (type === 'last_month') {
            startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            endDate = new Date(today.getFullYear(), today.getMonth(), 0);
        } else if (type === 'this_year') {
            startDate = new Date(today.getFullYear(), 0, 1);
            endDate = new Date();
        }

        let formatDate = (d) => {
            let month = '' + (d.getMonth() + 1);
            let day = '' + d.getDate();
            let year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            return [year, month, day].join('-');
        };

        $('#tanggal_awal').val(formatDate(startDate));
        $('#tanggal_akhir').val(formatDate(endDate));
        $('#purchaseFilterForm').submit();
    }
</script>
@endpush
