<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController,
    LaporanController,
    ProdukController,
    DealController,
    MemberController,
    PengeluaranController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    SettingController,
    SupplierController,
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

    // ==========================================
    // 1. ADMIN & MANAGER (Inventory, Menu, Deals, Expenses)
    // ==========================================
    Route::group(['middleware' => ['role_or_permission:admin|manager|categories.view']], function () {
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

        // Suppliers & Purchases
        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::get('/pembelian/{id}/nota-kecil', [PembelianController::class, 'notaKecil'])->name('pembelian.nota_kecil');
        Route::post('/pembelian/{id}/settle-payment', [PembelianController::class, 'settlePayment'])->name('pembelian.settle_payment');
        Route::resource('/pembelian', PembelianController::class);

        Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
        Route::resource('/pembelian_detail', PembelianDetailController::class)->except('create', 'show', 'edit');

        // Customer Members
        Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
        Route::resource('/member', MemberController::class);

        // Operational Expenses
        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);
    });

    // ==========================================
    // 2. CASHIER & POS (Fast Food Touch Screen Terminal)
    // ==========================================
    Route::group(['middleware' => ['role_or_permission:admin|cashier|manager|pos.access']], function () {
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

        Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
        Route::resource('/transaksi', PenjualanDetailController::class)->except('create', 'show');
    });

    // ==========================================
    // 3. ADMIN ONLY (Reports, Staff Users, Settings)
    // ==========================================
    Route::group(['middleware' => ['role_or_permission:admin|reports.view']], function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });

    // User Profile
    Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
    Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
});