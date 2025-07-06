<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $guarded = ['id'];

    public function desa() {
        return $this->belongsTo(Desa::class);
    }
}
