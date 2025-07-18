<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Daerah extends Model
{
    protected $guarded = ['id'];

    public function desa() {
        return $this->hasMany(Desa::class);
    }

    public function kelompok() {
        return $this->hasManyThrough(Kelompok::class, Desa::class);
    }
}
