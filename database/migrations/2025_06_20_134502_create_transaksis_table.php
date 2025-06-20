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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Relasi
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade'); // Donatur
            $table->foreignUuid('panti_id')->constrained('panti_asuhan')->onDelete('cascade'); // Panti tujuan
            
            // Data Transaksi
            $table->string('order_id')->unique(); // ID unik dari Midtrans (e.g. "DONASI-123")
            $table->integer('amount'); // Jumlah donasi
            $table->string('payment_method')->nullable(); // credit_card, bank_transfer, etc
            $table->enum('status', ['pending', 'success', 'expired', 'failed'])->default('pending');
            
            // Data Midtrans
            $table->string('snap_token')->nullable();
            $table->text('payload')->nullable(); // Response lengkap dari Midtrans (JSON)
            
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index('order_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
