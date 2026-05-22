<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Elektronik (HP, Laptop, dll)',
            'Dompet & Tas',
            'Dokumen (KTM, KTP, STNK)',
            'Kunci Kendaraan',
            'Pakaian & Aksesoris',
            'Lain-lain',
        ];

        foreach ($categories as $nama) {
            DB::table('categories')->updateOrInsert(
                ['nama' => $nama],
                [
                    'nama' => $nama,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}