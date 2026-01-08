@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<style>
    /* CSS Lokal untuk halaman ini */
    .history-wrapper {
        width: 100%;
        max-width: 800px; /* Batasi lebar agar rapi di tengah */
        margin: 0 auto;   /* Posisi tengah */
    }

    /* Bagian Header Halaman (Judul & Tombol Download) */
    .history-header {
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #dcdcdc;
    }

    /* Container Kartu (Wajib Flex-Column agar menyusun ke bawah) */
    .history-list {
        display: flex;
        flex-direction: column; /* KUNCI: Agar kartu tersusun ke bawah */
        gap: 1.5rem;            /* Jarak antar kartu */
    }

    /* Kartu Transaksi */
    .transaction-card {
        background-color: #2C3E50;
        color: #f5f0e1;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        width: 100%; /* Lebar penuh mengikuti container */
    }

    /* Header di dalam Kartu (Nama, Tanggal, Badge Pembayaran) */
    .transaction-card-header {
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        border-bottom: 1px solid #f5f0e1;
        padding-bottom: 0.8rem;
        margin-bottom: 1rem;
    }

    .header-left {
        display: flex;
        flex-direction: column;
    }

    .customer-name {
        font-weight: bold;
        font-size: 1.2rem;
    }

    .transaction-date {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    /* Badge Metode Pembayaran */
    .payment-badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: bold;
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
    }

    /* Item Barang */
    .transaction-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    /* Info Tambahan (Topping & Catatan) */
    .item-extra-info {
        margin-left: 20px;
        margin-bottom: 8px;
        border-left: 2px solid rgba(245, 240, 225, 0.3); /* Garis tipis di kiri */
        padding-left: 8px;
    }

    .addon-text {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .note-text {
        font-size: 0.85rem;
        color: #ffcccc; /* Warna merah muda lembut */
        font-style: italic;
        margin-top: 2px;
    }

    /* Total Harga */
    .transaction-total {
        border-top: 1px solid #f5f0e1;
        padding-top: 1rem;
        margin-top: 1rem;
        font-weight: bold;
        font-size: 1.3rem;
        display: flex;
        justify-content: space-between;
    }

    /* Tombol Download Excel */
    .btn-download {
        background-color: #28a745; 
        color: white; 
        border: none; 
        padding: 10px 20px; 
        border-radius: 5px; 
        font-weight: bold; 
        cursor: pointer;
        text-decoration: none;
        font-family: 'Poppins', sans-serif;
        transition: background-color 0.2s;
    }
    .btn-download:hover {
        background-color: #218838;
    }
</style>

<div class="history-wrapper">
    
    {{-- 1. HEADER HALAMAN --}}
    <div class="history-header">
        <h1>Riwayat Transaksi</h1>
        
        <a href="{{ route('history.export') }}" style="text-decoration: none;">
            <button class="btn-download">
                Download Excel (.xlsx)
            </button>
        </a>
    </div>

    {{-- 2. DAFTAR RIWAYAT --}}
    <div class="history-list">
        @forelse ($transactions as $transaction)
            <div class="transaction-card">
                
                {{-- Header Kartu: Nama & Badge --}}
                <div class="transaction-card-header">
                    <div class="header-left">
                        <span class="customer-name">{{ $transaction->customer_name ?? 'Pelanggan Umum' }}</span>
                        <span class="transaction-date">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    {{-- Badge Warna: Coklat (Tunai) / Biru (QRIS) --}}
                    <span class="payment-badge" 
                          style="background-color: {{ $transaction->payment_method == 'Tunai' ? '#2ECC71' : '#F39C12' }};">
                        {{ $transaction->payment_method }}
                    </span>
                </div>

                {{-- Daftar Item --}}
                @foreach ($transaction->details as $detail)
                    {{-- Nama & Harga Produk --}}
                    <div class="transaction-item">
                        <span class="item-info">{{ $detail->quantity }}x {{ $detail->product->name }}</span>
                        <span class="item-price">Rp {{ number_format($detail->price_per_item * $detail->quantity) }}</span>
                    </div>
                    
                    {{-- Info Tambahan (Topping & Catatan) --}}
                    @if ($detail->addon_name || $detail->note)
                        <div class="item-extra-info">
                            @if ($detail->addon_name)
                                <div class="addon-text">+ {{ $detail->addon_name }}</div>
                            @endif

                            @if ($detail->note)
                                <div class="note-text">Catatan: "{{ $detail->note }}"</div>
                            @endif
                        </div>
                    @endif
                @endforeach

                {{-- Total Bayar --}}
                <div class="transaction-total">
                    <span>Total</span>
                    <span>Rp {{ number_format($transaction->total_amount) }}</span>
                </div>
            </div>
        @empty
            <div style="text-align: center; margin-top: 3rem; color: #888;">
                <p style="font-size: 1.2rem;">Belum ada riwayat transaksi.</p>
                <p>Transaksi yang Anda lakukan akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection