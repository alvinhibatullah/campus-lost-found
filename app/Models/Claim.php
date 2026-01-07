<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        // Field barang sekarang milik Claim sepenuhnya
        'item_name',
        'category',
        'location_found',
        'date_found',
        'description',
        // Status
        'status',
        'claim_reason',
        'proof_image'
    ];

    protected $casts = [
        'date_found' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // HAPUS function item() karena kita sudah tidak berelasi!

    // Helper UI Warna Badge (Tetap sama)
    public function statusClass(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected', 'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    // Helper UI Label Teks
    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'approved' => 'Klaim Diterima',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }
}