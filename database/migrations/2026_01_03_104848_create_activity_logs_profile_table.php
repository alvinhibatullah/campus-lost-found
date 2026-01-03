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
        Schema::create('activity_logs_profile', function (Blueprint $table) {
            $table->id();
            // Menghubungkan log ke tabel users (jika user dihapus, log ikut terhapus)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Kolom untuk nama aktivitas (misal: "Update Profil")
            $table->string('action');
            
            // Kolom untuk detail deskripsi (opsional)
            $table->text('description')->nullable();
            
            // Mencatat waktu kapan aktivitas terjadi (created_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs_profile');
    }
};