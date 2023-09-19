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

  Route::resource('/donatur', App\Http\Controllers\DonaturController::class)->except('show');
  Route::get('/donatur/data', [App\Http\Controllers\DonaturController::class, 'data'])->name('donatur.data');
});