<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class StockManager extends Component
{
    public $products;

    public function mount()
    {
        // Mengambil semua produk beserta relasi addon-nya
        $this->products = Product::with('addons')->get();
    }

    public function incrementStock($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->increment('stock');
            $this->mount(); // Refresh data
        }
    }

    public function decrementStock($productId)
    {
        $product = Product::find($productId);
        if ($product && $product->stock > 0) {
            $product->decrement('stock');
            $this->mount(); // Refresh data
        }
    }

    // FUNGSI HAPUS PRODUK
    public function deleteProduct($productId)
    {
        $product = Product::find($productId);
        
        if ($product) {
            // 1. Hapus file gambar jika ada di folder public/images
            if ($product->image) {
                $imagePath = public_path('images/' . $product->image);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            // 2. Hapus data dari database
            $product->delete();
            
            // 3. Refresh data produk di tampilan
            $this->mount();
            
            session()->flash('success', 'Produk berhasil dihapus!');
        }
    }

    public function render()
    {
        return view('livewire.stock-manager')->layout('layouts.app');
    }
}