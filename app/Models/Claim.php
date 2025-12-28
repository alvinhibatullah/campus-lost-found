<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Item;
use App\Models\ClaimDetail;
use App\Models\ClaimAttachment;
use App\Models\ClaimVerification;
use App\Models\ClaimStatusHistory;

class Claim extends Model
{
    protected $fillable = [
        'user_id','item_id','claim_reason','incident_at','incident_location',
        'status','admin_note','is_active'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function item() { return $this->belongsTo(Item::class); }

    public function details() { return $this->hasMany(ClaimDetail::class); }
    public function attachments() { return $this->hasMany(ClaimAttachment::class); }
    public function verifications() { return $this->hasMany(ClaimVerification::class); }
    public function statusHistories() { return $this->hasMany(ClaimStatusHistory::class); }
}