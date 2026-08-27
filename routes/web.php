<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\RejectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PosController;

/*
|--------------------------------------------------------------------------
| Web Routes - Calathea Coffee Complete System
|--------------------------------------------------------------------------
*/

// Auth Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout Route (Auth Only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Auth Only)
Route::middleware('auth')->group(function () {
    // 0. POS Tablet Interface Routes (Majoo Style)
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [PosController::class, 'store'])->name('pos.checkout');

    // 1. Penjualan Kopi Routes
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::post('/sales/categories', [SaleController::class, 'storeCategory'])->name('sales.categories.store');
    Route::post('/sales/bulk-delete', [SaleController::class, 'bulkDelete'])->name('sales.bulk-delete');
    Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
    Route::get('/sales/export-pdf', [SaleController::class, 'exportPdf'])->name('sales.export-pdf');

    // 2. Pengeluaran Harian Routes
    Route::get('/', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::get('/expenses/export-pdf', [ExpenseController::class, 'exportPdf'])->name('expenses.export-pdf');

    // 3. Stok Bahan Baku Routes
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::post('/stocks', [StockController::class, 'store'])->name('stocks.store');
    Route::post('/stocks/categories', [StockController::class, 'storeCategory'])->name('stocks.categories.store');
    Route::put('/stocks/{stock}', [StockController::class, 'update'])->name('stocks.update');
    Route::post('/stocks/{stock}/adjust', [StockController::class, 'adjustStock'])->name('stocks.adjust');
    Route::delete('/stocks/{stock}', [StockController::class, 'destroy'])->name('stocks.destroy');
    Route::get('/stocks/export-pdf', [StockController::class, 'exportPdf'])->name('stocks.export-pdf');

    // 4. Bahan Reject / Waste Routes
    Route::get('/rejects', [RejectController::class, 'index'])->name('rejects.index');
    Route::post('/rejects', [RejectController::class, 'store'])->name('rejects.store');
    Route::post('/rejects/categories', [RejectController::class, 'storeCategory'])->name('rejects.categories.store');
    Route::post('/rejects/baristas', [RejectController::class, 'storeBarista'])->name('rejects.baristas.store');
    Route::put('/rejects/{reject}', [RejectController::class, 'update'])->name('rejects.update');
    Route::delete('/rejects/{reject}', [RejectController::class, 'destroy'])->name('rejects.destroy');
    Route::get('/rejects/export-pdf', [RejectController::class, 'exportPdf'])->name('rejects.export-pdf');

    // 5. Inventaris Peralatan Routes
    Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');
    Route::post('/inventories', [InventoryController::class, 'store'])->name('inventories.store');
    Route::post('/inventories/categories', [InventoryController::class, 'storeCategory'])->name('inventories.categories.store');
    Route::put('/inventories/{inventory}', [InventoryController::class, 'update'])->name('inventories.update');
    Route::delete('/inventories/{inventory}', [InventoryController::class, 'destroy'])->name('inventories.destroy');
    Route::get('/inventories/export-pdf', [InventoryController::class, 'exportPdf'])->name('inventories.export-pdf');
});
