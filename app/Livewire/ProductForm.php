<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // Wajib untuk upload gambar
use App\Models\Product;

class ProductForm extends Component
{
    use WithFileUploads;

    public $name;
    public $base_price;
    public $stock;
    public $image; // Menyimpan file gambar sementara

    // Aturan Validasi
    protected $rules = [
        'name' => 'required|string|max:255',
        'base_price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048', // Maksimal 2MB, format gambar
    ];

    public function save()
    {
        $this->validate();

        // Proses Upload Gambar (Jika ada)
        $imageName = null;
        if ($this->image) {
            // Simpan ke folder public/images
            // Catatan: Pastikan Anda sudah setting disk 'real_public' di config/filesystems.php
            // ATAU cara paling gampang (manual move):
            $imageName = time() . '.' . $this->image->getClientOriginalExtension();
            $this->image->storeAs('/', $imageName, 'public_uploads'); 
            // *Nanti saya berikan settingan config filesystems di bawah agar tidak bingung
        }

        // Simpan ke Database
        Product::create([
            'name' => $this->name,
            'base_price' => $this->base_price,
            'stock' => $this->stock,
            'image' => $imageName,
        ]);

        session()->flash('success', 'Produk berhasil ditambahkan!');

        // Kembali ke halaman stok
        return redirect()->route('stok'); 
    }

    public function render()
    {
        return view('livewire.product-form')->layout('layouts.app');
    }
}