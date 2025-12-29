<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimVerification extends Model
{
    protected $fillable = [
        'claim_id',
        'verified_by',
        'decision',
        'note',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
