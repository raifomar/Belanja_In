<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Livewire\Shop;
use App\Livewire\StockManager;
use App\Livewire\ProductForm;

Route::get('/shop', Shop::class)->name('shop');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return view('cashier.index'); // Atau @livewire('cashier')
    })->name('home');

    // Halaman utama kasir
    Route::get('/', [CashierController::class, 'index'])->name('cashier.index');

    // Halaman stok
    Route::get('/stok', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stok/update/{product}', [StockController::class, 'update'])->name('stock.update');

    // Rute untuk halaman stok (sesuaikan dengan nama yang sudah Anda buat)
    Route::get('/gudang', StockManager::class)->name('stok'); 

    // Rute BARU untuk halaman tambah produk
    Route::get('/produk/tambah', ProductForm::class)->name('products.create');

    // Halaman riwayat
    Route::get('/riwayat', [TransactionController::class, 'history'])->name('transaction.history');

    // Proses pembayaran
    Route::post('/bayar', [TransactionController::class, 'store'])->name('transaction.store');

    // Endpoint untuk mengelola keranjang (bisa juga pakai Livewire/Inertia)
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

    Route::get('/riwayat/export', [HistoryController::class, 'export'])->name('history.export');

});