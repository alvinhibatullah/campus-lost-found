<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom nim, fakultas, dan angkatan.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom baru setelah kolom email
            $table->string('nim', 20)->nullable()->after('email');
            $table->string('fakultas', 100)->nullable()->after('nim');
            $table->integer('angkatan')->nullable()->after('fakultas');
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus kolom jika rollback.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim', 'fakultas', 'angkatan']);
        });
    }
};