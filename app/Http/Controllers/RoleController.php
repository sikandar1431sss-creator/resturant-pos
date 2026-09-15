<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    /**
     * Core system roles that cannot be deleted.
     */
    protected $coreRoles = ['admin', 'manager', 'cashier', 'kitchen', 'waiter'];

    /**
     * Display clean roles table.
     */
    public function index()
    {
        $this->ensurePermissionsSynced();
        $roles = Role::withCount('users', 'permissions')->orderBy('id', 'asc')->get();
        return view('role.index', compact('roles'));
    }

    /**
     * Return JSON data for DataTables
     */
    public function data()
    {
        $roles = Role::withCount('users', 'permissions')->orderBy('id', 'asc')->get();
        $coreRoles = $this->coreRoles;
        $totalPerms = Permission::count();

        return datatables()
            ->of($roles)
            ->addIndexColumn()
            ->addColumn('role_name', function ($role) use ($coreRoles) {
                $name = ucfirst(str_replace('_', ' ', $role->name));
                $isCore = in_array(strtolower($role->name), $coreRoles);
                
                $badge = '';
                if ($role->name === 'admin') {
                    $badge = '<span class="label label-danger">Admin (Full Access)</span>';
                } elseif ($role->name === 'manager') {
                    $badge = '<span class="label label-warning">Manager</span>';
                } elseif ($role->name === 'cashier') {
                    $badge = '<span class="label label-success">Cashier</span>';
                } elseif ($role->name === 'kitchen') {
                    $badge = '<span class="label" style="background:#ea580c; color:#fff;">Kitchen KDS</span>';
                } elseif ($role->name === 'waiter') {
                    $badge = '<span class="label label-info">Waiter</span>';
                } else {
                    $badge = '<span class="label label-primary">Custom Role</span>';
                }

                return '<strong style="font-size:14px; color:#1e293b;">' . htmlspecialchars($name) . '</strong> &nbsp; ' . $badge;
            })
            ->addColumn('users_count', function ($role) {
                return '<span class="badge" style="background:#e2e8f0; color:#334155; font-size:12px; padding:5px 10px; border-radius:4px;"><i class="fa fa-users"></i> ' . $role->users_count . ' Staff</span>';
            })
            ->addColumn('permissions_count', function ($role) use ($totalPerms) {
                if ($role->name === 'admin') {
                    return '<span class="label label-success" style="font-size:12px; padding:4px 8px;"><i class="fa fa-check-circle"></i> All Permissions (Full Access)</span>';
                }
                return '<span style="font-weight:600; font-size:13px; color:#475569;">' . $role->permissions_count . ' / ' . $totalPerms . ' Allowed</span>';
            })
            ->addColumn('aksi', function ($role) use ($coreRoles) {
                $isCore = in_array(strtolower($role->name), $coreRoles);
                $permUrl = route('role.permissions', $role->id);
                $editUrl = route('role.show', $role->id);
                $updateUrl = route('role.update', $role->id);
                $deleteUrl = route('role.destroy', $role->id);

                $deleteBtn = '';
                if (! $isCore) {
                    $deleteBtn = '<button type="button" onclick="deleteRole(`' . $deleteUrl . '`)" class="btn btn-xs btn-danger btn-flat" title="Delete Role"><i class="fa fa-trash"></i> Delete</button>';
                }

                $editNameBtn = '';
                if (! $isCore) {
                    $editNameBtn = '<button type="button" onclick="editRoleName(`' . $editUrl . '`, `' . $updateUrl . '`)" class="btn btn-xs btn-default btn-flat" title="Edit Role Name"><i class="fa fa-pencil"></i> Rename</button>';
                }

                return '
                <div style="display:flex; gap:6px; align-items:center;">
                    <a href="' . $permUrl . '" class="btn btn-xs btn-primary btn-flat" style="background:#ea580c; border-color:#ea580c; font-weight:700;">
                        <i class="fa fa-key"></i> Permissions
                    </a>
                    ' . $editNameBtn . '
                    ' . $deleteBtn . '
                </div>
                ';
            })
            ->rawColumns(['role_name', 'users_count', 'permissions_count', 'aksi'])
            ->make(true);
    }

    /**
     * Dedicated Permissions Page for a specific role
     */
    public function permissions($id)
    {
        $this->ensurePermissionsSynced();
        $role = Role::with('permissions')->findOrFail($id);
        $permissionGroups = config('permissions.groups', []);
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('role.permissions', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    /**
     * Save Permissions from dedicated page
     */
    public function updatePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $request->permissions ?? [];

        if ($role->name === 'admin') {
            $role->syncPermissions(Permission::all());
        } else {
            $role->syncPermissions($permissions);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('role.permissions', $role->id)->with('success', 'Permissions for "' . ucfirst($role->name) . '" updated successfully.');
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
        ]);

        $roleName = strtolower(trim(str_replace(' ', '_', $request->name)));

        $role = Role::create([
            'name' => $roleName,
            'guard_name' => 'web'
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return response()->json([
            'status' => 'success',
            'message' => 'Role "' . ucfirst($role->name) . '" created successfully!',
            'redirect' => route('role.permissions', $role->id)
        ], 200);
    }

    /**
     * Display role info for rename modal.
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        
        return response()->json([
            'id' => $role->id,
            'name' => $role->name,
            'is_core' => in_array(strtolower($role->name), $this->coreRoles)
        ]);
    }

    /**
     * Update role name.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $isCore = in_array(strtolower($role->name), $this->coreRoles);

        if (!$isCore) {
            $request->validate([
                'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            ]);
            $role->name = strtolower(trim(str_replace(' ', '_', $request->name)));
            $role->save();
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully!'
        ], 200);
    }

    /**
     * Remove custom role.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if (in_array(strtolower($role->name), $this->coreRoles)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Core system roles cannot be deleted.'
            ], 422);
        }

        if ($role->users()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete role with ' . $role->users()->count() . ' assigned users. Please reassign the staff first.'
            ], 422);
        }

        $role->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully!'
        ], 200);
    }

    /**
     * Helper to auto-sync config permissions to the database.
     */
    protected function ensurePermissionsSynced()
    {
        $groups = config('permissions.groups', []);
        foreach ($groups as $group) {
            if (isset($group['permissions']) && is_array($group['permissions'])) {
                foreach ($group['permissions'] as $permKey => $meta) {
                    Permission::firstOrCreate(['name' => $permKey, 'guard_name' => 'web']);
                }
            }
        }
        Permission::firstOrCreate(['name' => 'sales.view', 'guard_name' => 'web']);
    }
}
