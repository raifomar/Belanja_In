<div>
    {{-- HERO SECTION --}}
    <div class="hero-section">
        <div class="hero-content">
            <h1>BELANJAIN <br> Foods, Drinks & Snacks</h1> 
            <p>Belanja Semua Aja</p>
            <button class="btn-shop-now">Shop Now</button>
        </div>
        {{-- Gambar Background Hero (Ganti dengan gambar makanan Anda) --}}
        <img src="{{ asset('images/gambar makanan.jpg') }}" class="hero-bg" alt="Hero">
    </div>

    {{-- RUNNING TEXT / MARQUEE --}}
    <div class="marquee">
        <p>Free Shipping above Rp 50.000 • Fresh Ingredients</p>
    </div>

    {{-- KATEGORI (Lingkaran) --}}
    <div class="container">
        <h2 class="section-title">Belanja Collection</h2>
        <p class="section-subtitle">Shop by Preference</p>

        <div class="category-list">
            <div class="cat-item active">Best Sellers</div>
            <div class="cat-item">Main Corse</div>
            <div class="cat-item">Snack</div>
            <div class="cat-item">Drinks</div>
            <div class="cat-item">Bundles</div>
        </div>

        {{-- PRODUCT GRID --}}
        <div class="product-grid">
            @foreach ($products as $product)
            <div class="product-card">
                {{-- Badge Bestseller (Hardcode dulu atau ambil dari DB) --}}
                <span class="badge">Bestseller!</span>

                {{-- Gambar Produk --}}
                <div class="product-img-wrapper">
                    <img src="{{ $product->image ? asset('images/'.$product->image) : 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
                </div>

                {{-- Detail Produk --}}
                <div class="product-info">
                    <h3>{{ $product->name }}</h3>
                    <p class="price">Rp {{ number_format($product->base_price) }}</p>

                    {{-- Pilihan Berat/Varian (Hiasan UI) --}}
                    <div class="variant-selector">
                        <span>1 Pcs</span>
                        <span>▼</span>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="card-actions">
                        <div class="qty-control">
                            <button>-</button>
                            <span>1</span>
                            <button>+</button>
                        </div>
                        <button class="btn-add-cart">Add to cart</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    {{-- BOTTOM BANNER (Sesuai Gambar) --}}
    <div class="bottom-banner">
        <div class="banner-content">
            <h2>BelanjaIn</h2>
            <p>BelanjaIn | Belanja Semua Aja</p>
        </div>
    </div>
</div>