<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimDetail extends Model
{
    protected $fillable = [
        'claim_id',
        'field_key',
        'field_value',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
