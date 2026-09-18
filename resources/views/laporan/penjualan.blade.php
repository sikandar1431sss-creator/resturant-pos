@extends('layouts.master')

@section('title')
    Sales Report
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Sales Report</li>
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
        border-top-color: #3b82f6;
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
            <form action="{{ route('laporan.penjualan') }}" method="GET" id="salesFilterForm">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                    <div>
                        <h4 style="font-weight: 800; font-size: 16px; color: #0f172a; margin: 0;">
                            <i class="fa fa-shopping-cart" style="color: #10b981;"></i> Sales &amp; Revenue Report
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
                    <div class="col-md-2 col-sm-6">
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 11.5px; font-weight: 700; color: #475569;">From Date</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control input-sm" value="{{ $tanggalAwal }}" style="border-radius: 6px; font-weight: 600;">
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6">
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 11.5px; font-weight: 700; color: #475569;">To Date</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control input-sm" value="{{ $tanggalAkhir }}" style="border-radius: 6px; font-weight: 600;">
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6">
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 11.5px; font-weight: 700; color: #475569;">Order Type</label>
                            <select name="tipe_order" class="form-control input-sm" style="border-radius: 6px; font-weight: 600;">
                                <option value="all" {{ $orderType === 'all' ? 'selected' : '' }}>All Channels</option>
                                <option value="Dine-In" {{ $orderType === 'Dine-In' ? 'selected' : '' }}>Dine-In</option>
                                <option value="Takeaway" {{ $orderType === 'Takeaway' ? 'selected' : '' }}>Takeaway</option>
                                <option value="Delivery" {{ $orderType === 'Delivery' ? 'selected' : '' }}>Delivery</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6">
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 11.5px; font-weight: 700; color: #475569;">Payment Status</label>
                            <select name="status_pembayaran" class="form-control input-sm" style="border-radius: 6px; font-weight: 600;">
                                <option value="all" {{ $paymentStatus === 'all' ? 'selected' : '' }}>All Invoices</option>
                                <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Paid In Full</option>
                                <option value="unpaid" {{ $paymentStatus === 'unpaid' ? 'selected' : '' }}>Unpaid / Due</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6">
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 11.5px; font-weight: 700; color: #475569;">Staff / Cashier</label>
                            <select name="id_user" class="form-control input-sm" style="border-radius: 6px; font-weight: 600;">
                                <option value="all">All Cashiers</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ $userId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
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

        <!-- 6 KPI Stat Cards -->
        <div class="row">
            <!-- Total Sales -->
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-blue">
                    <div class="kpi-title" style="color: #2563eb;">Total Billed Sales</div>
                    <div class="kpi-val" style="color: #0f172a;">{{ format_currency($totalSales) }}</div>
                    <div class="kpi-sub">Total revenue billed</div>
                </div>
            </div>

            <!-- Paid Cash -->
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-green">
                    <div class="kpi-title" style="color: #15803d;">Paid (Cash Realized)</div>
                    <div class="kpi-val" style="color: #10b981;">{{ format_currency($paidSales) }}</div>
                    <div class="kpi-sub">Collected payments</div>
                </div>
            </div>

            <!-- Unpaid Due -->
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-red">
                    <div class="kpi-title" style="color: #b91c1c;">Customer Dues</div>
                    <div class="kpi-val" style="color: #ef4444;">{{ format_currency($unpaidSales) }}</div>
                    <div class="kpi-sub">Pending receivables</div>
                </div>
            </div>

            <!-- Orders Count -->
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-dark">
                    <div class="kpi-title" style="color: #475569;">Total Orders</div>
                    <div class="kpi-val" style="color: #0f172a;">{{ number_format($totalOrders) }}</div>
                    <div class="kpi-sub">Avg Order: <strong>{{ format_currency($avgOrderValue) }}</strong></div>
                </div>
            </div>

            <!-- Items Sold -->
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-purple">
                    <div class="kpi-title" style="color: #7e22ce;">Dishes / Items Sold</div>
                    <div class="kpi-val" style="color: #8b5cf6;">{{ number_format($totalItemsSold) }}</div>
                    <div class="kpi-sub">Total item quantities</div>
                </div>
            </div>

            <!-- Discounts Given -->
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-amber">
                    <div class="kpi-title" style="color: #b45309;">Discounts Given</div>
                    <div class="kpi-val" style="color: #d97706;">{{ format_currency($totalDiscounts) }}</div>
                    <div class="kpi-sub">Promotional concessions</div>
                </div>
            </div>
        </div>

        <!-- Breakdown Nav Tabs -->
        <div class="nav-tabs-custom" style="border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_invoices" data-toggle="tab">
                        <i class="fa fa-list-alt text-blue"></i> Invoices &amp; Orders ({{ $totalOrders }})
                    </a>
                </li>
                <li>
                    <a href="#tab_items" data-toggle="tab">
                        <i class="fa fa-cutlery text-green"></i> Dish / Item-Wise Breakdown
                    </a>
                </li>
                <li>
                    <a href="#tab_channels" data-toggle="tab">
                        <i class="fa fa-pie-chart text-orange"></i> Channels &amp; Summary
                    </a>
                </li>
            </ul>

            <div class="tab-content" style="padding: 18px;">
                <!-- TAB 1: Invoices List -->
                <div class="tab-pane active" id="tab_invoices">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover datatable-sales" style="width: 100%;">
                            <thead>
                                <th width="4%">#</th>
                                <th width="10%">Invoice #</th>
                                <th>Date &amp; Time</th>
                                <th>Customer / Table</th>
                                <th>Channel</th>
                                <th>Items</th>
                                <th>Subtotal</th>
                                <th>Discount</th>
                                <th>Total Bill</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th>Cashier</th>
                            </thead>
                            <tbody>
                                @forelse($invoices as $idx => $inv)
                                    @php
                                        $isPaid = ($inv->status_pembayaran === 'paid' || $inv->diterima >= $inv->bayar);
                                        $dueAmount = $isPaid ? 0 : max(0, $inv->bayar - ($inv->diterima ?? 0));
                                        $discountAmt = $inv->total_harga > $inv->bayar ? ($inv->total_harga - $inv->bayar) : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>
                                            <a href="{{ route('penjualan.show', $inv->id_penjualan) }}" style="font-weight: 700; color: #2563eb;">
                                                #{{ $inv->id_penjualan }}
                                            </a>
                                        </td>
                                        <td>{{ $inv->created_at ? $inv->created_at->format('d M Y, h:i A') : '--' }}</td>
                                        <td>
                                            <strong>{{ $inv->member->nama ?? 'Walk-in Guest' }}</strong>
                                            @if($inv->nomor_meja)
                                                <br><small class="text-muted"><i class="fa fa-cutlery"></i> {{ $inv->nomor_meja }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($inv->tipe_order === 'Dine-In')
                                                <span class="badge" style="background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;">Dine-In</span>
                                            @elseif($inv->tipe_order === 'Delivery')
                                                <span class="badge" style="background:#fef3c7; color:#b45309; border:1px solid #fde68a;">Delivery</span>
                                            @else
                                                <span class="badge" style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;">Takeaway</span>
                                            @endif
                                        </td>
                                        <td class="text-center"><strong>{{ $inv->total_item }}</strong></td>
                                        <td>{{ format_currency($inv->total_harga) }}</td>
                                        <td>{{ format_currency($discountAmt) }}</td>
                                        <td><strong style="color: #0f172a;">{{ format_currency($inv->bayar) }}</strong></td>
                                        <td style="color: #15803d; font-weight: 700;">{{ format_currency($inv->diterima ?? ($isPaid ? $inv->bayar : 0)) }}</td>
                                        <td style="color: {{ $dueAmount > 0 ? '#b91c1c' : '#64748b' }}; font-weight: 700;">
                                            {{ format_currency($dueAmount) }}
                                        </td>
                                        <td>
                                            @if($isPaid)
                                                <span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #86efac; font-weight:700;">Paid</span>
                                            @else
                                                <span class="badge" style="background:#fee2e2; color:#b91c1c; border:1px solid #fca5a5; font-weight:700;">Unpaid</span>
                                            @endif
                                        </td>
                                        <td><small style="color: #64748b; font-weight:600;">{{ $inv->user->name ?? 'Staff' }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center text-muted" style="padding: 24px;">No sales transactions found for this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 2: Item-Wise Sales Breakdown -->
                <div class="tab-pane" id="tab_items">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover datatable-items" style="width: 100%;">
                            <thead>
                                <th width="4%">#</th>
                                <th>Product / Dish Name</th>
                                <th>Code</th>
                                <th>Category</th>
                                <th>Avg Selling Price</th>
                                <th>Total Qty Sold</th>
                                <th>Total Revenue Generated</th>
                            </thead>
                            <tbody>
                                @forelse($itemBreakdown as $idx => $it)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td><strong style="color: #0f172a;">{{ $it->nama_produk }}</strong></td>
                                        <td><span style="font-family: monospace; font-size: 11px; background:#f1f5f9; padding:2px 6px; border-radius:4px;">{{ $it->kode_produk }}</span></td>
                                        <td>{{ $it->nama_kategori ?: 'General' }}</td>
                                        <td>{{ format_currency($it->unit_price) }}</td>
                                        <td class="text-center"><span class="badge" style="background:#0f172a; color:#fff; font-size:12px; font-weight:700; padding:4px 8px;">{{ number_format($it->total_qty) }}</span></td>
                                        <td><strong style="color: #15803d; font-size: 13.5px;">{{ format_currency($it->total_revenue) }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted" style="padding: 24px;">No item sales data found for this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 3: Sales Channels & Summary -->
                <div class="tab-pane" id="tab_channels">
                    <div class="row">
                        <!-- Channel Share Cards -->
                        <div class="col-md-4">
                            <div style="background: #f0f9ff; border: 1.5px solid #bae6fd; border-radius: 10px; padding: 16px; margin-bottom: 16px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <h4 style="font-weight: 800; font-size: 15px; color: #0369a1; margin: 0;"><i class="fa fa-cutlery"></i> Dine-In</h4>
                                    <span class="badge" style="background: #0284c7; color: #fff;">{{ $dineInCount }} Orders</span>
                                </div>
                                <div style="font-size: 20px; font-weight: 900; color: #0c4a6e; margin-top: 10px;">{{ format_currency($dineInSales) }}</div>
                                <div style="font-size: 11.5px; color: #0284c7; margin-top: 4px;">
                                    {{ $totalSales > 0 ? round(($dineInSales / $totalSales) * 100, 1) : 0 }}% of Total Sales
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 16px; margin-bottom: 16px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <h4 style="font-weight: 800; font-size: 15px; color: #334155; margin: 0;"><i class="fa fa-shopping-bag"></i> Takeaway</h4>
                                    <span class="badge" style="background: #475569; color: #fff;">{{ $takeawayCount }} Orders</span>
                                </div>
                                <div style="font-size: 20px; font-weight: 900; color: #0f172a; margin-top: 10px;">{{ format_currency($takeawaySales) }}</div>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 4px;">
                                    {{ $totalSales > 0 ? round(($takeawaySales / $totalSales) * 100, 1) : 0 }}% of Total Sales
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div style="background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 10px; padding: 16px; margin-bottom: 16px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <h4 style="font-weight: 800; font-size: 15px; color: #b45309; margin: 0;"><i class="fa fa-motorcycle"></i> Delivery</h4>
                                    <span class="badge" style="background: #d97706; color: #fff;">{{ $deliveryCount }} Orders</span>
                                </div>
                                <div style="font-size: 20px; font-weight: 900; color: #78350f; margin-top: 10px;">{{ format_currency($deliverySales) }}</div>
                                <div style="font-size: 11.5px; color: #b45309; margin-top: 4px;">
                                    {{ $totalSales > 0 ? round(($deliverySales / $totalSales) * 100, 1) : 0 }}% of Total Sales
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status Summary Table -->
                    <div style="margin-top: 10px;">
                        <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin-bottom: 10px;">Collection &amp; Due Summary</h4>
                        <table class="table table-bordered">
                            <tr style="background: #f8fafc;">
                                <th>Category</th>
                                <th>Orders Count</th>
                                <th>Amount</th>
                                <th>Share (%)</th>
                            </tr>
                            <tr>
                                <td><strong style="color: #15803d;"><i class="fa fa-check-circle"></i> Paid Cash Realized</strong></td>
                                <td>{{ $invoices->where('status_pembayaran', 'paid')->count() }}</td>
                                <td><strong style="color: #15803d;">{{ format_currency($paidSales) }}</strong></td>
                                <td>{{ $totalSales > 0 ? round(($paidSales / $totalSales) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td><strong style="color: #b91c1c;"><i class="fa fa-clock-o"></i> Unpaid Receivables (Due)</strong></td>
                                <td>{{ $invoices->where('status_pembayaran', '!=', 'paid')->count() }}</td>
                                <td><strong style="color: #b91c1c;">{{ format_currency($unpaidSales) }}</strong></td>
                                <td>{{ $totalSales > 0 ? round(($unpaidSales / $totalSales) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr style="background: #f1f5f9; font-weight: 800;">
                                <td>TOTAL BILLED</td>
                                <td>{{ $totalOrders }}</td>
                                <td>{{ format_currency($totalSales) }}</td>
                                <td>100%</td>
                            </tr>
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
        $('.datatable-sales').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'asc']]
        });

        $('.datatable-items').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[5, 'desc']]
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
        $('#salesFilterForm').submit();
    }
</script>
@endpush
