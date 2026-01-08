<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class Cashier extends Component
{
    public $products = [];
    public $cart = [];
    public $notes = [];
    
    // Data Transaksi
    public $customerName = '';       
    public $paymentMethod = 'Tunai'; 
    public $tunai = 0;               // Uang diterima (Untuk Tunai)
    public $kembalian = 0;

    public function mount()
    {
        $this->products = Product::with('addons')->get();
    }

    // --- FUNGSI KERANJANG ---
    public function addToCart($productId, $addonId = null)
    {
        $product = Product::find($productId);
        if (!$product) return;

        $addon = null; $addonPrice = 0; $addonName = '';
        if ($addonId) {
            $addon = $product->addons()->find($addonId);
            $addonPrice = $addon->additional_price;
            $addonName = $addon->name;
        }

        $noteInput = $this->notes[$productId] ?? '';
        $cartKey = $productId . '-' . $addonId . '-' . str_replace(' ', '_', $noteInput);

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity']++;
        } else {
            $this->cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->base_price,
                'quantity' => 1,
                'addon_id' => $addonId,
                'addon_name' => $addonName,
                'addon_price' => $addonPrice,
                'note' => $noteInput,
            ];
        }
        $this->notes[$productId] = ''; 
    }

    public function incrementQuantity($cartKey) {
        if (isset($this->cart[$cartKey])) $this->cart[$cartKey]['quantity']++;
    }

    public function decrementQuantity($cartKey) {
        if (isset($this->cart[$cartKey])) {
            if ($this->cart[$cartKey]['quantity'] > 1) $this->cart[$cartKey]['quantity']--;
            else unset($this->cart[$cartKey]);
        }
    }

    public function setPaymentMethod($method)
    {
        $this->paymentMethod = $method;
        $this->tunai = 0; 
    }

    // ==========================================================
    // BAGIAN 1: PROSES BAYAR TUNAI
    // ==========================================================
    public function submitTransaction()
    {
        if ($this->paymentMethod !== 'Tunai') return;

        if (empty($this->cart)) return;
        if (empty($this->customerName)) {
            session()->flash('error', 'Nama Pelanggan harus diisi!');
            return;
        }

        $total = $this->calculateTotal();

        if ($this->tunai < $total) {
            session()->flash('error', 'Uang tunai kurang!');
            return;
        }
        
        $this->tunai = (float) $this->tunai;

        DB::transaction(function () use ($total) {
            $transaction = $this->createTransactionRecord($total, 'Tunai', 'paid');
            $transaction->cash_paid = $this->tunai; 
            $transaction->change_due = $this->tunai - $total;
            $transaction->save();

            $this->saveTransactionDetails($transaction->id);
        });

        $this->kembalian = $this->tunai - $total;
        $this->resetCartState();
        session()->flash('success', 'Pembayaran Tunai Berhasil!');
    }

    // ==========================================================
    // BAGIAN 2: PROSES BAYAR ONLINE (MIDTRANS) - SEMUA METODE
    // ==========================================================
    public function checkoutMidtrans()
    {
        if (empty($this->cart)) return;
        if (empty($this->customerName)) {
            session()->flash('error', 'Nama Pelanggan harus diisi dulu!');
            return;
        }

        $totalAmount = $this->calculateTotal();

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $orderId = 'TRX-' . rand(1000,9999) . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $this->customerName,
                'email' => 'pelanggan@example.com', // Wajib untuk Bank Transfer
            ],
            // MENGAKTIFKAN SEMUA METODE PEMBAYARAN
            'enabled_payments' => [
                'credit_card', 
                'bank_transfer', 
                'gopay', 
                'shopeepay', 
                'other_qris', 
                'indomaret', 
                'alfamart'
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $this->dispatch('open-midtrans-snap', token: $snapToken);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal koneksi Midtrans: ' . $e->getMessage());
        }
    }

    public function saveTransactionSuccess($result)
    {
        $total = $this->calculateTotal();

        DB::transaction(function () use ($total, $result) {
            // Deteksi metode spesifik dari hasil Midtrans
            $method = $result['payment_type'] ?? 'Online Payment';
            $transaction = $this->createTransactionRecord($total, strtoupper($method), 'paid');
            
            $transaction->cash_paid = $total;
            $transaction->change_due = 0;
            $transaction->save();

            $this->saveTransactionDetails($transaction->id);
        });

        $this->resetCartState();
        session()->flash('success', 'Pembayaran Berhasil!');
    }

    // --- HELPER FUNCTIONS ---
    
    private function calculateTotal() {
        return array_reduce($this->cart, function ($carry, $item) {
            return $carry + (($item['price'] + $item['addon_price']) * $item['quantity']);
        }, 0);
    }

    private function resetCartState() {
        $this->cart = [];
        $this->notes = [];
    }

    private function createTransactionRecord($total, $method, $status) {
        $transaction = new Transaction();
        $transaction->customer_name = $this->customerName;
        $transaction->total_amount = $total;
        $transaction->payment_method = $method;
        $transaction->status = $status;
        return $transaction;
    }

    private function saveTransactionDetails($transactionId) {
        foreach ($this->cart as $item) {
            TransactionDetail::forceCreate([
                'transaction_id' => $transactionId,
                'product_id' => $item['id'],
                'addon_name' => $item['addon_name'],
                'note' => $item['note'],
                'quantity' => $item['quantity'],
                'price_per_item' => $item['price'] + $item['addon_price'],
            ]);

            $product = Product::find($item['id']);
            if($product) {
                $product->stock -= $item['quantity'];
                $product->save();
            }
        }
    }

    public function render()
    {
        return view('livewire.cashier', [
            'totalBelanja' => $this->calculateTotal()
        ]);
    }
}