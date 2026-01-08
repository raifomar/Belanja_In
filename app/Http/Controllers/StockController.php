<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Panggil model Product

class StockController extends Controller
{
    public function index()
    {
        // Ambil semua data dari model Product
        $products = Product::all();

        // Kirim data ke view bernama 'stock.index'
        return view('stock', ['products' => $products]);
    }
}
