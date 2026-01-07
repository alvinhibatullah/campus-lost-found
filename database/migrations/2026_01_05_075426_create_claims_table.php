<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Kita gunakan Schema::dropIfExists dulu biar aman kalau tabel nyangkut
        Schema::dropIfExists('claims'); 

        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // KOLOM STANDALONE (Data barang disimpan disini)
            $table->string('item_name');       
            $table->string('category')->nullable();
            $table->string('location_found')->nullable();
            $table->date('date_found')->nullable();
            $table->text('description')->nullable();
            
            // STATUS
            $table->string('status')->default('pending');
            $table->text('claim_reason')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claims');
    }
};