<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MubalighSetempat extends Model
{
    protected $guarded = ['id'];

    public function insanRole() {
        return $this->belongsTo(InsanRole::class);
    }
}
