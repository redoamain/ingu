<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Route::view('/', 'welcome')->name('home');
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/export/excel', [ProductController::class, 'export'])->name('products.export');
Route::get('/products/export/csv', [ProductController::class, 'exportCsv'])->name('products.export.csv');
Route::get('/products/export/excel-image', [ProductController::class, 'exportWithImage'])->name('products.export.image');
