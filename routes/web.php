<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function(){
  return redirect()->route('dashboards.index');
});
Route::controller(App\Http\Controllers\AuthController::class)->prefix('auth')->name('auth.')->group(function(){
  Route::middleware('guest')->group(function(){
    Route::get('/login', 'login')->name('login');
    Route::post('/login-action', 'login_action')->name('login_action');
    Route::get('/reset-password', 'reset_password')->name('reset_password');
    Route::post('/reset-password-action', 'reset_password_action')->name('reset_password_action');
  });
});

Route::middleware('auth')->group(function(){
  // Auth Action
  Route::controller(App\Http\Controllers\AuthController::class)->prefix('auth')->name('auth.')->group(function(){
    Route::post('/logout-action', 'logout_action')->name('logout_action');
    Route::get('/change-password', 'change_password')->name('change_password');
    Route::get('/change-password-action', 'change_password_action')->name('change_password_action=');
  });
  // Dashboard
  Route::controller(App\Http\Controllers\DashboardController::class)->name('dashboards.')->group(function(){
    Route::get('/dashboard', 'index')->name('index');
  });
  // Donatur
  Route::get('/donatur/data', [App\Http\Controllers\DonaturController::class, 'data'])->name('donatur.data');
  Route::post('/donatur/store', [App\Http\Controllers\DonaturController::class, 'store_on_modal'])->name('donatur.store.modal');
  Route::resource('/donatur', App\Http\Controllers\DonaturController::class)->except('show');
  // Bantuan
  Route::controller(App\Http\Controllers\BantuanController::class)->prefix('bantuan')->name('bantuan.')->group(function(){
    Route::get('/data', 'data')->name('data');
    Route::get('/{kategori}', 'create')->name('create');
    // Verifikasi Bantuan Uang
    Route::get('/verifikasi/uang', [App\Http\Controllers\VerifikasiBantuanController::class, 'index'])->name('verifikasi.index');
    Route::get('/verifikasi/data', [App\Http\Controllers\VerifikasiBantuanController::class, 'data'])->name('verifikasi.data');
    Route::get('/verifikasi/uang/{id}', [App\Http\Controllers\VerifikasiBantuanController::class, 'edit'])->name('verifikasi.edit');
    Route::put('/verifikasi/uang/{id}', [App\Http\Controllers\VerifikasiBantuanController::class, 'update'])->name('verifikasi.update');
  });
  Route::resource('/bantuan', App\Http\Controllers\BantuanController::class)->except('create');
  // Distribusi
  Route::controller(App\Http\Controllers\DistribusiController::class)->prefix('distribusi')->name('distribusi.')->group(function(){
    Route::get('/data', 'data')->name('data');
    Route::get('/penerima', 'penerima')->name('penerima');
    Route::get('/penerima/create', 'create_penerima')->name('create.penerima');
    Route::post('/penerima', 'store_penerima')->name('store.penerima');
    Route::get('/penerima/{id}/edit', 'edit_penerima')->name('edit.penerima');
    Route::put('/penerima/{id}', 'update_penerima')->name('update.penerima');
    Route::delete('/penerima/{id}', 'destroy_penerima')->name('destroy.penerima');
    Route::get('/create', 'create')->name('create');
    Route::get('/{id}/edit', 'edit')->name('edit');
  });
  Route::resource('/distribusi', App\Http\Controllers\DistribusiController::class)->except('create');
  // Penarikan
  Route::controller(App\Http\Controllers\PenarikanController::class)->prefix('penarikan')->name('penarikan.')->group(function(){
    Route::get('/data', 'data')->name('data');
    Route::prefix('rencana-realisasi')->name('rencana-realisasi.')->group(function(){
      Route::get('/', 'rencana_realisasi')->name('index');
      Route::get('/{kategori}/create', 'create_rencana_realisasi')->name('create');
      Route::post('/{kategori}', 'store_rencana_realisasi')->name('store');
      Route::get('/{kategori}/{id}/edit', 'edit_rencana_realisasi')->name('edit');
      Route::put('/{kategori}/{id}', 'update_rencana_realisasi')->name('update');
      Route::delete('/{id}', 'destroy_rencana_realisasi')->name('destroy');
    });
    // Aproval Penarikan
    Route::controller(App\Http\Controllers\VerifikasiPenarikanController::class)->prefix('verifikasi')->name('verifikasi.')->group(function(){
      Route::get('/{id}', 'create')->name('create');
      Route::put('/{id}', 'store')->name('store');
      Route::get('/{id}/upload_bukti_tf', 'upload_bukti_tf')->name('upload_bukti_tf');
      Route::put('/{id}/update', 'update')->name('update');
    });
    // Pelaporan Penarikan
    Route::get('/pelaporan/data', [App\Http\Controllers\PelaporanPenarikanController::class, 'data'])->name('pelaporan.data');
    Route::get('/pelaporan/{id}/laporan', [App\Http\Controllers\PelaporanPenarikanController::class, 'laporan'])->name('pelaporan.laporan');
    Route::resource('/pelaporan', App\Http\Controllers\PelaporanPenarikanController::class);
  });
  Route::resource('/penarikan', App\Http\Controllers\PenarikanController::class)->except('show');
});

Route::controller(App\Http\Controllers\DataController::class)->prefix('data')->name('data.')->group(function(){
  Route::get('/get-kelurahan', 'get_kelurahan')->name('get_kelurahan');
  Route::get('/get-donatur', 'get_donatur')->name('get_donatur');
  Route::get('/get-asw-id', 'get_asw_id')->name('get_asw_id');
  Route::get('/get-balita-stunting', 'get_balita_stunting')->name('get_balita_stunting');
  Route::get('/get-permakanan', 'get_permakanan')->name('get_permakanan');
  Route::get('/get-beasiswa', 'get_beasiswa')->name('get_beasiswa');
  Route::get('/get-rutilahu', 'get_rutilahu')->name('get_rutilahu');
  Route::get('/get-jamban', 'get_jamban')->name('get_jamban');
  Route::get('/get-bumil-resti', 'get_bumil_resti')->name('get_bumil_resti');
  Route::get('/get-cekin', 'get_cekin')->name('get_cekin');
});