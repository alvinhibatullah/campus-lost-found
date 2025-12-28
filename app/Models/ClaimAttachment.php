<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimAttachment extends Model
{
    protected $fillable = [
        'claim_id',
        'file_path',
        'file_name',
        'mime_type',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
