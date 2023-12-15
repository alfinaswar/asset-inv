<?php

use App\Http\Controllers\AssetManagemenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataInventarisController;
use App\Http\Controllers\FileTemplateController;
use App\Http\Controllers\MasalahController;
use App\Http\Controllers\MasterDepartemenController;
use App\Http\Controllers\MasterIPController;
use App\Http\Controllers\MasterPenggunaController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\KalibrasiController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\MasterRsController;
use App\Models\MasalahModel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Route::middleware(['auth'])->group(function () {



    Route::get('asset-managemen/get-ip', [AssetManagemenController::class, 'getIP'])->name('get-ip');
    Route::get('asset-managemen/get-departemen', [AssetManagemenController::class, 'getDepartemen'])->name('get-departemen');
    Route::get('asset-managemen/label/{id}', [AssetManagemenController::class, 'label'])->name('label');
    Route::resource('asset-managemen', AssetManagemenController::class);

    Route::get('masalah/get-asset', [MasalahController::class, 'getAsset'])->name('get-asset');
    Route::get('masalah/get-alat', [MasalahController::class, 'getAlat'])->name('get-alat');
    Route::get('/get-item', [MasalahController::class, 'getItem'])->name('masalah.get-item');
    
    Route::get('masalah/edit', [MasalahController::class, 'edit'])->name('Masalah.edit');
    Route::get('/export',[MasalahController::class,'export'])->name('masalah.export');
    Route::get('/excel_masalah/',[MasalahController::class,'excel_masalah'])->name('masalah.excel_masalah');

    Route::resource('masalah', MasalahController::class);

    Route::get('perbaikan/get-asset', [PerbaikanController::class, 'getAsset'])->name('get-asset-perbaikan');
    Route::get('perbaikan/label/{id}', [PerbaikanController::class, 'label'])->name('label-perbaikan');
    Route::put('perbaikan/status/{id}', [PerbaikanController::class, 'status'])->name('update-status');
    Route::resource('perbaikan', PerbaikanController::class);

    Route::group(['prefix' => 'managemenUser'], function () {
        Route::resource('Permission', PermissionController::class);
        Route::get('change-password', [UserController::class, 'changePassword'])->name('change-password');
        Route::post('save-password', [UserController::class, 'changePasswordSave'])->name('save-password');
        Route::get('/get-rs', [UserController::class, 'getrs'])->name('user.get-rs');
        Route::resource('User', UserController::class);
        Route::resource('Role', RoleController::class);
    });

    Route::resource('file-template', FileTemplateController::class);
    Route::get('file-template/download/{id}', [FileTemplateController::class, 'downloadFile'])->name('download-file-template');
});
// Auth::routes();
Route::group(['prefix' => 'master'], function () {
    Route::resource('master-ip', MasterIPController::class);
    Route::get('master-departemen/get-departemen', [AssetManagemenController::class, 'getDepartemen'])->name('master.get-departemen');
    Route::resource('master-departemen', MasterDepartemenController::class);
 

    Route::prefix('master-pengguna')->group(function () {
    Route::get('/', [MasterPenggunaController::class, 'index'])->name('master.master-pengguna.index');
    Route::get('/create', [MasterPenggunaController::class, 'create'])->name('master.master-pengguna.create');
    Route::post('/store', [MasterPenggunaController::class, 'store'])->name('master.master-pengguna.store');
    });
    Route::prefix('master-rs')->group(function () {
        Route::resource('master-rs', MasterRsController::class);
        Route::get('/', [MasterRsController::class, 'index'])->name('master.master-rs.index');
        Route::get('/create', [MasterRsController::class, 'create'])->name('master.master-rs.create');
        Route::post('/store', [MasterRsController::class, 'store'])->name('master.master-rs.store');
    });
});

Route::prefix('inventaris')->group(function () {
    Route::get('/', [DataInventarisController::class, 'index'])->name('inventaris.index');
    Route::get('/create', [DataInventarisController::class, 'create'])->name('inventaris.create');
    Route::post('/store', [DataInventarisController::class, 'store'])->name('inventaris.store');
    Route::get('/get-item', [DataInventarisController::class, 'getItem'])->name('inventaris.get-item');
    Route::get('/label/{id}', [DataInventarisController::class, 'label'])->name('inventaris.label');
    Route::get('/tesprint', [DataInventarisController::class, 'tesprint'])->name('inventaris.tesprint');
    Route::get('/masteritem', [DataInventarisController::class, 'masteritem'])->name('inventaris.masteritem');
    Route::resource('inventaris', DataInventarisController::class);
    Route::get('getkategori', [DataInventarisController::class, 'getkategori'])->name('inventaris.getkategori');
    
});
Route::prefix('pembelian')->group(function () {
    Route::get('/', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/file-import',[PembelianController::class,'importView'])->name('pembelian.import-view');
    Route::get('/export',[PembelianController::class,'export'])->name('pembelian.export');
    Route::get('/export-users',[PembelianController::class,'exportUsers'])->name('pembelian.export-users');
    // Route::get('student_export',[StudentController::class, 'get_student_data'])->name('student.export');
    Route::get('/get-item', [PembelianController::class, 'getItem'])->name('pembelian.get-item');
    Route::get('/excel_pembelian/', [PembelianController::class, 'excel_pembelian'])->name('pembelian.excel_pembelian');
   
});
Route::prefix('gudang')->group(function () {
    Route::get('/', [GudangController::class, 'index'])->name('gudang.index');
   

});
Route::prefix('kalibrasi')->group(function () {
    Route::get('/', [KalibrasiController::class, 'index'])->name('kalibrasi.index');
    Route::post('/store', [KalibrasiController::class, 'store'])->name('kalibrasi.store');
    Route::get('/get-item', [KalibrasiController::class, 'getItem'])->name('kalibrasi.get-item');
    Route::get('/destroy', [KalibrasiController::class, 'destroy'])->name('kalibrasi.destroy');


});

Route::get('/history/{kode_item}', [MasalahController::class, 'history'])->name('masalah.history');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
