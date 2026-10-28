@extends('layouts.master')

@section('title')
    Customers
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Customers</li>
@endsection

@push('css')
<style>
    .table-actions-group {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        flex-wrap: nowrap !important;
        vertical-align: middle !important;
    }
    .btn-table-action {
        width: 32px !important;
        height: 32px !important;
        min-width: 32px !important;
        border-radius: 6px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 13px !important;
        border: 1px solid transparent !important;
        transition: background-color 0.15s ease, filter 0.15s ease !important;
        cursor: pointer !important;
        box-shadow: none !important;
        transform: none !important;
    }
    .btn-table-action:hover {
        transform: none !important;
        box-shadow: none !important;
        filter: brightness(0.92) !important;
    }
    .btn-table-action i,
    .btn-table-action .fa {
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
        color: #ffffff !important;
    }
    .btn-table-action.btn-edit {
        background-color: #f59e0b !important;
        background: #f59e0b !important;
        color: #ffffff !important;
        border-color: #f59e0b !important;
    }
    .btn-table-action.btn-edit:hover,
    .btn-table-action.btn-edit:focus {
        background-color: #d97706 !important;
        background: #d97706 !important;
        color: #ffffff !important;
        border-color: #d97706 !important;
    }
    .btn-table-action.btn-delete {
        background-color: #ef4444 !important;
        background: #ef4444 !important;
        color: #ffffff !important;
        border-color: #ef4444 !important;
    }
    .btn-table-action.btn-delete:hover,
    .btn-table-action.btn-delete:focus {
        background-color: #dc2626 !important;
        background: #dc2626 !important;
        color: #ffffff !important;
        border-color: #dc2626 !important;
    }
    /* Prevent table responsive scrollbar jump/overflow on hover */
    .box-body.table-responsive {
        overflow-x: auto !important;
        overflow-y: hidden !important;
        padding-bottom: 8px !important;
    }
    .table > tbody > tr > td {
        vertical-align: middle !important;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="box-header-actions" style="display: inline-flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <button onclick="addForm('{{ route('member.store') }}')" class="btn btn-success btn-flat" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-plus-circle"></i> Add New Customer</button>
                    <button onclick="cetakMember('{{ route('member.cetak_member') }}')" class="btn btn-primary btn-flat" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-id-card"></i> Download Cards</button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-member">
                    @csrf
                    <table class="table table-striped table-bordered table-hover">
                        <thead style="background: #f8fafc;">
                            <th width="4%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="4%">#</th>
                            <th width="10%">Code</th>
                            <th>Customer Name</th>
                            <th>Telephone</th>
                            <th>Address</th>
                            <th width="8%">Orders</th>
                            <th width="14%">Due Status</th>
                            <th width="18%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
@includeIf('member.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('member.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_member'},
                {data: 'nama'},
                {data: 'telepon'},
                {data: 'alamat'},
                {data: 'orders_count'},
                {data: 'due_balance'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        showSuccessToast('Customer saved successfully');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        showErrorToast('Unable to save customer data');
                        return;
                    });
            }
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Add Customer');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Customer');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama]').val(response.nama);
                $('#modal-form [name=telepon]').val(response.telepon);
                $('#modal-form [name=alamat]').val(response.alamat);
            })
            .fail((errors) => {
                showErrorToast('Unable to display customer data');
                return;
            });
    }

    function deleteData(url) {
        showConfirmDialog('Delete Customer?', 'Are you sure you want to delete this customer record?', 'Yes, delete', function() {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    showSuccessToast('Customer deleted successfully');
                    table.ajax.reload();
                })
                .fail((errors) => {
                    showErrorToast('Unable to delete customer');
                });
        });
    }

    function cetakMember(url) {
        if ($('input:checked').length < 1) {
            showWarningToast('Select at least one customer to print cards');
            return;
        } else {
            $('.form-member')
                .attr('target', '_blank')
                .attr('action', url)
                .submit();
        }
    }
</script>
@endpush