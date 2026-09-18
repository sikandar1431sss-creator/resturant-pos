<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Fetch all permissions defined in config/permissions.php
        $groups = config('permissions.groups', []);
        $allPermissionNames = [];

        foreach ($groups as $groupKey => $group) {
            if (isset($group['permissions']) && is_array($group['permissions'])) {
                foreach ($group['permissions'] as $permKey => $permMeta) {
                    $allPermissionNames[] = $permKey;
                    Permission::firstOrCreate(['name' => $permKey, 'guard_name' => 'web']);
                }
            }
        }

        // Backward compatibility permissions
        $legacyPerms = ['sales.view'];
        foreach ($legacyPerms as $legacy) {
            Permission::firstOrCreate(['name' => $legacy, 'guard_name' => 'web']);
        }

        // 3. Create Core Fast-Food Roles and Sync Defaults

        // --- ADMIN / OWNER (Full Access) ---
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // --- BRANCH MANAGER (Store Operations, Reports, Cashier Oversight) ---
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $managerRole->syncPermissions([
            'pos.access',
            'pos.create_order',
            'pos.settle_payment',
            'pos.apply_discount',
            'pos.drafts',
            'pos.print_bill',
            'pos.print_kot',
            'pos.cancel_order',
            'kitchen.access',
            'kitchen.manage_orders',
            'sales.view_all',
            'sales.view_own',
            'sales.view',
            'sales.edit',
            'sales.delete',
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'deals.view',
            'deals.manage',
            'categories.view',
            'categories.manage',
            'raw_materials.view',
            'raw_materials.manage',
            'recipes.manage',
            'tables.manage',
            'purchases.view',
            'purchases.manage',
            'suppliers.manage',
            'expenses.view',
            'expenses.manage',
            'members.manage',
            'reports.view',
            'reports.export',
            'users.manage',
        ]);

        // --- CASHIER / COUNTER STAFF (Counter Billing & Own Shift Invoices) ---
        $cashierRole = Role::firstOrCreate(['name' => 'cashier', 'guard_name' => 'web']);
        $cashierRole->syncPermissions([
            'pos.access',
            'pos.create_order',
            'pos.settle_payment',
            'pos.apply_discount',
            'pos.drafts',
            'pos.print_bill',
            'pos.print_kot',
            'kitchen.access',
            'sales.view_own',
            'members.manage',
        ]);

        // --- KITCHEN CHEF (KDS Screen & Bump Timers) ---
        $kitchenRole = Role::firstOrCreate(['name' => 'kitchen', 'guard_name' => 'web']);
        $kitchenRole->syncPermissions([
            'kitchen.access',
            'kitchen.manage_orders',
            'pos.print_kot',
        ]);

        // --- DINE-IN / FRONT WAITER (Order Taking, Table Drafts, KOT) ---
        $waiterRole = Role::firstOrCreate(['name' => 'waiter', 'guard_name' => 'web']);
        $waiterRole->syncPermissions([
            'pos.access',
            'pos.create_order',
            'pos.drafts',
            'pos.print_kot',
            'pos.print_bill',
            'sales.view_own',
            'kitchen.access',
        ]);

        // 4. Assign Admin Role to Primary User
        $adminUser = User::where('email', 'admin@mail.com')->orWhere('level', 1)->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }
    }
}
