<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['nama' => 'Elektronik (HP, Laptop, dll)', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dompet & Tas', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dokumen (KTM, KTP, STNK)', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kunci Kendaraan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pakaian & Aksesoris', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Lain-lain', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}