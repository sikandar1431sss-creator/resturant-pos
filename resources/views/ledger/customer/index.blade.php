@extends('layouts.master')

@section('title')
    Customer Ledgers
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Customer Ledgers</li>
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
    .kpi-border-purple { border-top: 3.5px solid #8b5cf6 !important; }
    .kpi-border-green { border-top: 3.5px solid #10b981 !important; }
    .kpi-border-red { border-top: 3.5px solid #ef4444 !important; }
    .kpi-border-amber { border-top: 3.5px solid #f59e0b !important; }

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
        background-color: #8b5cf6 !important;
        color: #ffffff !important;
        border-color: #8b5cf6 !important;
    }
    .btn-table-action.btn-view:hover {
        background-color: #7c3aed !important;
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
        background: #8b5cf6;
        color: #fff;
        border-color: #8b5cf6;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <!-- 4 KPI Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="ledger-kpi-card kpi-border-purple">
                    <div class="kpi-title" style="color: #7c3aed;">Total Invoiced Sales</div>
                    <div class="kpi-val" style="color: #0f172a;">{{ format_currency($totalInvoiced) }}</div>
                    <div class="kpi-sub">Total billed to registered customers</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="ledger-kpi-card kpi-border-green">
                    <div class="kpi-title" style="color: #15803d;">Payments Received</div>
                    <div class="kpi-val" style="color: #10b981;">{{ format_currency($totalReceived) }}</div>
                    <div class="kpi-sub">Total collections received</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="ledger-kpi-card kpi-border-red">
                    <div class="kpi-title" style="color: #b91c1c;">Customer Receivables</div>
                    <div class="kpi-val" style="color: #ef4444;">{{ format_currency($totalCustomerDue) }}</div>
                    <div class="kpi-sub">Total outstanding customer dues</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="ledger-kpi-card kpi-border-amber">
                    <div class="kpi-title" style="color: #b45309;">Customers with Dues</div>
                    <div class="kpi-val" style="color: #0f172a;">{{ $customersWithDue }} <span style="font-size: 14px; font-weight: 600; color: #64748b;">/ {{ $members->count() }}</span></div>
                    <div class="kpi-sub">Accounts with pending balance</div>
                </div>
            </div>
        </div>

        <!-- Main Card with Table -->
        <div class="box" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <div class="box-header with-border" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; padding: 16px 20px;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <button type="button" class="filter-tab-btn active" onclick="filterStatus('all', this)">All Customers</button>
                    <button type="button" class="filter-tab-btn" onclick="filterStatus('due', this)">Has Pending Due</button>
                    <button type="button" class="filter-tab-btn" onclick="filterStatus('settled', this)">Fully Settled</button>
                </div>
                <div>
                    <a href="{{ route('member.index') }}" class="btn btn-default btn-sm" style="border-radius: 6px; font-weight: 600;">
                        <i class="fa fa-users"></i> Customer Contacts
                    </a>
                </div>
            </div>

            <div class="box-body table-responsive" style="padding: 20px;">
                <table class="table table-striped table-bordered table-hover table-customer-ledger" style="width: 100%;">
                    <thead style="background: #f8fafc;">
                        <th width="4%">#</th>
                        <th>Customer / Contact</th>
                        <th width="8%">Orders</th>
                        <th>Gross Total</th>
                        <th>Discount</th>
                        <th>Net Invoiced</th>
                        <th>Total Received</th>
                        <th>Outstanding Due</th>
                        <th>Status</th>
                        <th width="10%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Receive Customer Due -->
<div class="modal fade" id="modal-receive-customer" tabindex="-1" role="dialog" aria-labelledby="modal-receive-customer">
    <div class="modal-dialog modal-sm" role="document">
        <form id="form-receive-customer" method="post" class="form-horizontal">
            @csrf
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); color: #fff; padding: 15px 20px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="font-weight: 700; font-size: 16px;"><i class="fa fa-money"></i> Receive Customer Payment</h4>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <input type="hidden" name="id_member" id="receive_id_member">
                    
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom: 4px;">
                            <span style="color:#64748b; font-size:12px;">Customer:</span>
                            <strong style="color:#1e293b; font-size:12px;" id="receive_customer_name">-</strong>
                        </div>
                        <hr style="margin: 8px 0; border-top: 1px dashed #cbd5e1;">
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#ef4444; font-weight:700; font-size:13px;">Outstanding Due:</span>
                            <strong style="color:#ef4444; font-weight:800; font-size:14px;" id="receive_due_amount">Rs 0</strong>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 12px;">
                        <label for="amount_received" style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; display:block;">Amount Received (Rs)</label>
                        <div class="input-group">
                            <span class="input-group-addon" style="background:#f1f5f9; font-weight:700; color:#475569;">Rs</span>
                            <input type="number" step="any" min="1" name="amount" id="amount_received" class="form-control" required style="font-weight:700; font-size:15px; color:#0f172a; height:42px;">
                        </div>
                        <div style="margin-top: 8px; display:flex; gap: 6px;">
                            <button type="button" class="btn btn-xs btn-default" onclick="setReceiveFull()" style="font-weight:600; border-radius:4px;"><i class="fa fa-check"></i> Settle Full Due</button>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; display:block;">Payment Method</label>
                        <select name="metode_pembayaran" class="form-control" style="font-weight: 600; height: 38px;">
                            <option value="cash">Cash</option>
                            <option value="card">Card / POS</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="easypaisa">Easypaisa / JazzCash</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 20px;">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" style="border-radius:6px; font-weight:600;">Cancel</button>
                    <button type="submit" class="btn btn-success btn-flat" id="btn-submit-receive" style="border-radius:6px; font-weight:700; background-color:#10b981; border-color:#10b981;"><i class="fa fa-check-circle"></i> Save Payment</button>
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
    let currentCustomerDue = 0;

    $(function () {
        table = $('.table-customer-ledger').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('ledger.customer.data') }}',
                data: function (d) {
                    d.status = currentStatus;
                }
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'customer_name'},
                {data: 'order_count'},
                {data: 'gross_total'},
                {data: 'discount'},
                {data: 'net_invoiced'},
                {data: 'total_received'},
                {data: 'due_balance'},
                {data: 'status'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#form-receive-customer').on('submit', function(e) {
            e.preventDefault();
            let id = $('#receive_id_member').val();
            let amount = $('#amount_received').val();
            let btn = $('#btn-submit-receive');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: `{{ url('/ledger/customer') }}/${id}/receive`,
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#modal-receive-customer').modal('hide');
                    showSuccessToast(response.message || 'Payment received successfully!');
                    table.ajax.reload();
                    setTimeout(() => location.reload(), 1200);
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error receiving payment';
                    showErrorToast(msg);
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fa fa-check-circle"></i> Save Payment');
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

    function openReceiveCustomerModal(id, name, due) {
        currentCustomerDue = due;
        $('#receive_id_member').val(id);
        $('#receive_customer_name').text(name);
        $('#receive_due_amount').text('Rs ' + Number(due).toLocaleString());
        $('#amount_received').val(due).attr('max', due);
        $('#modal-receive-customer').modal('show');
    }

    function setReceiveFull() {
        $('#amount_received').val(currentCustomerDue);
    }
</script>
@endpush
