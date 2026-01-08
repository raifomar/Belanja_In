<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Kasir')</title>
    {{-- Link to your CSS file --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    @livewireStyles
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <a href="/">
                <img src="{{ asset('images/logo BelanjaIn.png') }}" alt="Logo" style="height: 80px;">
            </div>
            <nav>
                <a href="/stok">Stok Makanan</a>
                <a href="/riwayat">Riwayat</a>
                {{-- Form Logout --}}
                {{-- Pastikan class "logout-form" ada di sini --}}
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout" onclick="return confirm('Yakin ingin keluar?')">
                        Keluar
                    </button>
                </form>
            </nav>
        </header>

        <main>
            {{-- Logika Pintar: Cek apakah ini halaman Livewire atau Biasa --}}
            @if(isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </main>
    </div>
    @livewireScripts
</body>
</html>