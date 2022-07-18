<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SalesOrderController;


Route::get('/', [AuthController::class, 'showFormLogin']);
Route::get('login', [AuthController::class, 'showFormLogin'])->name('form-login');;
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::get('register', [AuthController::class, 'showFormRegister'])->name('form-register');;
Route::post('register', [AuthController::class, 'register'])->name('register');


Route::group(['middleware' => 'auth'], function () {    

    Route::resource('products', ProductController::class)->except(['show', 'create']);
    Route::get('data-products', [ProductController::class, 'data'])->name('products.data');

    Route::resource('customers', CustomerController::class)->except(['show', 'create']);
    Route::get('data-customers', [CustomerController::class, 'data'])->name('customers.data');

    Route::resource('sales-orders', SalesOrderController::class)->except(['show', 'create']);
    Route::get('data-sales-orders', [SalesOrderController::class, 'data'])->name('sales-orders.data');

 
    Route::get('/logout', function() {
        Auth::logout();
        return redirect('/login');
    })->name('logout');    
 
});


