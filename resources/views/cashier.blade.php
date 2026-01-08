@extends('layouts.app')

@section('title', 'Kasir - Menu Utama')

@section('content')
    {{-- Memanggil komponen Livewire Cashier --}}
    @livewire('cashier')
@endsection