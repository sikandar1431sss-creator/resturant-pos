@extends('layouts.master')

@section('title')
    Purchases
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Purchases</li>
@endsection

@push('css')
<style>
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
        border-radius: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 13.5px !important;
        border: 1px solid transparent !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
        text-decoration: none !important;
    }
    .btn-table-action:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.18) !important;
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
    .btn-table-action.btn-print {
        background-color: #f1f5f9 !important;
        color: #334155 !important;
        border-color: #cbd5e1 !important;
    }
    .btn-table-action.btn-print:hover {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
    }
    .btn-table-action.btn-edit {
        background-color: #f59e0b !important;
        color: #ffffff !important;
        border-color: #f59e0b !important;
    }
    .btn-table-action.btn-edit:hover {
        background-color: #d97706 !important;
        color: #ffffff !important;
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
    .btn-table-action.btn-delete {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        border-color: #ef4444 !important;
    }
    .btn-table-action.btn-delete:hover {
        background-color: #dc2626 !important;
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm()" class="btn btn-success btn-flat" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-plus-circle"></i> Add New Purchase</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-pembelian table-hover">
                    <thead>
                        <th width="5%">#</th>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Discount</th>
                        <th>Paid</th>
                        <th>Status</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-pay-supplier" tabindex="-1" role="dialog" aria-labelledby="modal-pay-supplier">
    <div class="modal-dialog modal-sm" role="document">
        <form id="form-pay-supplier" method="post" class="form-horizontal">
            @csrf
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); color: #fff; padding: 15px 20px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="font-weight: 700; font-size: 16px;"><i class="fa fa-money"></i> Settle Supplier Due</h4>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <input type="hidden" name="id_pembelian" id="pay_id_pembelian">
                    
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom: 4px;">
                            <span style="color:#64748b; font-size:12px;">Supplier:</span>
                            <strong style="color:#1e293b; font-size:12px;" id="pay_supplier_name">-</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom: 4px;">
                            <span style="color:#64748b; font-size:12px;">Net Invoice:</span>
                            <strong style="color:#1e293b; font-size:12px;" id="pay_net_total">Rs 0</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom: 4px;">
                            <span style="color:#64748b; font-size:12px;">Already Paid:</span>
                            <strong style="color:#059669; font-size:12px;" id="pay_already_paid">Rs 0</strong>
                        </div>
                        <hr style="margin: 8px 0; border-top: 1px dashed #cbd5e1;">
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#ef4444; font-weight:700; font-size:13px;">Remaining Due:</span>
                            <strong style="color:#ef4444; font-weight:800; font-size:14px;" id="pay_due_amount">Rs 0</strong>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="amount_paid" style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; display:block;">Amount to Pay (Rs)</label>
                        <div class="input-group">
                            <span class="input-group-addon" style="background:#f1f5f9; font-weight:700; color:#475569;">Rs</span>
                            <input type="number" step="any" min="1" name="amount_paid" id="amount_paid" class="form-control" required style="font-weight:700; font-size:15px; color:#0f172a; height:42px;">
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

@includeIf('pembelian.supplier')
@includeIf('pembelian.detail')
@endsection

@push('scripts')
<script>
    let table, table1;
    let currentDueVal = 0;

    $(function () {
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('pembelian.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'supplier'},
                {data: 'total_item'},
                {data: 'total_harga'},
                {data: 'diskon'},
                {data: 'bayar'},
                {data: 'status'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('.table-supplier').DataTable();
        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_beli'},
                {data: 'jumlah'},
                {data: 'subtotal'},
            ]
        });

        $('#form-pay-supplier').on('submit', function(e) {
            e.preventDefault();
            let id = $('#pay_id_pembelian').val();
            let amount = $('#amount_paid').val();
            let btn = $('#btn-submit-pay');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

            $.ajax({
                url: `{{ url('/pembelian') }}/${id}/settle-payment`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    amount_paid: amount
                },
                success: function(response) {
                    $('#modal-pay-supplier').modal('hide');
                    showSuccessToast(response.message || 'Payment recorded successfully!');
                    table.ajax.reload();
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

    function openPayDueModal(id, due, supplierName, netTotal, alreadyPaid) {
        currentDueVal = due;
        $('#pay_id_pembelian').val(id);
        $('#pay_supplier_name').text(supplierName);
        $('#pay_net_total').text('Rs ' + Number(netTotal).toLocaleString());
        $('#pay_already_paid').text('Rs ' + Number(alreadyPaid).toLocaleString());
        $('#pay_due_amount').text('Rs ' + Number(due).toLocaleString());
        $('#amount_paid').val(due).attr('max', due);
        $('#modal-pay-supplier').modal('show');
    }

    function setPayFull() {
        $('#amount_paid').val(currentDueVal);
    }

    function addForm() {
        $('#modal-supplier').modal('show');
    }

    function showDetail(url, id) {
        $('#modal-detail').modal('show');
        if (id) {
            $('#detailPurchasePoNum').text('#PO-' + String(id).padStart(4, '0'));
            $('#btnDetailPrintThermal').attr('href', `{{ url('/pembelian') }}/${id}/nota-kecil`);
        }
        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        showConfirmDialog('Delete Purchase Order?', 'Are you sure you want to delete this purchase transaction?', 'Yes, delete', function() {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    showSuccessToast('Purchase order deleted successfully');
                    table.ajax.reload();
                })
                .fail((errors) => {
                    showErrorToast('Unable to delete purchase data');
                });
        });
    }
</script>
@endpush