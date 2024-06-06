<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;

Route::get('/', function () {
    return view('dashboard',[
        "title"=>"Dashboard"
    ]);
})->middleware('auth');

Route::resource('kategori',CategoryController::class)
->except('show','destroy','create')->middleware('auth');
Route::resource('pelanggan',CustomerController::class)->except('destroy')->middleware('auth');
Route::resource('produk',ProductController::class)->middleware('auth');
Route::resource('pengguna',UserController::class)->except('destroy','show','update','edit')->middleware('auth');

Route::get('login',[LoginController::class,'loginView'])->name('login');
Route::post('login',[LoginController::class,'authenticate']);
Route::post('logout',[LoginController::class,'logout'])->middleware('auth');

Route::get('penjualan',function(){
    return view('penjualan.index',[
        "title"=>"Penjualan"
    ]);
})->middleware('auth');

Route::get('order',function(){
    return view('penjualan.orders',[
        "title"=>"Order"
    ]);
})->middleware('auth');

Route::get('cetakReceipt',[CetakController::class,'receipt'])->name('cetakReceipt')->middleware('auth');

Route::get('/',[WelcomeController::class,'welcome'])->middleware('auth');