<?php

namespace App\Http\Controllers;

use App\Models\Transaction; // <-- PENTING: Panggil model Transaction
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionExport;

class HistoryController extends Controller
{
    public function index()
    {
        // Ambil transaksi beserta relasi 'details' dan 'product' di dalamnya
        // Diurutkan dari yang paling baru
        $transactions = Transaction::with('details.product')->latest()->get();

        return view('history.index', [
            'transactions' => $transactions
        ]);
    }

    public function export()
    {
        // Mendownload file dengan nama 'laporan-transaksi.xlsx'
        return Excel::download(new TransactionExport, 'laporan-transaksi.xlsx');
    }
}