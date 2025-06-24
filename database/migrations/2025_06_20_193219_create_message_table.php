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
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->enum('judul', ['Feedback', 'Request panti user'])->default('Feedback');
            $table->enum('role', ['donatur', 'panti', 'admin'])->default('donatur');
            $table->text('message');
            $table->string('file_path')->nullable();
            $table->timestamps();
            $table->text('reply')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
