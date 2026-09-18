<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class WorkflowDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Reset Spatie Cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Define All Permissions
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

            // Kitchen Display System (KDS)
            'kitchen.access',
            'kitchen.manage_orders',

            // Sales History
            'sales.view',
            'sales.delete',

            // Products & Categories & Deals
            'categories.view',
            'categories.manage',
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'deals.view',
            'deals.manage',

            // Purchases & Inventory
            'purchases.view',
            'purchases.manage',
            'suppliers.manage',

            // Expenses
            'expenses.view',
            'expenses.manage',

            // Customers / Members
            'members.manage',

            // Analytics & Reports
            'reports.view',
            'reports.export',

            // Users & Settings
            'users.manage',
            'settings.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // 3. Create / Sync Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

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
            'kitchen.access',
            'kitchen.manage_orders',
            'sales.view_all',
            'sales.view_own',
            'sales.view',
            'sales.edit',
            'sales.delete',
            'categories.view',
            'categories.manage',
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'deals.view',
            'deals.manage',
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

        $cashierRole = Role::firstOrCreate(['name' => 'cashier', 'guard_name' => 'web']);
        $cashierRole->syncPermissions([
            'pos.access',
            'pos.create_order',
            'pos.print_kot',
            'pos.print_bill',
            'pos.drafts',
            'pos.settle_payment',
            'kitchen.access',
            'sales.view',
            'members.manage',
        ]);

        $kitchenRole = Role::firstOrCreate(['name' => 'kitchen', 'guard_name' => 'web']);
        $kitchenRole->syncPermissions([
            'kitchen.access',
            'kitchen.manage_orders',
            'pos.print_kot',
        ]);

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

        // 4. Create Workflow User Accounts
        $usersData = [
            [
                'name' => 'Ali Hassan (Admin & Owner)',
                'email' => 'admin@mail.com',
                'password' => Hash::make('password123'),
                'level' => 1,
                'role' => 'admin',
            ],
            [
                'name' => 'Zubair Khan (Branch Manager)',
                'email' => 'manager@mail.com',
                'password' => Hash::make('password123'),
                'level' => 2,
                'role' => 'manager',
            ],
            [
                'name' => 'Hamza Ahmed (Counter Cashier)',
                'email' => 'cashier@mail.com',
                'password' => Hash::make('password123'),
                'level' => 2,
                'role' => 'cashier',
            ],
            [
                'name' => 'Chef Tariq (Kitchen Display)',
                'email' => 'kitchen@mail.com',
                'password' => Hash::make('password123'),
                'level' => 2,
                'role' => 'kitchen',
            ],
            [
                'name' => 'Bilal Raza (Dine-In Waiter)',
                'email' => 'waiter@mail.com',
                'password' => Hash::make('password123'),
                'level' => 2,
                'role' => 'waiter',
            ],
        ];

        $createdUsers = [];
        foreach ($usersData as $u) {
            $user = User::where('email', $u['email'])->first();
            if (!$user) {
                $user = new User();
                $user->email = $u['email'];
            }
            $user->name = $u['name'];
            $user->password = $u['password'];
            $user->level = $u['level'];
            $user->save();

            $user->syncRoles([$u['role']]);
            $createdUsers[$u['role']] = $user;
        }

        // 5. Ensure Categories & Products exist
        $catBurger = Kategori::firstOrCreate(['nama_kategori' => 'Burgers & Sandwiches']);
        $catFries = Kategori::firstOrCreate(['nama_kategori' => 'Fries & Sides']);
        $catDrinks = Kategori::firstOrCreate(['nama_kategori' => 'Cold Drinks & Shakes']);
        $catDeals = Kategori::firstOrCreate(['nama_kategori' => 'Deals & Combos']);

        $p1 = Produk::firstOrCreate(
            ['kode_produk' => 'PRD-0001'],
            ['id_kategori' => $catBurger->id_kategori, 'nama_produk' => 'Crispy Zinger Burger', 'merk' => 'Kitchen', 'harga_beli' => 250, 'harga_jual' => 450, 'diskon' => 0, 'stok' => 100]
        );

        $p2 = Produk::firstOrCreate(
            ['kode_produk' => 'PRD-0002'],
            ['id_kategori' => $catBurger->id_kategori, 'nama_produk' => 'Gourmet Grilled Beef Burger', 'merk' => 'Kitchen', 'harga_beli' => 380, 'harga_jual' => 650, 'diskon' => 0, 'stok' => 80]
        );

        $p3 = Produk::firstOrCreate(
            ['kode_produk' => 'PRD-0003'],
            ['id_kategori' => $catFries->id_kategori, 'nama_produk' => 'Loaded Cheesy Fries', 'merk' => 'Kitchen', 'harga_beli' => 150, 'harga_jual' => 320, 'diskon' => 0, 'stok' => 150]
        );

        $p4 = Produk::firstOrCreate(
            ['kode_produk' => 'PRD-0004'],
            ['id_kategori' => $catDrinks->id_kategori, 'nama_produk' => 'Fresh Mint Lemonade', 'merk' => 'Kitchen', 'harga_beli' => 60, 'harga_jual' => 180, 'diskon' => 0, 'stok' => 200]
        );

        $p5 = Produk::firstOrCreate(
            ['kode_produk' => 'PRD-0005'],
            ['id_kategori' => $catDrinks->id_kategori, 'nama_produk' => 'Chilled Soft Drink (Can)', 'merk' => 'Beverage', 'harga_beli' => 65, 'harga_jual' => 120, 'diskon' => 0, 'stok' => 120]
        );

        $p6 = Produk::firstOrCreate(
            ['kode_produk' => 'PRD-0006'],
            ['id_kategori' => $catDeals->id_kategori, 'nama_produk' => 'Mighty Duo Feast Deal (2 Zinger + Fries + 2 Drinks)', 'merk' => 'Combo', 'harga_beli' => 600, 'harga_jual' => 1190, 'diskon' => 0, 'stok' => 50]
        );

        // 6. Ensure Sample Customer Members
        $m1 = Member::firstOrCreate(['kode_member' => 'MBR-0001'], ['nama' => 'Usman Tariq', 'alamat' => 'Gulberg III, Lahore', 'telepon' => '0300-1234567']);
        $m2 = Member::firstOrCreate(['kode_member' => 'MBR-0002'], ['nama' => 'Sara Ali', 'alamat' => 'DHA Phase 5', 'telepon' => '0321-9876543']);
        $m3 = Member::firstOrCreate(['kode_member' => 'MBR-0003'], ['nama' => 'Ahmed Khan', 'alamat' => 'Johar Town', 'telepon' => '0333-5554433']);

        $cashierUser = $createdUsers['cashier'] ?? User::first();

        // 7. Seed Realistic Fast Food Orders in different Workflow Stages

        // --- STAGE 1: NEW PENDING ORDER (Freshly punched by Cashier/Waiter) ---
        $inv1 = Penjualan::create([
            'id_user' => $cashierUser->id,
            'id_member' => null,
            'nomor_meja' => 'Table 4',
            'tipe_order' => 'Dine-In',
            'total_item' => 5,
            'total_harga' => 1580,
            'diskon' => 0,
            'bayar' => 1580,
            'diterima' => 1580,
            'status_pembayaran' => 'paid',
            'metode_pembayaran' => 'cash',
            'kitchen_status' => 'pending',
            'catatan' => 'Extra Spicy Zinger filleting & Less Mayo in Fries',
            'created_at' => Carbon::now()->subMinutes(2),
            'updated_at' => Carbon::now()->subMinutes(2),
        ]);
        PenjualanDetail::create(['id_penjualan' => $inv1->id_penjualan, 'id_produk' => $p1->id_produk, 'harga_jual' => 450, 'jumlah' => 2, 'diskon' => 0, 'subtotal' => 900]);
        PenjualanDetail::create(['id_penjualan' => $inv1->id_penjualan, 'id_produk' => $p3->id_produk, 'harga_jual' => 320, 'jumlah' => 1, 'diskon' => 0, 'subtotal' => 320]);
        PenjualanDetail::create(['id_penjualan' => $inv1->id_penjualan, 'id_produk' => $p4->id_produk, 'harga_jual' => 180, 'jumlah' => 2, 'diskon' => 0, 'subtotal' => 360]);

        // --- STAGE 2: COOKING / PREP IN PROGRESS (Chef started cooking 7 mins ago - Yellow SLA) ---
        $inv2 = Penjualan::create([
            'id_user' => $cashierUser->id,
            'id_member' => $m1->id_member,
            'nomor_meja' => 'Counter',
            'tipe_order' => 'Takeaway',
            'total_item' => 3,
            'total_harga' => 1430,
            'diskon' => 0,
            'bayar' => 1430,
            'diterima' => 1430,
            'status_pembayaran' => 'paid',
            'metode_pembayaran' => 'card',
            'kitchen_status' => 'cooking',
            'kitchen_started_at' => Carbon::now()->subMinutes(6),
            'catatan' => 'Pack garlic mayo sauce dips separately in small cups',
            'created_at' => Carbon::now()->subMinutes(7),
            'updated_at' => Carbon::now()->subMinutes(6),
        ]);
        PenjualanDetail::create(['id_penjualan' => $inv2->id_penjualan, 'id_produk' => $p6->id_produk, 'harga_jual' => 1190, 'jumlah' => 1, 'diskon' => 0, 'subtotal' => 1190]);
        PenjualanDetail::create(['id_penjualan' => $inv2->id_penjualan, 'id_produk' => $p5->id_produk, 'harga_jual' => 120, 'jumlah' => 2, 'diskon' => 0, 'subtotal' => 240]);

        // --- STAGE 3: READY FOR PICKUP / PACKING (Chef marked ready - Waiting for Rider/Customer) ---
        $inv3 = Penjualan::create([
            'id_user' => $cashierUser->id,
            'id_member' => $m2->id_member,
            'nomor_meja' => 'Delivery',
            'tipe_order' => 'Delivery',
            'total_item' => 4,
            'total_harga' => 2270,
            'diskon' => 5,
            'bayar' => 2156.5,
            'diterima' => 2156.5,
            'status_pembayaran' => 'paid',
            'metode_pembayaran' => 'online',
            'kitchen_status' => 'ready',
            'kitchen_started_at' => Carbon::now()->subMinutes(11),
            'kitchen_ready_at' => Carbon::now()->subMinutes(1),
            'catatan' => 'Foodpanda Rider dispatched. Handover with heated bag.',
            'created_at' => Carbon::now()->subMinutes(12),
            'updated_at' => Carbon::now()->subMinutes(1),
        ]);
        PenjualanDetail::create(['id_penjualan' => $inv3->id_penjualan, 'id_produk' => $p2->id_produk, 'harga_jual' => 650, 'jumlah' => 3, 'diskon' => 5, 'subtotal' => 1852.5]);
        PenjualanDetail::create(['id_penjualan' => $inv3->id_penjualan, 'id_produk' => $p3->id_produk, 'harga_jual' => 320, 'jumlah' => 1, 'diskon' => 5, 'subtotal' => 304]);

        // --- STAGE 4: SERVED / COMPLETED TODAY ---
        $inv4 = Penjualan::create([
            'id_user' => $cashierUser->id,
            'id_member' => $m3->id_member,
            'nomor_meja' => 'Table 1',
            'tipe_order' => 'Dine-In',
            'total_item' => 3,
            'total_harga' => 1020,
            'diskon' => 0,
            'bayar' => 1020,
            'diterima' => 1020,
            'status_pembayaran' => 'paid',
            'metode_pembayaran' => 'cash',
            'kitchen_status' => 'served',
            'kitchen_started_at' => Carbon::now()->subMinutes(40),
            'kitchen_ready_at' => Carbon::now()->subMinutes(25),
            'kitchen_served_at' => Carbon::now()->subMinutes(20),
            'catatan' => 'Served with fresh wet wipes and extra napkins',
            'created_at' => Carbon::now()->subMinutes(45),
            'updated_at' => Carbon::now()->subMinutes(20),
        ]);
        PenjualanDetail::create(['id_penjualan' => $inv4->id_penjualan, 'id_produk' => $p1->id_produk, 'harga_jual' => 450, 'jumlah' => 2, 'diskon' => 0, 'subtotal' => 900]);
        PenjualanDetail::create(['id_penjualan' => $inv4->id_penjualan, 'id_produk' => $p5->id_produk, 'harga_jual' => 120, 'jumlah' => 1, 'diskon' => 0, 'subtotal' => 120]);
    }
}
