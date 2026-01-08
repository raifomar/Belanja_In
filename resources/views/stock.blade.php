@extends('layouts.app')

@section('title', 'Manajemen Stok Makanan')

@section('content')
    {{-- Memanggil komponen Livewire untuk manajemen stok --}}
    @livewire('stock-manager')
@endsection