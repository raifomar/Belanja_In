<div class="stock-container" style="padding: 2rem; background-color: #F8FAFC; min-height: 100vh;">
    
    {{-- HEADER HALAMAN & TOMBOL TAMBAH (DIKEMBALIKAN) --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; width: 100%;">
        <h1 style="color: #1E293B; font-family: 'Poppins', sans-serif; margin: 0;">Stok Makanan</h1>

        {{-- TOMBOL TAMBAH PRODUK BARU --}}
        <a href="{{ route('products.create') }}" style="text-decoration: none;">
            <button style="
                background-color: #28a745; 
                color: white; 
                border: none; 
                padding: 12px 24px; 
                border-radius: 8px; 
                font-weight: 700; 
                cursor: pointer; 
                font-family: 'Poppins', sans-serif;
                display: flex;
                align-items: center;
                gap: 8px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                transition: 0.3s;
            " onmouseover="this.style.backgroundColor='#218838'" onmouseout="this.style.backgroundColor='#28a745'">
                <span style="font-size: 1.2rem; line-height: 1;">+</span> Tambah Produk Baru
            </button>
        </a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session()->has('success'))
        <div style="background-color: #DEF7EC; color: #03543F; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; width: 100%;">
            {{ session('success') }}
        </div>
    @endif

    {{-- LIST PRODUK (Dilebarkan ke 100%) --}}
    <div class="stock-list" style="display: grid; gap: 1.2rem; width: 100%;">
        @forelse ($products as $product)
            <div class="stock-card" style="
                background-color: #1E293B; 
                color: white; 
                padding: 20px 30px; 
                border-radius: 12px; 
                display: flex; 
                justify-content: space-between; 
                align-items: center; {{-- Teks Nama Produk akan lebih turun ke tengah secara vertikal --}}
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                width: 100%; {{-- Melebarkan Card mengikuti kontainer layar --}}
            ">
                <div class="stock-info" style="flex: 1;">
                    {{-- Font Nama Produk Ditebalkan (Weight 700) --}}
                    <h2 style="margin: 0; font-size: 1.4rem; font-weight: 700; letter-spacing: 0.5px;">
                        {{ $product->name }}
                    </h2>
                </div>

                <div class="stock-controls" style="display: flex; align-items: center; gap: 20px;">
                    {{-- Kontrol Stok --}}
                    <div style="display: flex; align-items: center; gap: 20px; background: rgba(255,255,255,0.1); padding: 10px 25px; border-radius: 30px;">
                        <button wire:click="decrementStock({{ $product->id }})" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.8rem; font-weight: bold;">-</button>
                        <span style="font-weight: 800; font-size: 1.3rem; min-width: 40px; text-align: center;">{{ $product->stock }}</span>
                        <button wire:click="incrementStock({{ $product->id }})" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.5rem; font-weight: bold;">+</button>
                    </div>

                    {{-- Tombol Hapus dengan Konfirmasi --}}
                    <button wire:click="deleteProduct({{ $product->id }})" 
                            wire:confirm="Yakin ingin menghapus '{{ $product->name }}'?"
                            style="
                                background-color: #EF4444; 
                                color: white; 
                                border: none; 
                                padding: 12px; 
                                border-radius: 8px; 
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: 0.2s;
                            " onmouseover="this.style.backgroundColor='#DC2626'" onmouseout="this.style.backgroundColor='#EF4444'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 60px; background: white; border-radius: 12px; color: #64748B; width: 100%; border: 2px dashed #E2E8F0;">
                <p style="font-size: 1.1rem; font-weight: 500;">Belum ada produk di database.</p>
            </div>
        @endforelse
    </div>
</div>