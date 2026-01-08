<div class="row" style="display: flex; gap: 2rem; width: 100%;">
    
    {{-- SCRIPT MIDTRANS (WAJIB ADA) --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    {{-- KOLOM KIRI: MENU MAKANAN --}}
    <div class="menu-section" style="flex: 2;">
        <h1 style="color: #2C3E50;">Menu</h1>
        <div class="product-grid">
            @foreach ($products as $product)
                <div class="product-card">
                    {{-- Gambar Produk --}}
                    @if ($product->image)
                        <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 150px; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/150" alt="{{ $product->name }}">
                    @endif

                    <h3>{{ $product->name }}</h3>
                    <p class="price">Rp {{ number_format($product->base_price) }}</p>
                    
                    <p class="stock-info {{ $product->stock <= 10 ? 'low-stock' : '' }}">
                        Stok: {{ $product->stock }}
                    </p>

                    {{-- Input Catatan Sebelum Tambah ke Keranjang --}}
                    <div style="margin-bottom: 10px;">
                        <input type="text" wire:model="notes.{{ $product->id }}" placeholder="Catatan (opsional)..." style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                    </div>

                    @if ($product->addons->isNotEmpty())
                        <div class="addon-options">
                            @foreach ($product->addons as $addon)
                                <button wire:click="addToCart({{ $product->id }}, {{ $addon->id }})">
                                    {{ $addon->name }} (+ Rp {{ number_format($addon->additional_price) }})
                                </button>
                            @endforeach
                        </div>
                        <button class="btn-tambah" wire:click="addToCart({{ $product->id }})">Tambah (Original)</button>
                    @else
                        <button class="btn-tambah" wire:click="addToCart({{ $product->id }})">Tambah</button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- KOLOM KANAN: KERANJANG --}}
    <div class="cart-section" style="flex: 1; background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #ddd;">
        <h2 style="color: #2C3E50;">Keranjang</h2>

        <div style="margin-bottom: 1.5rem;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #2C3E50;">Nama Pelanggan:</label>
            <input type="text" wire:model="customerName" placeholder="Masukkan nama..." oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')" 
            style="width: 100%; padding: 10px; border: 2px solid #2C3E50; border-radius: 5px;">
        </div>

        @if (session()->has('error'))
            <div style="color: red; margin-bottom: 1rem; font-weight: bold; background: #ffe6e6; padding: 10px; border-radius: 5px;">
                {{ session('error') }}
            </div>
        @endif
        
        @if (session()->has('success'))
            <div style="color: green; margin-bottom: 1rem; font-weight: bold; background: #e6fffa; padding: 10px; border-radius: 5px;">
                {{ session('success') }} <br>
                @if($kembalian > 0) Kembalian: Rp {{ number_format($kembalian) }} @endif
            </div>
        @endif

        {{-- LIST ITEM KERANJANG DENGAN CATATAN --}}
        <div style="max-height: 300px; overflow-y: auto; margin-bottom: 1rem;">
            @forelse ($cart as $key => $item)
                <div class="cart-item" style="display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #eee;">
                    <div class="cart-item-details">
                        <div style="font-weight: bold; color: #2C3E50;">{{ $item['name'] }}</div>
                        
                        @if ($item['addon_name']) 
                            <div style="font-size: 0.85rem; color: #7F8C8D;">+ {{ $item['addon_name'] }}</div> 
                        @endif
                        
                        {{-- MENAMPILKAN CATATAN JIKA ADA --}}
                        @if (!empty($item['note']))
                            <div style="font-size: 0.8rem; color: #d9534f; font-style: italic; margin-top: 2px;">
                                Catatan: "{{ $item['note'] }}"
                            </div>
                        @endif
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px;">
                        <button wire:click="decrementQuantity('{{ $key }}')" 
                                style="padding: 2px 8px; border: 1px solid #ddd; background: #f8f9fa; cursor: pointer; border-radius: 3px;">-</button>
                        <span style="font-weight: bold; min-width: 20px; text-align: center;">{{ $item['quantity'] }}</span>
                        <button wire:click="incrementQuantity('{{ $key }}')" 
                                style="padding: 2px 8px; border: 1px solid #ddd; background: #f8f9fa; cursor: pointer; border-radius: 3px;">+</button>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: #888; padding: 20px 0;">Keranjang masih kosong.</p>
            @endforelse
        </div>

        {{-- TOTAL DAN PEMBAYARAN --}}
        <div class="cart-total" style="border-top: 2px solid #2C3E50; padding-top: 1rem;">
            <div class="total-row" style="font-size: 1.5rem; margin-bottom: 1rem; color: #2C3E50; display: flex; justify-content: space-between;">
                <span>Total</span>
                <span>Rp {{ number_format($totalBelanja) }}</span>
            </div>

            <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #2C3E50;">Metode Pembayaran:</label>
            <div style="display: flex; gap: 10px; margin-bottom: 1rem;">
                <button wire:click="setPaymentMethod('Tunai')" class="btn-option {{ $paymentMethod === 'Tunai' ? 'active' : '' }}" style="flex: 1; padding: 10px; color: {{ $paymentMethod === 'Tunai' ? '#ffffff' : '#2C3E50' }}; text-shadow: 0.5px 0.5px 0px rgba(0,0,0,0.5); 
        -webkit-text-stroke: 0.2px black; cursor: pointer;">Tunai</button>
                <button wire:click="setPaymentMethod('QRIS')" class="btn-option {{ $paymentMethod === 'QRIS' ? 'active' : '' }}" style="flex: 1; padding: 10px; cursor: pointer;">Non-Tunai</button>
            </div>

            @if ($paymentMethod === 'Tunai')
                <div style="margin-bottom: 1rem; color: #2C3E50;">
                    <label style="display: block; margin-bottom: 5px;">Uang Diterima :</label>
                    <input type="number" wire:model="tunai" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1.2rem;">
                </div>
                <button wire:click="submitTransaction" style="width: 100%; background: #28a745; color: #fff; padding: 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;" {{ empty($cart) ? 'disabled' : '' }}>
                    BAYAR TUNAI
                </button>
            @endif

            @if ($paymentMethod === 'QRIS')
                <div style="text-align: center; margin-bottom: 1rem; color: #666; font-size: 0.8rem;">
                    <p>Pilih Bank, E-Wallet, atau QRIS di halaman selanjutnya.</p>
                </div>
                <button wire:click="checkoutMidtrans" style="width: 100%; background: #4a6fa5; color: #fff; padding: 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;" {{ empty($cart) ? 'disabled' : '' }}>
                    BAYAR NON-TUNAI
                </button>
            @endif
        </div>
    </div>
</div>

@script
<script>
    Livewire.on('open-midtrans-snap', (event) => {
        window.snap.pay(event.token, {
            onSuccess: function(result){ @this.call('saveTransactionSuccess', result); },
            onPending: function(result){ alert("Menunggu pembayaran..."); },
            onError: function(result){ alert("Pembayaran gagal!"); },
            onClose: function(){ alert('Pembayaran dibatalkan'); }
        });
    });
</script>
@endscript