<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name','description'];

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}
