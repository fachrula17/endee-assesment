<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;

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

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'root'])->name('root');

Auth::routes();
Route::middleware([Authenticate::class])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/client', [App\Http\Controllers\ClientController::class, 'index'])->name('client');
    Route::get('/client/create', [App\Http\Controllers\ClientController::class, 'create'])->name('client.create');
    Route::post('/client/datatables', [App\Http\Controllers\ClientController::class, 'datatables'])->name('client.datatables');
    Route::post('/client/store', [App\Http\Controllers\ClientController::class, 'store'])->name('client.store');
    Route::get('/client/edit/{param}', [App\Http\Controllers\ClientController::class, 'edit'])->name('client.edit');
    Route::put('/client/update/{id}', [App\Http\Controllers\ClientController::class, 'update'])->name('client.update');
    Route::delete('/client/delete/{id}', [App\Http\Controllers\ClientController::class, 'delete'])->name('client.delete');

    Route::get('/order', [App\Http\Controllers\OrderController::class, 'index'])->name('order');
    Route::get('/order/create', [App\Http\Controllers\OrderController::class, 'create'])->name('order.create');
    Route::post('/order/datatables', [App\Http\Controllers\OrderController::class, 'datatables'])->name('order.datatables');
    Route::post('/order/store', [App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
    Route::get('/order/edit/{param}', [App\Http\Controllers\OrderController::class, 'edit'])->name('order.edit');
    Route::put('/order/update/{id}', [App\Http\Controllers\OrderController::class, 'update'])->name('order.update');
    Route::delete('/order/delete/{id}', [App\Http\Controllers\OrderController::class, 'delete'])->name('order.delete');
    Route::get('/order/print', [App\Http\Controllers\OrderController::class, 'print_report'])->name('order.print');
});