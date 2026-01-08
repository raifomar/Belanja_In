<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- PENTING: Panggil model Product
use Illuminate\Http\Request;

class CashierController extends Controller
{
    /**
     * Menampilkan halaman utama kasir dengan daftar produk.
     */
    public function index()
    {
        // 1. Ambil semua data produk dari database
        $products = Product::all();

        // 2. Kirim data tersebut ke sebuah view bernama 'cashier.index'
        return view('cashier', [
            'products' => $products
        ]);
    }
}