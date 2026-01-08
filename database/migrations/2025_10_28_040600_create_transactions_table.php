<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_amount', 10, 2); // <- Apakah namanya sama?
            $table->string('payment_method')->default('Tunai');
            $table->string('status')->default('pending');
            $table->string('snap_token')->nullable();
            $table->decimal('cash_paid', 10, 2);  // <- Apakah namanya sama?
            $table->decimal('change_due', 10, 2); // <- Apakah namanya sama?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
