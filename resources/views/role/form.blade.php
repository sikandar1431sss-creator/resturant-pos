<div class="modal fade" id="modal-role-form" tabindex="-1" role="dialog" aria-labelledby="modal-role-form">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: 1050px;">
        <form action="" method="post" class="form-horizontal" id="roleForm">
            @csrf
            @method('post')

            <div class="modal-content" style="border-radius: 12px; overflow: hidden; box-shadow: 0 20px 45px rgba(0,0,0,0.25); border: none;">
                <!-- Header -->
                <div class="modal-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 18px 24px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.8; font-size: 24px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" style="font-weight: 700; font-size: 18px; margin: 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fa fa-shield" style="color: #ea580c;"></i>
                        <span id="modal-role-title">Configure Role & Permissions</span>
                    </h4>
                    <p style="margin: 4px 0 0 0; color: #94a3b8; font-size: 13px;">
                        Select exact system capabilities for this role. Changes take effect instantly for all assigned staff.
                    </p>
                </div>

                <div class="modal-body" style="padding: 24px; background: #f8fafc; max-height: calc(85vh - 160px); overflow-y: auto;">
                    <!-- Role Name Input -->
                    <div class="panel" style="border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.03); background: #ffffff; padding: 16px 20px; margin-bottom: 20px;">
                        <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
                            <label for="role_name_input" class="col-sm-3 control-label" style="text-align: left; font-size: 14px; font-weight: 700; color: #1e293b;">
                                Role Name / Title <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" name="name" id="role_name_input" class="form-control" style="border-radius: 8px; border: 1.5px solid #cbd5e1; font-weight: 600; font-size: 14px; padding: 8px 14px;" required placeholder="e.g. Supervisor, Shift Incharge, Junior Cashier">
                                <small class="text-muted" id="role_name_hint">Short, identifiable name for the staff position.</small>
                            </div>
                            <div class="col-sm-3 text-right">
                                <button type="button" class="btn btn-sm btn-default" onclick="toggleAllModules(true)" style="border-radius: 6px; font-weight: 600; margin-right: 5px;">
                                    <i class="fa fa-check-square-o text-success"></i> Check All
                                </button>
                                <button type="button" class="btn btn-sm btn-default" onclick="toggleAllModules(false)" style="border-radius: 6px; font-weight: 600;">
                                    <i class="fa fa-square-o text-danger"></i> Uncheck All
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Modules Grid -->
                    <div class="permission-groups-container">
                        @foreach($permissionGroups as $groupKey => $group)
                            <div class="permission-module-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); overflow: hidden;">
                                <!-- Module Header -->
                                <div class="module-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 12px 18px; display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span class="module-icon" style="background: {{ $group['color'] ?? '#64748b' }}15; color: {{ $group['color'] ?? '#64748b' }}; width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 16px;">
                                            <i class="fa {{ $group['icon'] ?? 'fa-circle' }}"></i>
                                        </span>
                                        <div>
                                            <h5 style="margin: 0; font-weight: 700; font-size: 14px; color: #0f172a;">
                                                {{ $group['title'] }}
                                                @if(!empty($group['urdu_title']))
                                                    <span style="font-weight: 500; font-size: 12.5px; color: #64748b; margin-left: 6px;">({{ $group['urdu_title'] }})</span>
                                                @endif
                                            </h5>
                                            <small class="text-muted">{{ $group['description'] ?? '' }}</small>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-xs btn-default" onclick="toggleGroupCheckboxes('{{ $groupKey }}')" style="border-radius: 4px; font-weight: 600; font-size: 11px;">
                                            <i class="fa fa-toggle-on text-primary"></i> Toggle Group
                                        </button>
                                    </div>
                                </div>

                                <!-- Module Permissions List -->
                                <div class="module-body" style="padding: 16px 18px;">
                                    <div class="row">
                                        @foreach($group['permissions'] as $permKey => $perm)
                                            <div class="col-md-6 col-sm-12" style="margin-bottom: 12px;">
                                                <label class="permission-item-box" for="perm_{{ str_replace('.', '_', $permKey) }}" style="display: flex; align-items: flex-start; gap: 12px; padding: 10px 14px; background: #fdfdfd; border: 1.5px solid #f1f5f9; border-radius: 8px; cursor: pointer; transition: all 0.15s ease; width: 100%; margin: 0;">
                                                    <input type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permKey }}" 
                                                           id="perm_{{ str_replace('.', '_', $permKey) }}" 
                                                           class="perm-checkbox perm-group-{{ $groupKey }}"
                                                           style="margin-top: 4px; width: 18px; height: 18px; cursor: pointer; accent-color: #ea580c;">
                                                    <div style="flex: 1;">
                                                        <div style="font-weight: 700; font-size: 13px; color: #1e293b;">
                                                            {{ $perm['label'] }}
                                                            @if(str_contains($permKey, 'delete'))
                                                                <span class="label label-danger" style="font-size: 9px; margin-left: 4px;">High Risk</span>
                                                            @elseif(str_contains($permKey, 'view_all'))
                                                                <span class="label label-primary" style="font-size: 9px; margin-left: 4px;">Store Wide</span>
                                                            @elseif(str_contains($permKey, 'view_own'))
                                                                <span class="label label-info" style="font-size: 9px; margin-left: 4px;">Own Shift</span>
                                                            @endif
                                                        </div>
                                                        <div style="font-size: 11.5px; color: #64748b; margin-top: 2px; line-height: 1.35;">
                                                            {{ $perm['desc'] }}
                                                        </div>
                                                        @if(!empty($perm['urdu']))
                                                            <div style="font-size: 11.5px; color: #ea580c; margin-top: 3px; font-weight: 500;">
                                                                <i class="fa fa-info-circle"></i> {{ $perm['urdu'] }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer" style="background: #ffffff; border-top: 1px solid #e2e8f0; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                    <div class="text-muted" style="font-size: 12.5px;">
                        <i class="fa fa-info-circle text-primary"></i> <span id="selected-perms-count">0</span> permissions selected
                    </div>
                    <div>
                        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" style="border-radius: 6px; padding: 8px 18px; font-weight: 600;">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-flat" id="btn-save-role" style="background: #ea580c; border-color: #ea580c; border-radius: 6px; padding: 8px 24px; font-weight: 700;">
                            <i class="fa fa-save"></i> Save Permissions
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.permission-item-box:hover {
    border-color: #cbd5e1 !important;
    background: #f8fafc !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}
.permission-item-box input[type="checkbox"]:checked + div {
    color: #0f172a;
}
</style>
