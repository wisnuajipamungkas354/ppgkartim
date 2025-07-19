<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $guarded = ['id'];

    public function daerah() {
        return $this->belongsTo(Daerah::class);
    }

    public function kelompok() {
        return $this->hasMany(Kelompok::class);
    }
}
