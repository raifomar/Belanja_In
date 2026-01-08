<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class Shop extends Component
{
    public $category = 'All'; // Filter kategori (opsional)

    public function render()
    {
        // Ambil semua produk
        $products = Product::all();

        return view('livewire.shop', [
            'products' => $products
        ])->layout('layouts.shop'); // Kita akan buat layout khusus shop
    }
}