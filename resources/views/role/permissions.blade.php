@extends('layouts.master')

@section('title')
    Permissions: {{ ucfirst(str_replace('_', ' ', $role->name)) }}
@endsection

@section('breadcrumb')
    @parent
    <li><a href="{{ route('role.index') }}">Roles & Permissions</a></li>
    <li class="active">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</li>
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible" style="border-radius: 6px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <i class="fa fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<form action="{{ route('role.permissions.update', $role->id) }}" method="POST" id="permissionsForm">
    @csrf
    @method('POST')

    <!-- Top Action Bar -->
    <div class="box box-default" style="border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
        <div class="box-body" style="padding: 16px 20px;">
            <div class="row" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                <div class="col-md-6 col-sm-12" style="margin-bottom: 8px;">
                    <a href="{{ route('role.index') }}" class="btn btn-default btn-flat" style="border-radius: 4px; margin-right: 12px;">
                        <i class="fa fa-arrow-left"></i> Back to Roles
                    </a>
                    <span style="font-size: 18px; font-weight: 700; color: #0f172a; vertical-align: middle;">
                        Role: <span class="text-primary">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                    </span>
                    @if($role->name === 'admin')
                        <span class="label label-danger" style="font-size: 11px; margin-left: 6px;">SuperAdmin (Always has all rights)</span>
                    @endif
                </div>

                <div class="col-md-6 col-sm-12 text-right">
                    <button type="button" class="btn btn-sm btn-default btn-flat" onclick="toggleAll(true)" style="border-radius: 4px; margin-right: 4px;">
                        <i class="fa fa-check-square-o"></i> Check All
                    </button>
                    <button type="button" class="btn btn-sm btn-default btn-flat" onclick="toggleAll(false)" style="border-radius: 4px; margin-right: 12px;">
                        <i class="fa fa-square-o"></i> Uncheck All
                    </button>
                    <button type="submit" class="btn btn-success btn-flat" style="background: #16a34a; border-color: #16a34a; border-radius: 4px; font-weight: 700; padding: 6px 20px;">
                        <i class="fa fa-save"></i> Save Permissions
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Grid -->
    <div class="row">
        @foreach($permissionGroups as $groupKey => $group)
            <div class="col-md-6 col-sm-12" style="margin-bottom: 20px;">
                <div class="box box-solid" style="border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: none; height: calc(100% - 20px); display: flex; flex-direction: column;">
                    <!-- Card Header -->
                    <div class="box-header with-border" style="background: #f8fafc; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
                        <h4 class="box-title" style="font-weight: 700; font-size: 15px; color: #1e293b; margin: 0;">
                            {{ $group['title'] }}
                        </h4>
                        <button type="button" class="btn btn-xs btn-default" onclick="toggleGroup('{{ $groupKey }}')" style="font-size: 11px; border-radius: 3px;">
                            Toggle Group
                        </button>
                    </div>

                    <!-- Card Body / Permissions List -->
                    <div class="box-body" style="padding: 14px 16px; flex: 1;">
                        <p class="text-muted" style="font-size: 12px; margin-bottom: 12px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 8px;">
                            {{ $group['description'] }}
                        </p>

                        <div class="permissions-list">
                            @foreach($group['permissions'] as $permKey => $perm)
                                @php
                                    $isChecked = in_array($permKey, $rolePermissions) || $role->name === 'admin';
                                @endphp
                                <div class="permission-item" style="margin-bottom: 10px; padding: 8px 10px; background: #ffffff; border: 1px solid #f1f5f9; border-radius: 6px;">
                                    <label for="perm_{{ str_replace('.', '_', $permKey) }}" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; margin: 0; font-weight: normal;">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permKey }}" 
                                               id="perm_{{ str_replace('.', '_', $permKey) }}" 
                                               class="perm-checkbox group-{{ $groupKey }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               style="margin-top: 3px; cursor: pointer;">
                                        <div style="flex: 1;">
                                            <strong style="color: #0f172a; font-size: 13px;">{{ $perm['label'] }}</strong>
                                            <div style="font-size: 12px; color: #64748b; margin-top: 1px;">
                                                {{ $perm['desc'] }}
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Bottom Action Bar -->
    <div class="box box-default" style="border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 10px;">
        <div class="box-body text-right" style="padding: 14px 20px;">
            <a href="{{ route('role.index') }}" class="btn btn-default btn-flat" style="border-radius: 4px; margin-right: 8px;">
                Cancel
            </a>
            <button type="submit" class="btn btn-success btn-flat" style="background: #16a34a; border-color: #16a34a; border-radius: 4px; font-weight: 700; padding: 7px 24px;">
                <i class="fa fa-save"></i> Save Permissions
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function toggleAll(status) {
        $('.perm-checkbox').prop('checked', status);
    }

    function toggleGroup(groupKey) {
        let $boxes = $('.group-' + groupKey);
        let allChecked = $boxes.length === $boxes.filter(':checked').length;
        $boxes.prop('checked', !allChecked);
    }
</script>
@endpush
