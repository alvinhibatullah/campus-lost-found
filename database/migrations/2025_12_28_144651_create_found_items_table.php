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
     Schema::create('found_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('nama_barang'); 
        $table->string('lokasi_ditemukan');
        $table->date('tanggal_ditemukan');
        $table->text('deskripsi');
        $table->string('foto_barang')->nullable();
        $table->string('koordinat_lokasi');
        $table->string('status')->default('Unclaimed');
        $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('found_items');
    }
};
