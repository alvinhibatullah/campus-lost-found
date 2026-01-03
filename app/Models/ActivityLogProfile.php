<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogProfile extends Model
{
    use HasFactory;

    /**
     * Tentukan nama tabel secara eksplisit.
     * Karena nama tabel 'activity_logs_profile' tidak mengikuti aturan standar Laravel (plural).
     */
    protected $table = 'activity_logs_profile';

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'user_id',
        'action',
        'description',
    ];

    /**
     * Relasi ke model User.
     * Memudahkan jika nanti ingin menampilkan siapa yang melakukan aktivitas.
     * Contoh: $log->user->name
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}