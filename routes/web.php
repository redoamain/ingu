<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Route::view('/', 'welcome')->name('home');
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/export/excel', [ProductController::class, 'export'])->name('products.export');
