@extends('layouts.master')

@section('title')
    Invoices & Sales List
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Sales Invoices</li>
@endsection

@push('css')
<style>
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

    /* Larger, Prominent Table Action Buttons */
    .table-actions-group {
        display: inline-flex !important;
        align-items: center !important;
        gap: 10px !important;
        flex-wrap: nowrap !important;
    }

    .btn-table-action {
        width: 36px !important;
        height: 36px !important;
        min-width: 36px !important;
        border-radius: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 14px !important;
        border: 1px solid transparent !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer !important;
        text-decoration: none !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
        padding: 0 !important;
    }

    .btn-table-action:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.18) !important;
    }

    .btn-table-action:active {
        transform: scale(0.95) !important;
    }

    .btn-table-action.btn-pay {
        width: auto !important;
        min-width: auto !important;
        height: 36px !important;
        padding: 0 13px !important;
        font-size: 12.5px !important;
        font-weight: 700 !important;
        gap: 6px !important;
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
        border-color: #94a3b8 !important;
    }

    .btn-table-action.btn-edit,
    .btn-table-action.btn-edit i {
        background-color: #2563eb !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
    }
    .btn-table-action.btn-edit:hover,
    .btn-table-action.btn-edit:hover i {
        background-color: #1d4ed8 !important;
        border-color: #1d4ed8 !important;
        color: #ffffff !important;
    }

    .btn-table-action.btn-view,
    .btn-table-action.btn-view i {
        background-color: #0284c7 !important;
        border-color: #0284c7 !important;
        color: #ffffff !important;
    }
    .btn-table-action.btn-view:hover,
    .btn-table-action.btn-view:hover i {
        background-color: #0369a1 !important;
        border-color: #0369a1 !important;
        color: #ffffff !important;
    }

    .btn-table-action.btn-delete,
    .btn-table-action.btn-delete i {
        background-color: #ef4444 !important;
        border-color: #ef4444 !important;
        color: #ffffff !important;
    }
    .btn-table-action.btn-delete:hover,
    .btn-table-action.btn-delete:hover i {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h3 class="box-title" style="font-weight: 800; color: #0f172a;"><i class="fa fa-file-text-o text-primary"></i> Customer Invoices</h3>
                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">Invoices are saved as Unpaid first. Click "Pay Now" when customer pays to settle and print receipt in a new tab.</div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('transaksi.baru') }}" class="btn btn-sm btn-primary btn-flat" style="font-weight: 700; border-radius: 6px;"><i class="fa fa-plus-circle"></i> Create New Invoice</a>
                </div>
            </div>
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
                        <th>Cashier</th>
                        <th width="18%"><i class="fa fa-cog"></i> Action</th>
                    </thead>
                </table>
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

@includeIf('penjualan.detail')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('penjualan.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
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

        $.post(`{{ url('/penjualan') }}/${id}/settle`, {
            '_token': $('[name=csrf-token]').attr('content'),
            'metode_pembayaran': method
        })
        .done(response => {
            $('#modal-pay-invoice').modal('hide');
            table.ajax.reload();

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
                if (res.isConfirmed) {
                    window.open(response.print_url, '_blank');
                }
            });
        })
        .fail(errors => {
            let msg = errors.responseJSON ? (errors.responseJSON.message || errors.responseJSON.error) : 'Failed to mark invoice as paid';
            showErrorToast(msg);
        });
    }

    function showDetail(url) {
        $('#modal-detail').modal('show');
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
                })
                .fail((errors) => {
                    showErrorToast('Unable to delete transaction record');
                });
        });
    }
</script>
@endpush