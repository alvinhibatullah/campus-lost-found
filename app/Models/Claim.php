<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'claim_reason',
        'incident_at',
        'incident_location',
        'owner_name',
        'nim',
        'contact_phone',
        'ownership_proof',
        'attachments',
        'status',
        'is_active',
    ];

    protected $casts = [
        'incident_at' => 'datetime',
        'attachments' => 'array',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Models\LostItem::class, 'item_id');
        // ganti kalau tabel item kamu beda
    }

    // helper UI
    public function statusLabel(): string
    {
        return match ($this->status) {
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'need_more_proof' => 'Need Info',
            'approved' => 'Accepted',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }
}