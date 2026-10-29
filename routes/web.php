<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'lowStock' => Product::lowStock()->orderBy('quantity')->limit(10)->get(),
        'productCount' => Product::count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Produits
    Route::get('products', [ProductController::class, 'index'])
        ->middleware('can:products.view')->name('products.index');

    Route::middleware('can:products.manage')->group(function () {
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Mouvements de stock
    Route::get('stock', [StockMovementController::class, 'index'])
        ->middleware('can:stock.view')->name('stock.index');

    Route::middleware('can:stock.move')->group(function () {
        Route::get('stock/create', [StockMovementController::class, 'create'])->name('stock.create');
        Route::post('stock', [StockMovementController::class, 'store'])->name('stock.store');
    });

    // Catégories
    Route::resource('categories', CategoryController::class)
        ->except('show')->middleware('can:products.manage');

    // Fournisseurs
    Route::resource('suppliers', SupplierController::class)
        ->except('show')->middleware('can:suppliers.manage');

    // Commandes d'achat
    Route::middleware('can:orders.manage')->group(function () {
        Route::resource('purchase-orders', PurchaseOrderController::class)
            ->only(['index', 'create', 'store', 'show']);
        Route::post('purchase-orders/{purchaseOrder}/send', [PurchaseOrderController::class, 'send'])->name('purchase-orders.send');
        Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
        Route::post('purchase-orders/{purchaseOrder}/cancel', [PurchaseOrderController::class, 'cancel'])->name('purchase-orders.cancel');
    });
});

require __DIR__ . '/auth.php';
