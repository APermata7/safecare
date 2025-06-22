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
            $table->foreignUuid('user_id')->nullable()->constrained()->onDelete('set null'); // Donatur
            $table->foreignUuid('panti_id')->nullable()->constrained('panti_asuhan')->onDelete('set null'); // Panti tujuan
            
            // Data Transaksi
            $table->string('order_id')->unique(); // ID unik dari Midtrans (e.g. "DONASI-123")
            $table->integer('amount'); // Jumlah donasi
            $table->enum('payment_method', ['bank transfer', 'QRIS'])->nullable();
            $table->enum('status', ['waiting confirmation', 'success', 'canceled'])->default('waiting confirmation'); // Status transaksi
            $table->boolean('hide_name')->default(false);

            // Data Midtrans
            $table->string('snap_token')->nullable();
            
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
