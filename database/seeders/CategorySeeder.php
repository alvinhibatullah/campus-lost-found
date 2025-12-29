<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    \App\Models\Category::create(['nama' => 'Elektronik']);
    \App\Models\Category::create(['nama' => 'Pakaian']);
    \App\Models\Category::create(['nama' => 'Dokumen']);
    \App\Models\Category::create(['nama' => 'Aksesoris']);
    \App\Models\Category::create(['nama' => 'Lain-lain']);
    }
}
