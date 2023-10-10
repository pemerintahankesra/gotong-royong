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
  Route::controller(App\Http\Controllers\AuthController::class)->prefix('auth')->name('auth.')->group(function(){
    Route::post('/logout-action', 'logout_action')->name('logout_action');
    Route::get('/change-password', 'change_password')->name('change_password');
    Route::get('/change-password-action', 'change_password_action')->name('change_password_action=');
  });

  Route::controller(App\Http\Controllers\DashboardController::class)->name('dashboards.')->group(function(){
    Route::get('/dashboard', 'index')->name('index');
  });
  
  Route::get('/donatur/data', [App\Http\Controllers\DonaturController::class, 'data'])->name('donatur.data');
  Route::resource('/donatur', App\Http\Controllers\DonaturController::class)->except('show');
  Route::controller(App\Http\Controllers\BantuanController::class)->prefix('bantuan')->name('bantuan.')->group(function(){
    Route::get('/data', 'data')->name('data');
    Route::get('/{kategori}', 'create')->name('create');
  });
  Route::resource('/bantuan', App\Http\Controllers\BantuanController::class)->except('create');
  Route::controller(App\Http\Controllers\DistribusiController::class)->prefix('distribusi')->name('distribusi.')->group(function(){
    Route::get('/data', 'data')->name('data');
    Route::get('/penerima', 'penerima')->name('penerima');
    Route::get('/penerima/create', 'create_penerima')->name('create.penerima');
    Route::post('/penerima', 'store_penerima')->name('store.penerima');
    Route::delete('/penerima/{id}', 'destroy_penerima')->name('destroy.penerima');
    Route::get('/{kategori}', 'create')->name('create');
  });
  Route::resource('/distribusi', App\Http\Controllers\DistribusiController::class)->except('create');
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