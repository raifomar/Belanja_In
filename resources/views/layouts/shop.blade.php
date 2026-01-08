<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BelanjaIn - Shop</title>
    
    {{-- Google Fonts (Mirip dengan gambar) --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
    {{-- CSS Khusus Shop --}}
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
    
    @livewireStyles
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar">
        <div class="logo">
            {{-- Ganti 'logo_gabite.png' sesuai nama file Anda --}}
            <img src="{{ asset('images/logo BelanjaIn.png') }}" alt="Logo" style="height: 100px;">
        </div>
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="#">All Products</a>
            <a href="#">Our Story</a>
            <a href="#">Contact</a>
        </div>
        <div class="nav-links">
            <a href="#">Profil</a>
            <a href="#">Cart</a>
        </div>
    </nav>

    {{-- KONTEN UTAMA (Dari Livewire) --}}
    {{ $slot }}

    {{-- FOOTER --}}
    <footer class="footer">
        <p>&copy; 2025 BelanjaIn. All rights reserved.</p>
    </footer>

    @livewireScripts
</body>
</html>