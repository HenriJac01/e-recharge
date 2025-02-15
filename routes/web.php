<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dgi\DgiController;
use App\Http\Controllers\yas\StockController;
use App\Http\Controllers\yas\ClientController;
use App\Http\Controllers\dgi\HomeDgiController;
use App\Http\Controllers\yas\HomeYasController;
use App\Http\Controllers\dgi\DecompteController;
use App\Http\Controllers\dgi\TransmisController;
use App\Http\Controllers\yas\TransmitController;
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
    //Route dashboard DGI
    Route::get('/home', [HomeDgiController::class, 'dashDgi'])->name('dgi/dashDgi');
    //Route view pour les transmissions faisant par les opérateurs
    Route::get('/transmission',[TransmisController::class,'transmis'])->name('dgi/transmission');
    //Route view pour les DGI 
    Route::get('/tables/dgi',[DgiController::class,'dgi'])->name('dgi/tables.dgi');
    //Route forme pour les DGI
    Route::get('formes/dgi',[DgiController::class,'formDgi'])->name('dgi/formes.dgi');
    /* Route CUD pour les DGI*/
    Route::post('/dgi/store',[DgiController::class,'store'])->name('dgi.store');
    /* Route CUD pour les DGI*/
    //Route pour imprimer les décomptes de droit d'accise
    Route::get('/decompte/{id}', [DecompteController::class, 'decompteDA'])->name('decompte.print');
});

//Route yas
Route::prefix('yas')->middleware(['auth','user-access:yas'])->group(function(){
    //Route dahsboard Yas
    Route::get('/home', [HomeYasController::class, 'dashYas'])->name('yas/dashYas');
    //Route view pour les transactions
    Route::get('/transaction', [TransactionController::class, 'transaction'])->name('yas/transaction');
    //Route view pour les clients
    Route::get('/tables/client', [ClientController::class, 'client'])->name('yas/tables.client');
    //Route formes pour les clients
    Route::get('/formes/client', [ClientController::class, 'selectOperator'])->name('yas/formes.client');
    //Route forme edit pour les clients
    Route::get('/client/{id}/edit', [ClientController::class, 'edit'])->name('yas/formes.editClient');
    /* Route CUD pour les clients */
    Route::post('/client/store', [ClientController::class, 'store'])->name('client.store');
    Route::put('/client/update/{id}',[ClientController::class, 'update'])->name('client.update');
    Route::get('/client/{id}', [ClientController::class, 'destroy'])->name('client.destroy');
    /* Route CUD pour les clients */

    //Route view pour le stock
    Route::get('/tables/stock', [StockController::class, 'stock'])->name('yas/tables.stock');
    //Route forme edit pour les clients
    Route::get('/stock/{id}/edit', [StockController::class, 'edit'])->name('yas/formes.editStock');
    /* Route UD pour le stock*/
    Route::put('/stock/update/{id}',[StockController::class, 'update'])->name('stock.update');
    Route::get('/stock/{id}', [StockController::class, 'destroy'])->name('stock.destroy');
    /* Route UD pour le stock*/

    //Route view pour les transmissions
    Route::get('/tables/transmission', [TransmitController::class, 'transmit'])->name('yas/tables.transmission');
    //Route forme pour les transmissions
    Route::get('/formes/transmission', [TransmitController::class, 'create'])->name('yas/formes.transmission');
    //Route forme edit pour les transmission 
    Route::get('/transmission/{id}/edit', [TransmitController::class, 'edit'])->name('yas/formes.editTransmit');
    /* Route CUD pour les transmissions */
    Route::post('/transmission/store', [TransmitController::class, 'store'])->name('transmission.store');
    Route::put('/transmission/update/{id}',[TransmitController::class, 'update'])->name('transmission.update');
    Route::get('/transmission/{id}', [TransmitController::class, 'destroy'])->name('transmission.destroy');
    /* Route CUD pour les transmissions */

   
});