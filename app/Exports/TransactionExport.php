<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Opsional: Agar lebar kolom otomatis

class TransactionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * Mengambil data transaksi dari database
    */
    public function collection()
    {
        return Transaction::with('details.product')->latest()->get();
    }

    /**
     * Mengatur Judul Kolom di Excel (Baris Pertama)
     */
    public function headings(): array
    {
        return [
            'ID',
            'Tanggal & Waktu',
            'Nama Pelanggan', // <--- TAMBAHKAN INI
            'Metode Pembayaran',
            'Daftar Item', 
            'Total Bayar',
            'Uang Diterima',
            'Kembalian',
        ];
    }

    /**
     * Mengatur isi data per baris
     */
    public function map($transaction): array
    {
        // Menggabungkan nama item menjadi satu string
        $itemsList = $transaction->details->map(function($detail) {
            $addonInfo = $detail->addon_name ? " (+ {$detail->addon_name})" : "";
            $noteInfo = $detail->note ? " [Note: {$detail->note}]" : "";
            return "{$detail->quantity}x {$detail->product->name}{$addonInfo}{$noteInfo}";
        })->implode(', ');

        return [
            $transaction->id,
            $transaction->created_at->format('d-m-Y H:i'),
            $transaction->customer_name ?? 'Tanpa Nama', // <--- TAMBAHKAN INI (Isi nama pelanggan)
            $transaction->payment_method,
            $itemsList,
            $transaction->total_amount,
            $transaction->cash_paid,
            $transaction->change_due,
        ];
    }
}