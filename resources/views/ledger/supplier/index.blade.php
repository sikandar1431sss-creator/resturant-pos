@extends('layouts.master')

@section('title')
    Supplier Ledgers
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Supplier Ledgers</li>
@endsection

@push('css')
<style>
    .ledger-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .ledger-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
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
    .kpi-border-blue { border-top: 3.5px solid #0284c7 !important; }
    .kpi-border-green { border-top: 3.5px solid #10b981 !important; }
    .kpi-border-red { border-top: 3.5px solid #ef4444 !important; }
    .kpi-border-purple { border-top: 3.5px solid #8b5cf6 !important; }

    .table-actions-group {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        flex-wrap: nowrap !important;
        vertical-align: middle !important;
    }
    .btn-table-action {
        width: 32px !important;
        height: 32px !important;
        min-width: 32px !important;
        border-radius: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 13px !important;
        border: 1px solid transparent !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.06) !important;
        text-decoration: none !important;
    }
    .btn-table-action:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important;
    }
    .btn-table-action.btn-view {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        border-color: #0284c7 !important;
    }
    .btn-table-action.btn-view:hover {
        background-color: #0369a1 !important;
        color: #ffffff !important;
    }
    .btn-table-action.btn-pay {
        background-color: #10b981 !important;
        color: #ffffff !important;
        border-color: #10b981 !important;
    }
    .btn-table-action.btn-pay:hover {
        background-color: #059669 !important;
        color: #ffffff !important;
    }
    .filter-tab-btn {
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .filter-tab-btn.active {
        background: #0284c7;
        color: #fff;
        border-color: #0284c7;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <!-- 4 KPI Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="ledger-kpi-card kpi-border-blue">
                    <div class="kpi-title" style="color: #0284c7;">Total Purchases Billed</div>
                    <div class="kpi-val" style="color: #0f172a;">{{ format_currency($totalPurchases) }}</div>
                    <div class="kpi-sub">Net stock purchase orders</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="ledger-kpi-card kpi-border-green">
                    <div class="kpi-title" style="color: #15803d;">Paid to Suppliers</div>
                    <div class="kpi-val" style="color: #10b981;">{{ format_currency($totalPaid) }}</div>
                    <div class="kpi-sub">Total payments disbursed</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="ledger-kpi-card kpi-border-red">
                    <div class="kpi-title" style="color: #b91c1c;">Outstanding Payables</div>
                    <div class="kpi-val" style="color: #ef4444;">{{ format_currency($totalDue) }}</div>
                    <div class="kpi-sub">Total supplier dues to pay</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="ledger-kpi-card kpi-border-purple">
                    <div class="kpi-title" style="color: #6b21a8;">Suppliers with Dues</div>
                    <div class="kpi-val" style="color: #0f172a;">{{ $suppliersWithDue }} <span style="font-size: 14px; font-weight: 600; color: #64748b;">/ {{ $suppliers->count() }}</span></div>
                    <div class="kpi-sub">Vendors awaiting payment</div>
                </div>
            </div>
        </div>

        <!-- Main Card with Table -->
        <div class="box" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <div class="box-header with-border" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; padding: 16px 20px;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <button type="button" class="filter-tab-btn active" onclick="filterStatus('all', this)">All Suppliers</button>
                    <button type="button" class="filter-tab-btn" onclick="filterStatus('due', this)">Has Pending Due</button>
                    <button type="button" class="filter-tab-btn" onclick="filterStatus('settled', this)">Fully Settled</button>
                </div>
                <div>
                    <a href="{{ route('pembelian.index') }}" class="btn btn-default btn-sm" style="border-radius: 6px; font-weight: 600;">
                        <i class="fa fa-cart-arrow-down"></i> Purchases List
                    </a>
                </div>
            </div>

            <div class="box-body table-responsive" style="padding: 20px;">
                <table class="table table-striped table-bordered table-hover table-supplier-ledger" style="width: 100%;">
                    <thead style="background: #f8fafc;">
                        <th width="4%">#</th>
                        <th>Supplier</th>
                        <th width="8%">POs</th>
                        <th>Gross Total</th>
                        <th>Discount</th>
                        <th>Net Billed</th>
                        <th>Total Paid</th>
                        <th>Balance Due</th>
                        <th>Status</th>
                        <th width="10%"><i class="fa fa-cog"></i></th>
                    </thead>
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
                    <input type="hidden" name="id_supplier" id="pay_id_supplier">
                    
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom: 4px;">
                            <span style="color:#64748b; font-size:12px;">Supplier:</span>
                            <strong style="color:#1e293b; font-size:12px;" id="pay_supplier_name">-</strong>
                        </div>
                        <hr style="margin: 8px 0; border-top: 1px dashed #cbd5e1;">
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#ef4444; font-weight:700; font-size:13px;">Total Due:</span>
                            <strong style="color:#ef4444; font-weight:800; font-size:14px;" id="pay_due_amount">Rs 0</strong>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="amount_paid" style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; display:block;">Payment Amount (Rs)</label>
                        <div class="input-group">
                            <span class="input-group-addon" style="background:#f1f5f9; font-weight:700; color:#475569;">Rs</span>
                            <input type="number" step="any" min="1" name="amount" id="amount_paid" class="form-control" required style="font-weight:700; font-size:15px; color:#0f172a; height:42px;">
                        </div>
                        <div style="margin-top: 8px; display:flex; gap: 6px;">
                            <button type="button" class="btn btn-xs btn-default" onclick="setPayFull()" style="font-weight:600; border-radius:4px;"><i class="fa fa-check"></i> Pay Full Due</button>
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
    let table;
    let currentStatus = 'all';
    let currentSupplierDue = 0;

    $(function () {
        table = $('.table-supplier-ledger').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('ledger.supplier.data') }}',
                data: function (d) {
                    d.status = currentStatus;
                }
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'supplier_name'},
                {data: 'po_count'},
                {data: 'gross_total'},
                {data: 'discount'},
                {data: 'net_total'},
                {data: 'total_paid'},
                {data: 'due_balance'},
                {data: 'status'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
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
                    table.ajax.reload();
                    setTimeout(() => location.reload(), 1200);
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

    function filterStatus(status, btn) {
        currentStatus = status;
        $('.filter-tab-btn').removeClass('active');
        $(btn).addClass('active');
        table.ajax.reload();
    }

    function openPaySupplierModal(id, name, due) {
        currentSupplierDue = due;
        $('#pay_id_supplier').val(id);
        $('#pay_supplier_name').text(name);
        $('#pay_due_amount').text('Rs ' + Number(due).toLocaleString());
        $('#amount_paid').val(due).attr('max', due);
        $('#modal-pay-supplier').modal('show');
    }

    function setPayFull() {
        $('#amount_paid').val(currentSupplierDue);
    }
</script>
@endpush
