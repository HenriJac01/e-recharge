<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\yas\ClientController;
use App\Http\Controllers\dgi\HomeDgiController;
use App\Http\Controllers\yas\HomeYasController;
use App\Http\Controllers\ussd\USSDDialerController;
use App\Http\Controllers\yas\TransactionController;

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


//composer ussd
Route::get('/ussd', [USSDDialerController::class, 'index'])->name('ussd.dialer');
Route::post('/ussd/process', [USSDDialerController::class, 'processUSSD'])->name('ussd.process');

//Route home page
Route::get('/', [HomeDgiController::class, 'index'])->name('auth/login');
Route::get('/', [HomeYasController::class, 'index'])->name('auth/login');

//Route authentification
Route::controller(AuthController::class)->group(function () {

    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');

    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');

    Route::get('logout', 'logout')->middleware('auth')->name('logout');

    Route::get('forgotPassword', 'forgotPassword')->name('forgotPassword');
});



//Route dgi
Route::prefix('dgi')->middleware(['auth','user-access:dgi'])->group(function(){
    Route::get('/home', [HomeDgiController::class, 'dashDgi'])->name('dgi/dashDgi');
});

//Route yas
Route::prefix('yas')->middleware(['auth','user-access:yas'])->group(function(){
    Route::get('/home', [HomeYasController::class, 'dashYas'])->name('yas/dashYas');
    Route::get('/transaction', [TransactionController::class, 'transaction'])->name('yas/transaction');
    Route::get('/tables/client', [ClientController::class, 'client'])->name('yas/tables.client');
    Route::get('/formes/client', [ClientController::class, 'selectOperator'])->name('yas/formes.client');
    Route::post('/client/store', [ClientController::class, 'store'])->name('client.store');
    Route::get('/client/{id}', [ClientController::class, 'destroy'])->name('client.destroy');
});