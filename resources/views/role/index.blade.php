@extends('layouts.master')

@section('title')
    Roles & Permissions
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Roles & Permissions</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box box-default" style="border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: none;">
            <div class="box-header with-border" style="padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 class="box-title" style="font-weight: 700; font-size: 16px; color: #0f172a; margin: 0;">
                        System Roles & Access Control
                    </h3>
                    <p style="margin: 3px 0 0 0; color: #64748b; font-size: 13px;">
                        Manage staff roles and click the <strong>Permissions</strong> button to customize module access for each role.
                    </p>
                </div>
                <div class="box-tools">
                    <button onclick="addRoleModal('{{ route('role.store') }}')" class="btn btn-primary btn-flat" style="background: #ea580c; border-color: #ea580c; border-radius: 4px; font-weight: 700;">
                        <i class="fa fa-plus-circle"></i> Add New Role
                    </button>
                </div>
            </div>

            <div class="box-body table-responsive" style="padding: 16px 20px;">
                <table class="table table-bordered table-hover" id="role-table" style="width: 100%;">
                    <thead style="background: #f8fafc;">
                        <th width="5%">#</th>
                        <th width="30%">Role Name</th>
                        <th width="20%">Assigned Staff</th>
                        <th width="25%">Active Permissions</th>
                        <th width="20%"><i class="fa fa-cog"></i> Actions</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Create / Rename Role -->
<div class="modal fade" id="modal-role" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width: 480px;">
        <form action="" method="post" id="roleSimpleForm">
            @csrf
            @method('post')
            <div class="modal-content" style="border-radius: 8px;">
                <div class="modal-header" style="background: #0f172a; color: #fff; padding: 14px 18px;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title" style="font-size: 16px; font-weight: 700; margin: 0;"></h4>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <div class="form-group">
                        <label for="role_name" style="font-weight: 600; color: #1e293b;">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="role_name" class="form-control" style="border-radius: 4px;" required placeholder="e.g. Supervisor, Shift Incharge">
                        <small class="text-muted" style="margin-top: 4px; display: block;">After creating the role, you can immediately set its permissions.</small>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; padding: 12px 18px;">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-flat" id="btn-save-simple-role" style="background: #ea580c; border-color: #ea580c; font-weight: 700;">
                        Save Role
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let roleTable;

    $(function () {
        roleTable = $('#role-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('role.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'role_name'},
                {data: 'users_count'},
                {data: 'permissions_count'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#roleSimpleForm').on('submit', function (e) {
            e.preventDefault();
            let $btn = $('#btn-save-simple-role');
            $btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: $('#roleSimpleForm').attr('action'),
                type: $('#roleSimpleForm [name=_method]').val() || 'POST',
                data: $('#roleSimpleForm').serialize(),
                dataType: 'json'
            })
            .done((response) => {
                $('#modal-role').modal('hide');
                showSuccessToast(response.message || 'Role saved successfully');
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    roleTable.ajax.reload();
                }
            })
            .fail((xhr) => {
                let msg = 'Unable to save role';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                showErrorToast(msg);
            })
            .always(() => {
                $btn.prop('disabled', false).text('Save Role');
            });
        });
    });

    function addRoleModal(url) {
        $('#modal-role').modal('show');
        $('#modal-role .modal-title').text('Create New Role');
        $('#roleSimpleForm')[0].reset();
        $('#roleSimpleForm').attr('action', url);
        $('#roleSimpleForm [name=_method]').val('post');
        $('#role_name').focus();
    }

    function editRoleName(showUrl, updateUrl) {
        $('#modal-role').modal('show');
        $('#modal-role .modal-title').text('Rename Role');
        $('#roleSimpleForm')[0].reset();
        $('#roleSimpleForm').attr('action', updateUrl);
        $('#roleSimpleForm [name=_method]').val('put');

        $.get(showUrl)
            .done((response) => {
                $('#role_name').val(response.name);
                $('#role_name').focus();
            })
            .fail(() => {
                showErrorToast('Unable to load role info');
            });
    }

    function deleteRole(url) {
        showConfirmDialog('Delete Role?', 'Are you sure you want to delete this custom role?', 'Yes, delete', function() {
            $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done((response) => {
                showSuccessToast(response.message || 'Role deleted');
                roleTable.ajax.reload();
            })
            .fail((xhr) => {
                let msg = 'Unable to delete role';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                showErrorToast(msg);
            });
        });
    }
</script>
@endpush
