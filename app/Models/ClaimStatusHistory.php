<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimStatusHistory extends Model
{
    protected $fillable = [
        'claim_id',
        'changed_by',
        'from_status',
        'to_status',
        'note',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
