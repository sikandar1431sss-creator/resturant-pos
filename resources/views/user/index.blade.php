@extends('layouts.master')

@section('title')
    Staff & Users Management
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Staff Users</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <div class="box-header with-border" style="padding: 18px 22px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 class="box-title" style="font-weight: 800; font-size: 16px; color: #0f172a;">
                        <i class="fa fa-users text-primary"></i> Restaurant Staff Accounts
                    </h3>
                    <p style="margin: 2px 0 0 0; color: #64748b; font-size: 13px;">
                        Manage cashiers, managers, kitchen chefs, waiters, and custom staff roles.
                    </p>
                </div>
                <div class="box-tools">
                    <a href="{{ route('role.index') }}" class="btn btn-default btn-flat" style="border-radius: 6px; font-weight: 600; margin-right: 8px;">
                        <i class="fa fa-shield text-warning"></i> Manage Roles & Permissions
                    </a>
                    <button onclick="addForm('{{ route('user.store') }}')" class="btn btn-primary btn-flat" style="background: #ea580c; border-color: #ea580c; border-radius: 6px; font-weight: 700;">
                        <i class="fa fa-plus-circle"></i> Add New Staff User
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive" style="padding: 18px 22px;">
                <table class="table table-bordered table-hover" id="user-datatable" style="width: 100%;">
                    <thead style="background: #f8fafc;">
                        <th width="5%">#</th>
                        <th width="25%">Staff Name</th>
                        <th width="30%">Email / Login</th>
                        <th width="25%">Assigned Role & Level</th>
                        <th width="15%"><i class="fa fa-cog"></i> Actions</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('user.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('#user-datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('user.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'name'},
                {data: 'email'},
                {data: 'role_badge'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#userForm').on('submit', function (e) {
            e.preventDefault();
            let $btn = $('#btn-save-user');
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: $('#userForm').attr('action'),
                type: $('#userForm [name=_method]').val() || 'POST',
                data: $('#userForm').serialize(),
                dataType: 'json'
            })
            .done((response) => {
                $('#modal-form').modal('hide');
                showSuccessToast(response.message || 'Staff user saved successfully');
                table.ajax.reload();
            })
            .fail((xhr) => {
                let msg = 'Unable to save user';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors)[0][0];
                }
                showErrorToast(msg);
            })
            .always(() => {
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Staff User');
            });
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Add New Staff User');

        $('#userForm')[0].reset();
        $('#userForm').attr('action', url);
        $('#userForm [name=_method]').val('post');
        $('#name').focus();

        $('#password, #password_confirmation').attr('required', true);
        $('#password-help').text('Password is required for new staff accounts.');
    }

    function editForm(showUrl, updateUrl) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Staff User');

        $('#userForm')[0].reset();
        $('#userForm').attr('action', updateUrl);
        $('#userForm [name=_method]').val('put');
        $('#name').focus();

        $('#password, #password_confirmation').attr('required', false);
        $('#password-help').text('Leave blank to keep existing password.');

        $.get(showUrl)
            .done((response) => {
                $('#name').val(response.name);
                $('#email').val(response.email);
                $('#role').val(response.role || 'cashier');
            })
            .fail((errors) => {
                showErrorToast('Unable to load user data');
            });
    }

    function deleteData(url) {
        showConfirmDialog('Delete User Account?', 'Are you sure you want to delete this staff user?', 'Yes, delete', function() {
            $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done((response) => {
                showSuccessToast(response.message || 'Staff user deleted');
                table.ajax.reload();
            })
            .fail((xhr) => {
                let msg = 'Unable to delete user';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                showErrorToast(msg);
            });
        });
    }
</script>
@endpush