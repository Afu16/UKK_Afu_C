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

    Schema::create('logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        $table->string('action'); // misal: 'create', 'update', 'delete', 'login', 'pinjam'
        $table->string('model')->nullable(); // misal: 'User', 'Buku', 'Peminjaman'
        $table->integer('model_id')->nullable(); // ID data yang diubah
        $table->text('description'); // deskripsi lengkap kejadian
        $table->string('ip_address')->nullable();
        $table->string('user_agent')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
