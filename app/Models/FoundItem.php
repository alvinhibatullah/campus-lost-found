<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoundItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_barang',
        'lokasi_ditemukan',
        'tanggal_ditemukan',
        'deskripsi',
        'foto_barang',
        'status',
        'koordinat_lokasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}