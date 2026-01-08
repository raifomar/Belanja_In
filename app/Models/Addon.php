<?php

namespace App\Models; // <-- Pastikan pakai backslash \

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Addon extends Model // <-- Pastikan nama class "Addon"
{
    use HasFactory;
    protected $fillable = ['name', 'additional_price'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_addon');
    }
}