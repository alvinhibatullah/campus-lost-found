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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->unique(); // Menyimpan Google ID
            $table->string('name'); // Menyimpan nama pengguna
            $table->string('email')->unique(); // Menyimpan email pengguna
            $table->string('avatar')->nullable(); // Menyimpan avatar pengguna
            $table->string('password')->nullable(); // Password default (bisa diatur otomatis)
            $table->timestamps(); // Kolom waktu dibuat dan diperbarui
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
