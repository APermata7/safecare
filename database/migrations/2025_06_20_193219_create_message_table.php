<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('judul', ['Feedback', 'Request panti user'])->default('Feedback');
            $table->enum('role', ['donatur', 'panti', 'admin'])->default('donatur');
            $table->text('message');
            $table->string('file_path')->nullable();
            $table->text('reply')->nullable();
            $table->timestamp('replied_at')->nullable(); // Added for tracking reply time
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};