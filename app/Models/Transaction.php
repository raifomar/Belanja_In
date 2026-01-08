<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',   // <--- PASTIKAN INI ADA
        'total_amount',
        'payment_method',  // <--- PASTIKAN INI ADA
        'cash_paid',
        'change_due',
        'status',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
}