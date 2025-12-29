<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lost_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');           
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('nama_barang');
            $table->text('deskripsi');
            $table->date('tanggal_hilang');
            $table->string('koordinat_lokasi')->nullable();      
            $table->string('foto_barang')->nullable();
            $table->enum('status', ['Searching', 'Found', 'Closed'])->default('Searching');   
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lost_items');
    }
};