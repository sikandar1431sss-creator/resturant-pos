@extends('layouts.master')

@section('title')
    Supplier Statement - {{ $supplier->nama }}
@endsection

@section('breadcrumb')
    @parent
    <li><a href="{{ route('supplier.index') }}">Suppliers</a></li>
    <li class="active">{{ $supplier->nama }} Statement</li>
@endsection

@push('css')
<style>
    .supplier-header-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    .supplier-avatar {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 800;
        box-shadow: 0 4px 10px rgba(2, 132, 199, 0.25);
    }
    .info-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
    }
    
    .ledger-filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .period-btn {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 13px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .period-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .period-btn.active {
        background: #0284c7;
        color: #fff;
        border-color: #0284c7;
    }

    .kpi-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform 0.15s ease;
    }
    .kpi-stat-card:hover {
        transform: translateY(-2px);
    }
    .kpi-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .kpi-icon-amber { background: #fef3c7; color: #d97706; }
    .kpi-icon-blue { background: #e0f2fe; color: #0284c7; }
    .kpi-icon-green { background: #dcfce7; color: #15803d; }
    .kpi-icon-red { background: #fee2e2; color: #dc2626; }

    .kpi-title-text {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 3px;
    }
    .kpi-amount-text {
        font-size: 20px;
        font-weight: 900;
        margin: 0;
        line-height: 1.2;
    }
    .kpi-help-text {
        font-size: 11px;
        color: #64748b;
        margin-top: 2px;
        font-weight: 500;
    }

    .statement-table-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    @media print {
        .btn-print-hide, .main-sidebar, .main-header, .content-header, .ledger-filter-card {
            display: none !important;
        }
        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }
        .kpi-stat-card, .supplier-header-box, .statement-table-box {
            border: 1px solid #cbd5e1 !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        
        <!-- 1. Supplier Profile Header Card -->
        <div class="supplier-header-box">
            <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                <div class="supplier-avatar">
                    <i class="fa fa-truck"></i>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 6px;">
                        <span style="font-size: 18px; font-weight: 900; color: #0f172a;">{{ $supplier->nama }}</span>
                        @if($currentTotalDue > 0.01)
                            <span class="label label-danger" style="font-size: 11px; font-weight: 800; border-radius: 4px; padding: 3px 8px;">
                                <i class="fa fa-exclamation-circle"></i> Pending Payable Due: {{ format_currency($currentTotalDue) }}
                            </span>
                        @else
                            <span class="label label-success" style="font-size: 11px; font-weight: 800; border-radius: 4px; padding: 3px 8px;">
                                <i class="fa fa-check-circle"></i> Account Clear (Settled)
                            </span>
                        @endif
                    </div>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <span class="info-chip"><i class="fa fa-phone text-muted"></i> {{ $supplier->telepon ?: 'No Phone' }}</span>
                        <span class="info-chip"><i class="fa fa-map-marker text-muted"></i> {{ $supplier->alamat ?: 'No Address' }}</span>
                    </div>
                </div>
            </div>

            <!-- Header Action Buttons -->
            <div style="display: flex; align-items: center; gap: 8px;" class="btn-print-hide">
                @if($currentTotalDue > 0.01)
                    <button type="button" class="btn btn-success btn-flat" style="border-radius: 8px; font-weight: 700; background-color: #10b981; border-color: #10b981; padding: 7px 16px;" onclick="openPayModal()">
                        <i class="fa fa-money"></i> Record Payment
                    </button>
                @endif
                <button type="button" onclick="window.print()" class="btn btn-default btn-flat" style="border-radius: 8px; font-weight: 700; padding: 7px 14px; border-color: #cbd5e1;">
                    <i class="fa fa-print"></i> Print Statement
                </button>
                <a href="{{ route('supplier.index') }}" class="btn btn-default btn-flat" style="border-radius: 8px; font-weight: 600; padding: 7px 14px; border-color: #cbd5e1;">
                    <i class="fa fa-arrow-left"></i> Back to Suppliers
                </a>
            </div>
        </div>

        <!-- 2. Clean Date Filter Toolbar -->
        <div class="ledger-filter-card">
            <form action="{{ route('ledger.supplier.statement', $supplier->id_supplier) }}" method="GET" id="statementFilterForm">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                    <div style="font-size: 13px; font-weight: 700; color: #1e293b;">
                        <i class="fa fa-calendar text-primary"></i> Statement Period: <span style="color:#0284c7;">{{ date('d M Y', strtotime($tanggalAwal)) }}</span> &mdash; <span style="color:#0284c7;">{{ date('d M Y', strtotime($tanggalAkhir)) }}</span>
                    </div>
                    <div style="display:flex; gap:6px; flex-wrap:wrap;" class="btn-print-hide">
                        <button type="button" class="period-btn" onclick="setPeriod('today')">Today</button>
                        <button type="button" class="period-btn" onclick="setPeriod('this_week')">This Week</button>
                        <button type="button" class="period-btn" onclick="setPeriod('this_month')">This Month</button>
                        <button type="button" class="period-btn" onclick="setPeriod('last_month')">Last Month</button>
                        <button type="button" class="period-btn" onclick="setPeriod('this_year')">This Year</button>
                        <button type="button" class="period-btn" onclick="setPeriod('all_time')">All Time</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-5">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">From Date</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="{{ $tanggalAwal }}" style="border-radius: 6px; font-weight: 600; height: 38px;">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-5">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">To Date</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ $tanggalAkhir }}" style="border-radius: 6px; font-weight: 600; height: 38px;">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-2" style="padding-top: 21px;">
                        <button type="submit" class="btn btn-primary btn-flat" style="border-radius: 6px; font-weight: 700; width: 100%; height: 38px; background: #0284c7; border-color: #0284c7;">
                            <i class="fa fa-filter"></i> Apply Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 3. 4 Modern KPI Summary Cards -->
        <div class="row">
            <!-- Opening Balance -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="kpi-stat-card">
                    <div class="kpi-icon-box kpi-icon-amber">
                        <i class="fa fa-history"></i>
                    </div>
                    <div>
                        <div class="kpi-title-text" style="color: #b45309;">Opening Balance</div>
                        <div class="kpi-amount-text" style="color: #0f172a;">{{ format_currency($openingBalance) }}</div>
                        <div class="kpi-help-text">Payable before period</div>
                    </div>
                </div>
            </div>

            <!-- Invoiced / Stock-In (+) -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="kpi-stat-card">
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fa fa-truck"></i>
                    </div>
                    <div>
                        <div class="kpi-title-text" style="color: #0284c7;">Purchases Billed (+)</div>
                        <div class="kpi-amount-text" style="color: #0284c7;">{{ format_currency($invoicedInPeriod) }}</div>
                        <div class="kpi-help-text">Stock-in invoices in period</div>
                    </div>
                </div>
            </div>

            <!-- Payments Disbursed (-) -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="kpi-stat-card">
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fa fa-money"></i>
                    </div>
                    <div>
                        <div class="kpi-title-text" style="color: #15803d;">Paid to Supplier (-)</div>
                        <div class="kpi-amount-text" style="color: #10b981;">{{ format_currency($paidInPeriod) }}</div>
                        <div class="kpi-help-text">Payments paid out</div>
                    </div>
                </div>
            </div>

            <!-- Closing Payable Due (=) -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="kpi-stat-card">
                    <div class="kpi-icon-box kpi-icon-red">
                        <i class="fa fa-balance-scale"></i>
                    </div>
                    <div>
                        <div class="kpi-title-text" style="color: #b91c1c;">Closing Balance (=)</div>
                        <div class="kpi-amount-text" style="color: {{ $closingBalance > 0.01 ? '#ef4444' : '#10b981' }};">{{ format_currency($closingBalance) }}</div>
                        <div class="kpi-help-text">Ending payable due</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Statement Table Card -->
        <div class="statement-table-box">
            <div style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; font-weight: 800; font-size: 15px; color: #0f172a;">
                    <i class="fa fa-list-alt text-primary"></i> Account Statement Transactions
                </h4>
                <span class="text-muted" style="font-size: 12px; font-weight: 600;">
                    Showing transactions from <strong>{{ date('d M Y', strtotime($tanggalAwal)) }}</strong> to <strong>{{ date('d M Y', strtotime($tanggalAkhir)) }}</strong>
                </span>
            </div>

            <div class="table-responsive" style="padding: 15px 20px;">
                <table class="table table-striped table-bordered table-hover datatable-statement" style="width: 100%; margin-bottom: 0;">
                    <thead style="background: #f8fafc;">
                        <th width="4%">#</th>
                        <th>Date &amp; Time</th>
                        <th>Ref / PO #</th>
                        <th>Transaction Type</th>
                        <th>Details / Notes</th>
                        <th style="color: #15803d; text-align: right;">Paid to Supplier (-)</th>
                        <th style="color: #0284c7; text-align: right;">Bill Added (+)</th>
                        <th style="color: #ef4444; text-align: right;">Net Balance Due</th>
                        <th width="6%" class="btn-print-hide text-center"><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        <!-- Opening Balance Row -->
                        <tr style="background: #f8fafc; font-weight: 700;">
                            <td class="text-center">-</td>
                            <td>{{ date('d M Y', strtotime($tanggalAwal)) }} 00:00</td>
                            <td><span class="label label-default" style="font-size: 10.5px; font-weight: 700;">OPENING</span></td>
                            <td><strong>Opening Balance</strong></td>
                            <td class="text-muted">Payable prior to {{ date('d M Y', strtotime($tanggalAwal)) }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right"><strong style="color: #0f172a; font-size: 13.5px;">{{ format_currency($openingBalance) }}</strong></td>
                            <td class="btn-print-hide text-center">-</td>
                        </tr>

                        @forelse($transactions as $idx => $t)
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td>{{ date('d M Y, h:i A', strtotime($t['date'])) }}</td>
                                <td>
                                    <span class="label" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-family: monospace; font-size: 11.5px; font-weight: 700;">
                                        {{ $t['ref_no'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($t['debit'] > 0)
                                        <span class="label label-success" style="font-size: 10.5px; font-weight: 700;"><i class="fa fa-arrow-down"></i> Payment Paid</span>
                                    @else
                                        <span class="label label-primary" style="font-size: 10.5px; font-weight: 700; background-color: #0284c7;"><i class="fa fa-file-text-o"></i> Stock-In PO</span>
                                    @endif
                                </td>
                                <td>{{ $t['description'] }}</td>
                                <td style="color: #15803d; font-weight: 700; text-align: right;">
                                    {{ $t['debit'] > 0 ? format_currency($t['debit']) : '-' }}
                                </td>
                                <td style="color: #0284c7; font-weight: 700; text-align: right;">
                                    {{ $t['credit'] > 0 ? format_currency($t['credit']) : '-' }}
                                </td>
                                <td style="text-align: right;">
                                    <strong style="color: {{ $t['balance'] > 0.01 ? '#ef4444' : '#15803d' }}; font-size: 13.5px;">
                                        {{ format_currency($t['balance']) }}
                                    </strong>
                                </td>
                                <td class="btn-print-hide text-center">
                                    <a href="{{ $t['print_url'] }}" target="_blank" class="btn btn-xs btn-default btn-flat" title="Print PO Thermal Slip" style="border-radius: 4px;">
                                        <i class="fa fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted" style="padding: 28px;">
                                    <i class="fa fa-folder-open-o" style="font-size: 28px; display: block; margin-bottom: 8px; color: #cbd5e1;"></i>
                                    No transactions recorded for this supplier in the selected period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot style="background: #f8fafc; font-weight: 800;">
                        <tr>
                            <td colspan="5" class="text-right">Period Totals / Ending Balance:</td>
                            <td style="color: #15803d; text-align: right;">{{ format_currency($paidInPeriod) }}</td>
                            <td style="color: #0284c7; text-align: right;">{{ format_currency($invoicedInPeriod) }}</td>
                            <td style="color: {{ $closingBalance > 0.01 ? '#ef4444' : '#15803d' }}; font-size: 14px; text-align: right;">
                                {{ format_currency($closingBalance) }}
                            </td>
                            <td class="btn-print-hide"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Modal Pay Supplier Due -->
<div class="modal fade" id="modal-pay-supplier" tabindex="-1" role="dialog" aria-labelledby="modal-pay-supplier">
    <div class="modal-dialog modal-sm" role="document">
        <form id="form-pay-supplier" method="post" class="form-horizontal">
            @csrf
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); color: #fff; padding: 15px 20px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="font-weight: 700; font-size: 16px;"><i class="fa fa-money"></i> Record Supplier Payment</h4>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <input type="hidden" name="id_supplier" id="pay_id_supplier" value="{{ $supplier->id_supplier }}">
                    
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom: 4px;">
                            <span style="color:#64748b; font-size:12px;">Supplier:</span>
                            <strong style="color:#1e293b; font-size:12px;">{{ $supplier->nama }}</strong>
                        </div>
                        <hr style="margin: 8px 0; border-top: 1px dashed #cbd5e1;">
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#ef4444; font-weight:700; font-size:13px;">Total Outstanding Due:</span>
                            <strong style="color:#ef4444; font-weight:800; font-size:14px;">{{ format_currency($currentTotalDue) }}</strong>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="amount_paid" style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; display:block;">Payment Amount (Rs)</label>
                        <div class="input-group">
                            <span class="input-group-addon" style="background:#f1f5f9; font-weight:700; color:#475569;">Rs</span>
                            <input type="number" step="any" min="1" max="{{ $currentTotalDue }}" name="amount" id="amount_paid" class="form-control" value="{{ $currentTotalDue }}" required style="font-weight:700; font-size:15px; color:#0f172a; height:42px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 20px;">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" style="border-radius:6px; font-weight:600;">Cancel</button>
                    <button type="submit" class="btn btn-success btn-flat" id="btn-submit-pay" style="border-radius:6px; font-weight:700; background-color:#10b981; border-color:#10b981;"><i class="fa fa-save"></i> Save Payment</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('.datatable-statement').DataTable({
            responsive: true,
            pageLength: 50,
            ordering: false,
            bSort: false,
            dom: 'Brt'
        });

        $('#form-pay-supplier').on('submit', function(e) {
            e.preventDefault();
            let id = $('#pay_id_supplier').val();
            let amount = $('#amount_paid').val();
            let btn = $('#btn-submit-pay');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: `{{ url('/ledger/supplier') }}/${id}/pay`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    amount: amount
                },
                success: function(response) {
                    $('#modal-pay-supplier').modal('hide');
                    showSuccessToast(response.message || 'Payment recorded successfully!');
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error recording payment';
                    showErrorToast(msg);
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Payment');
                }
            });
        });
    });

    function openPayModal() {
        $('#modal-pay-supplier').modal('show');
    }

    function setPeriod(type) {
        let today = new Date();
        let startDate = new Date();
        let endDate = new Date();

        if (type === 'today') {
            // today
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
        } else if (type === 'all_time') {
            startDate = new Date(2020, 0, 1);
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
        $('#statementFilterForm').submit();
    }
</script>
@endpush
