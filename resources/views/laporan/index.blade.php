@extends('layouts.master')

@section('title')
    Income &amp; Profit Report
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
    .kpi-border-slate { border-top: 3.5px solid #64748b !important; }

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
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Income &amp; Profit Report</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        
        <!-- Filter Card -->
        <div class="filter-box-card">
            <form action="{{ route('laporan.index') }}" method="GET" id="incomeFilterForm">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                    <div>
                        <h4 style="font-weight: 800; font-size: 16px; color: #0f172a; margin: 0;">
                            <i class="fa fa-line-chart" style="color: #f59e0b;"></i> Income &amp; Profit / Loss Report
                        </h4>
                        <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                            Period: <strong>{{ date('d M Y', strtotime($tanggalAwal)) }}</strong> &mdash; <strong>{{ date('d M Y', strtotime($tanggalAkhir)) }}</strong>
                        </div>
                    </div>

                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        <button type="button" class="period-quick-btn" onclick="setPeriod('today')">Today</button>
                        <button type="button" class="period-quick-btn" onclick="setPeriod('yesterday')">Yesterday</button>
                        <button type="button" class="period-quick-btn" onclick="setPeriod('this_week')">This Week</button>
                        <button type="button" class="period-quick-btn" onclick="setPeriod('this_month')">This Month</button>
                        <button type="button" class="period-quick-btn" onclick="setPeriod('last_month')">Last Month</button>
                        <button type="button" class="period-quick-btn" onclick="setPeriod('this_year')">This Year</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-5">
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 11.5px; font-weight: 700; color: #475569;">From Date</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control input-sm" value="{{ $tanggalAwal }}" style="border-radius: 6px; font-weight: 600;">
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-5">
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 11.5px; font-weight: 700; color: #475569;">To Date</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control input-sm" value="{{ $tanggalAkhir }}" style="border-radius: 6px; font-weight: 600;">
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-2">
                        <div class="form-group" style="margin-bottom: 8px; padding-top: 23px; display: flex; gap: 6px;">
                            <button type="submit" class="btn btn-primary btn-sm btn-flat" style="border-radius: 6px; font-weight: 700; flex: 1;">
                                <i class="fa fa-filter"></i> Apply Period
                            </button>
                            <a href="{{ route('laporan.export_pdf', [$tanggalAwal, $tanggalAkhir]) }}" target="_blank" class="btn btn-default btn-sm btn-flat" style="border-radius: 6px; font-weight: 700;" title="Export PDF">
                                <i class="fa fa-file-pdf-o text-danger"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- 4 Summary KPI Cards -->
        <div class="row">
            <!-- Total Sales -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-blue">
                    <div class="kpi-title" style="color: #0284c7;">Total Sales Revenue</div>
                    <div class="kpi-val" style="color: #0f172a;">{{ format_currency($totalSales) }}</div>
                    <div class="kpi-sub">Gross customer sales</div>
                </div>
            </div>

            <!-- Total Purchases -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-slate">
                    <div class="kpi-title" style="color: #475569;">Total Purchases</div>
                    <div class="kpi-val" style="color: #334155;">{{ format_currency($totalPurchases) }}</div>
                    <div class="kpi-sub">Inventory &amp; stock cost</div>
                </div>
            </div>

            <!-- Operating Expenses -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-red">
                    <div class="kpi-title" style="color: #b91c1c;">Operating Expenses</div>
                    <div class="kpi-val" style="color: #ef4444;">{{ format_currency($totalExpenses) }}</div>
                    <div class="kpi-sub">Daily operational costs</div>
                </div>
            </div>

            <!-- Net Profit / Income -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="report-card-kpi kpi-border-green">
                    <div class="kpi-title" style="color: #15803d;">Net Income / Profit</div>
                    <div class="kpi-val" style="color: {{ $netIncome >= 0 ? '#10b981' : '#ef4444' }};">
                        {{ format_currency($netIncome) }}
                    </div>
                    <div class="kpi-sub">Sales &minus; Purchases &minus; Expenses</div>
                </div>
            </div>
        </div>

        <!-- Main Daily Breakdown Table Box -->
        <div class="box" style="border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <div class="box-header with-border" style="background: #ffffff; padding: 14px 18px;">
                <h3 class="box-title" style="font-weight: 800; font-size: 15px; color: #0f172a; margin: 0;">
                    <i class="fa fa-calendar-check-o text-primary"></i> Daily Income Breakdown
                </h3>
            </div>
            <div class="box-body table-responsive" style="padding: 18px;">
                <table class="table table-striped table-bordered table-hover datatable-income" style="width: 100%;">
                    <thead>
                        <th width="5%">#</th>
                        <th>Date</th>
                        <th>Sales</th>
                        <th>Purchases</th>
                        <th>Expenses</th>
                        <th>Net Income</th>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.datatable-income').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('laporan.data', [$tanggalAwal, $tanggalAkhir]) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'penjualan'},
                {data: 'pembelian'},
                {data: 'pengeluaran'},
                {data: 'pendapatan'}
            ],
            dom: 'Brt',
            bSort: false,
            bPaginate: false,
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
        $('#incomeFilterForm').submit();
    }
</script>
@endpush