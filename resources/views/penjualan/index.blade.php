@extends('layouts.master')

@section('title')
    Invoices
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Invoices</li>
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
<div class="row" style="margin-bottom: 16px;">
    <div class="col-xs-12" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="margin: 0; font-weight: 800; color: #0f172a; font-size: 20px;">
                <i class="fa fa-file-text-o text-primary"></i> Customer Invoices
            </h3>
        </div>
        <div>
            @if(auth()->user()->can('pos.create_order') || auth()->user()->can('pos.access') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <a href="{{ route('transaksi.baru') }}" class="btn btn-primary btn-flat" style="font-weight: 800; border-radius: 8px; padding: 9px 20px; font-size: 13.5px; box-shadow: 0 4px 12px rgba(37,99,235,0.25); display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa fa-plus-circle"></i> New Invoice
            </a>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box" style="border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04); overflow: hidden;">
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
                    <i class="fa fa-pencil-square-o text-warning"></i> Quick Edit Invoice <span id="quickEditInvNumber" style="color: #ffaa5a; font-weight: 900;">#INV-00000</span>
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
                })
                .fail((errors) => {
                    showErrorToast('Unable to delete transaction record');
                });
        });
    }
</script>
@endpush