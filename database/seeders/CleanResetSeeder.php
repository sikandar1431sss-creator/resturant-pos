<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class CleanResetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Disable Foreign Key Constraints
        Schema::disableForeignKeyConstraints();

        // 2. Truncate all transaction and catalog tables safely
        $tablesToTruncate = [
            'penjualan_detail',
            'penjualan',
            'pembelian_detail',
            'pembelian',
            'pengeluaran',
            'deal_items',
            'deals',
            'product_recipes',
            'raw_materials',
            'produk',
            'kategori',
            'member',
            'shift_closings',
            'model_has_roles',
            'model_has_permissions',
            'role_has_permissions',
            'users'
        ];

        foreach ($tablesToTruncate as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        // 3. Re-enable Foreign Key Constraints
        Schema::enableForeignKeyConstraints();

        // 4. Reset & Sync Spatie Permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->call(RolePermissionSeeder::class);

        // 5. Create the 4 Requested Staff Accounts
        // A. Admin (Owner)
        $admin = User::create([
            'name' => 'Restaurant Admin',
            'email' => 'admin@restaurant.com',
            'password' => Hash::make('admin123'),
            'foto' => '/img/user.jpg',
            'level' => 1,
        ]);
        $admin->syncRoles(['admin']);

        // B. Manager
        $manager = User::create([
            'name' => 'Store Manager',
            'email' => 'manager@restaurant.com',
            'password' => Hash::make('manager123'),
            'foto' => '/img/user.jpg',
            'level' => 2,
        ]);
        $manager->syncRoles(['manager']);

        // C. Waiter (Front of house order taking & billing)
        $waiter = User::create([
            'name' => 'Front Waiter',
            'email' => 'waiter@restaurant.com',
            'password' => Hash::make('waiter123'),
            'foto' => '/img/user.jpg',
            'level' => 2,
        ]);
        $waiter->syncRoles(['waiter']);

        // D. Chef (Kitchen Display & Timers)
        $chef = User::create([
            'name' => 'Head Chef',
            'email' => 'chef@restaurant.com',
            'password' => Hash::make('chef123'),
            'foto' => '/img/user.jpg',
            'level' => 2,
        ]);
        $chef->syncRoles(['kitchen']);

        // 6. Create 1 Clean Example Category
        $kategori = Kategori::create([
            'nama_kategori' => 'Fast Food'
        ]);

        // 7. Create 1 Clean Example Product Item
        Produk::create([
            'id_kategori' => $kategori->id_kategori,
            'kode_produk' => 'P00001',
            'nama_produk' => 'Zinger Burger',
            'merk' => 'Crispy Zinger',
            'harga_beli' => 250,
            'harga_jual' => 450,
            'diskon' => 0,
            'stok' => 100,
            'foto' => null
        ]);

        // 8. Ensure Setting is configured properly
        $setting = Setting::first();
        if (!$setting) {
            Setting::create([
                'nama_perusahaan' => 'Fast Food Restaurant',
                'alamat' => 'Main Commercial Boulevard',
                'telepon' => '0300-1234567',
                'tipe_nota' => 1,
                'diskon' => 0,
                'path_logo' => '/img/logo.png',
                'path_kartu_member' => '/img/member.png',
            ]);
        }
    }
}
