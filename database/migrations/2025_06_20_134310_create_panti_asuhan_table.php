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
    Schema::create('panti_asuhan', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignUuid('user_id')->nullable()->constrained()->onDelete('set null');
        $table->string('nama_panti');
        $table->string('pengurus'); // Tambahkan kolom pengurus
        $table->text('alamat');
        $table->text('deskripsi')->nullable();
        $table->string('foto_profil')->nullable();
        $table->string('dokumen_verifikasi');
        // Sesuaikan enum dengan kebutuhan
        $table->enum('status_verifikasi', ['verified', 'unverified', 'rejected'])->default('unverified');
        $table->string('nomor_rekening')->nullable();
        $table->string('bank')->nullable();
        $table->string('kontak');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panti_asuhan');
    }
};
