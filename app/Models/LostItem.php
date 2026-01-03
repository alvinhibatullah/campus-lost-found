<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostItem extends Model
{
    use HasFactory;

    protected $table = 'lost_items';

    protected $fillable = [
        'user_id',
        'category_id', // Pastikan kolom ini ada di database
        'nama_barang',
        'deskripsi',
        'tanggal_hilang',
        'koordinat_lokasi',
        'foto_barang',
        'status',
    ];

    // Relasi ke User (Pemilik barang)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Category (Jenis barang) -> TAMBAHKAN INI
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}