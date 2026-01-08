<div style="max-width: 600px; margin: 40px auto; font-family: 'Poppins', sans-serif;">
    
    {{-- Header --}}
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #1F3A5F; font-weight: bold;">Tambah Produk Baru</h2>
        <p style="color: #000000;">Masukkan detail menu baru di bawah ini</p>
    </div>

    {{-- Form Container --}}
    <div style="background-color: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e3d5bf;">
        
        <form wire:submit.prevent="save">
            
            {{-- Input Nama --}}
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #1F3A5F; font-weight: bold; margin-bottom: 8px;">Nama Produk</label>
                <input type="text" wire:model="name" placeholder="Contoh: Gabin Coklat" 
                       style="width: 100%; padding: 12px; border: 2px solid #1F3A5F; border-radius: 8px; outline: none; transition: 0.3s;">
                @error('name') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            {{-- Input Harga & Stok (Grid) --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                
                {{-- Harga --}}
                <div>
                    <label style="display: block; color: #1F3A5F; font-weight: bold; margin-bottom: 8px;">Harga (Rp)</label>
                    <input type="number" wire:model="base_price" placeholder="15000" 
                           style="width: 100%; padding: 12px; border: 2px solid #1F3A5F; border-radius: 8px;">
                    @error('base_price') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>

                {{-- Stok --}}
                <div>
                    <label style="display: block; color: #1F3A5F; font-weight: bold; margin-bottom: 8px;">Stok Awal</label>
                    <input type="number" wire:model="stock" placeholder="50" 
                           style="width: 100%; padding: 12px; border: 2px solid #1F3A5F; border-radius: 8px;">
                    @error('stock') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Upload Gambar --}}
            <div style="margin-bottom: 30px;">
                <label style="display: block; color: #1F3A5F; font-weight: bold; margin-bottom: 8px;">Foto Produk</label>
                
                <input type="file" wire:model="image" 
                       style="width: 100%; padding: 10px; background: #6eaaff; border-radius: 8px; border: 1px dashed #1F3A5F;">
                
                <div wire:loading wire:target="image" style="color: #1F3A5F; font-size: 0.9rem; margin-top: 5px;">
                    Sedang mengupload...
                </div>

                {{-- Preview Gambar --}}
                @if ($image)
                    <div style="margin-top: 10px; text-align: center;">
                        <img src="{{ $image->temporaryUrl() }}" style="max-height: 150px; border-radius: 10px; border: 2px solid #5a4635;">
                    </div>
                @endif
                @error('image') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('stok') }}" style="flex: 1; text-decoration: none;">
                    <button type="button" style="width: 100%; padding: 12px; background-color: #ddd; color: #555; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                        Batal
                    </button>
                </a>
                
                <button type="submit" style="flex: 2; padding: 12px; background-color: #1F3A5F; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s;">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>