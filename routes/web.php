<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController,
    LaporanController,
    ProdukController,
    DealController,
    MemberController,
    MejaController,
    PengeluaranController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    KitchenController,
    ProductRecipeController,
    RawMaterialController,
    RoleController,
    SettingController,
    SupplierController,
    SupplierLedgerController,
    CustomerLedgerController,
    UnitController,
    UserController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Fast Food Restaurant POS
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/period-data', [DashboardController::class, 'getPeriodData'])->name('dashboard.period_data');
    Route::get('/dashboard/table-status', [DashboardController::class, 'getTableStatus'])->name('dashboard.table_status');

    // ==========================================
    // 1. ADMIN & MANAGER (Menu, Deals, Inventory & Recipes, Tables, Expenses)
    // ==========================================
    Route::group(['middleware' => ['role_or_permission:admin|manager|categories.view|products.view|purchases.view|expenses.view']], function () {
        // Deals & Combos
        Route::get('/deal/data', [DealController::class, 'data'])->name('deal.data');
        Route::resource('/deal', DealController::class);

        // Categories
        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);

        // Products & Menu Items
        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
        Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
        Route::resource('/produk', ProdukController::class);

        // Dish Recipes & Ingredients Mapping (BOM)
        Route::get('/recipe', [ProductRecipeController::class, 'index'])->name('recipe.index');
        Route::get('/recipe/{id}/get', [ProductRecipeController::class, 'getRecipe'])->name('recipe.get');
        Route::get('/recipe/{id}/data', [ProductRecipeController::class, 'getRecipe'])->name('recipe.data');
        Route::post('/recipe/{id}/save', [ProductRecipeController::class, 'saveRecipe'])->name('recipe.save');
        Route::post('/recipe/{id}', [ProductRecipeController::class, 'saveRecipe']);

        // Raw Materials & Ingredients Inventory
        Route::get('/raw_material/data', [RawMaterialController::class, 'data'])->name('raw_material.data');
        Route::post('/raw_material/{id}/adjust', [RawMaterialController::class, 'adjust'])->name('raw_material.adjust');
        Route::resource('/raw_material', RawMaterialController::class);

        // Measurement Units (KG, Gram, Liter, Pcs)
        Route::get('/unit/data', [UnitController::class, 'data'])->name('unit.data');
        Route::get('/unit/list', [UnitController::class, 'list'])->name('unit.list');
        Route::resource('/unit', UnitController::class);

        // Dining Tables & Seating Management
        Route::get('/meja/data', [MejaController::class, 'data'])->name('meja.data');
        Route::post('/meja/{id}/free', [MejaController::class, 'freeTable'])->name('meja.free');
        Route::resource('/meja', MejaController::class);

        // Suppliers & Purchases
        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::get('/pembelian/create', [PembelianController::class, 'create']);
        Route::get('/pembelian/{id}/nota-kecil', [PembelianController::class, 'notaKecil'])->name('pembelian.nota_kecil');
        Route::post('/pembelian/{id}/settle-payment', [PembelianController::class, 'settlePayment'])->name('pembelian.settle_payment');
        Route::resource('/pembelian', PembelianController::class)->except('create');

        Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembelian_detail/loadform/{diskon?}/{total?}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
        Route::resource('/pembelian_detail', PembelianDetailController::class)->except('create', 'show', 'edit');

        // Customer Members
        Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
        Route::resource('/member', MemberController::class);

        // Supplier Ledgers & Statements
        Route::get('/ledger/supplier/data', [SupplierLedgerController::class, 'data'])->name('ledger.supplier.data');
        Route::get('/ledger/supplier', [SupplierLedgerController::class, 'index'])->name('ledger.supplier.index');
        Route::get('/ledger/supplier/{id}', [SupplierLedgerController::class, 'statement'])->name('ledger.supplier.statement');
        Route::post('/ledger/supplier/{id}/pay', [SupplierLedgerController::class, 'recordPayment'])->name('ledger.supplier.pay');

        // Customer Ledgers & Statements
        Route::get('/ledger/customer/data', [CustomerLedgerController::class, 'data'])->name('ledger.customer.data');
        Route::get('/ledger/customer', [CustomerLedgerController::class, 'index'])->name('ledger.customer.index');
        Route::get('/ledger/customer/{id}', [CustomerLedgerController::class, 'statement'])->name('ledger.customer.statement');
        Route::post('/ledger/customer/{id}/receive', [CustomerLedgerController::class, 'receivePayment'])->name('ledger.customer.receive');

        // Operational Expenses
        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);
    });

    // ==========================================
    // 2. CASHIER, WAITER & POS (Fast Food Touch Screen Terminal & Invoices)
    // ==========================================
    Route::group(['middleware' => ['role_or_permission:admin|cashier|manager|waiter|pos.access|sales.view_all|sales.view_own']], function () {
        // Customer Invoices
        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
        Route::get('/penjualan/{id}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
        Route::get('/penjualan/{id}/info', [PenjualanController::class, 'getInfo'])->name('penjualan.info');
        Route::post('/penjualan/{id}/update-info', [PenjualanController::class, 'updateInfo'])->name('penjualan.update_info');
        Route::post('/penjualan/{id}/settle-payment', [PenjualanController::class, 'settlePayment'])->name('penjualan.settle_payment');
        Route::post('/penjualan/{id}/settle', [PenjualanController::class, 'settlePayment']);
        Route::get('/penjualan/{id}/nota-kecil', [PenjualanController::class, 'notaKecilSingle'])->name('penjualan.nota_kecil');
        Route::get('/penjualan/{id}/nota-besar', [PenjualanController::class, 'notaBesarSingle'])->name('penjualan.nota_besar');

        // POS Fast Food Terminal
        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
        Route::get('/transaksi/cancel-edit', [PenjualanController::class, 'cancelEdit'])->name('transaksi.cancel_edit');
        Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
        Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
        Route::post('/transaksi/draft', [PenjualanController::class, 'saveDraft'])->name('transaksi.draft');
        Route::get('/transaksi/draft-list', [PenjualanController::class, 'draftList'])->name('transaksi.draft_list');
        Route::delete('/transaksi/draft/{id}', [PenjualanController::class, 'deleteDraft'])->name('transaksi.delete_draft');
        Route::get('/transaksi/draft/{id}/resume', [PenjualanController::class, 'resumeDraft'])->name('transaksi.resume_draft');

        Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
        Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

        Route::get('/transaksi/tables/status', [PenjualanController::class, 'getTablesStatus'])->name('transaksi.tables_status');
        Route::post('/transaksi/item-note/{id}', [PenjualanDetailController::class, 'updateNote'])->name('transaksi.item_note');

        Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
        Route::resource('/transaksi', PenjualanDetailController::class)->except('create', 'show');
    });

    // ==========================================
    // 3. KITCHEN DISPLAY SYSTEM (KDS) & KOT
    // ==========================================
    Route::group(['middleware' => ['role_or_permission:admin|kitchen.access']], function () {
        Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
        Route::get('/kitchen/data', [KitchenController::class, 'data'])->name('kitchen.data');
        Route::post('/kitchen/{id}/status', [KitchenController::class, 'updateStatus'])->name('kitchen.update_status');
    });

    Route::group(['middleware' => ['role_or_permission:admin|pos.print_kot|kitchen.access|pos.access']], function () {
        Route::get('/kitchen/kot/{id}', [KitchenController::class, 'kot'])->name('kitchen.kot');
    });

    // ==========================================
    // 4. ANALYTICS & REPORTS
    // ==========================================
    Route::group(['middleware' => ['role_or_permission:admin|manager|reports.view']], function () {
        Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('/laporan/pembelian', [LaporanController::class, 'pembelian'])->name('laporan.pembelian');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');
    });

    // ==========================================
    // 5. STAFF USERS, ROLES & PERMISSIONS, SETTINGS
    // ==========================================
    Route::group(['middleware' => ['role_or_permission:admin|manager|users.manage']], function () {
        // Staff Users
        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        // Roles & Permissions (RBAC)
        Route::get('/role/data', [RoleController::class, 'data'])->name('role.data');
        Route::get('/role/{id}/permissions', [RoleController::class, 'permissions'])->name('role.permissions');
        Route::post('/role/{id}/permissions', [RoleController::class, 'updatePermissions'])->name('role.permissions.update');
        Route::resource('/role', RoleController::class);
    });

    Route::group(['middleware' => ['role_or_permission:admin|settings.manage']], function () {
        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });

    // User Profile
    Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
    Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
});