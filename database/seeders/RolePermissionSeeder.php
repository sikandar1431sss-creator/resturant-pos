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
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define All Permissions
        $permissions = [
            // POS & Sales
            'pos.access',
            'pos.create_order',
            'pos.print_kot',
            'pos.print_bill',
            'pos.drafts',
            'pos.settle_payment',
            'pos.cancel_order',
            'pos.apply_discount',
            
            // Sales History
            'sales.view',
            'sales.delete',

            // Products & Categories
            'categories.view',
            'categories.manage',
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',

            // Purchases & Inventory
            'purchases.view',
            'purchases.manage',
            'suppliers.manage',

            // Expenses
            'expenses.view',
            'expenses.manage',

            // Customers / Members
            'members.manage',

            // Reports
            'reports.view',
            'reports.export',

            // Users & Settings
            'users.manage',
            'settings.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // 2. Create Roles and Assign Permissions

        // --- Role: Admin (Super Administrator / Owner) ---
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // --- Role: Manager (Branch Manager / Floor Incharge) ---
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $managerRole->syncPermissions([
            'pos.access',
            'pos.create_order',
            'pos.print_kot',
            'pos.print_bill',
            'pos.drafts',
            'pos.settle_payment',
            'pos.cancel_order',
            'pos.apply_discount',
            'sales.view',
            'categories.view',
            'products.view',
            'purchases.view',
            'purchases.manage',
            'suppliers.manage',
            'expenses.view',
            'expenses.manage',
            'members.manage',
            'reports.view',
        ]);

        // --- Role: Cashier / Staff (Counter Billing & Order Taking) ---
        $cashierRole = Role::firstOrCreate(['name' => 'cashier', 'guard_name' => 'web']);
        $cashierRole->syncPermissions([
            'pos.access',
            'pos.create_order',
            'pos.print_kot',
            'pos.print_bill',
            'pos.drafts',
            'pos.settle_payment',
            'sales.view',
            'members.manage',
        ]);

        // --- Role: Waiter (Optional for Dine-In Table Order Punching) ---
        $waiterRole = Role::firstOrCreate(['name' => 'waiter', 'guard_name' => 'web']);
        $waiterRole->syncPermissions([
            'pos.access',
            'pos.create_order',
            'pos.print_kot',
            'pos.drafts',
        ]);

        // 3. Assign Default Roles to existing users
        $adminUser = User::where('email', 'admin@mail.com')->orWhere('level', 1)->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        $otherUsers = User::where('id', '!=', optional($adminUser)->id)->get();
        foreach ($otherUsers as $user) {
            if ($user->level == 2) {
                $user->assignRole('cashier');
            }
        }
    }
}
