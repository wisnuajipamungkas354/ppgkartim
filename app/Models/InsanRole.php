<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsanRole extends Model
{
    protected $guarded = ['id'];

    public function insan() {
        return $this->belongsTo(Insan::class);
    }

    public function dapukan() {
        return $this->belongsTo(Dapukan::class);
    }

    public function generus() {
        return $this->hasMany(Generus::class);
    }

    public function mubalighTugasan() {
        return $this->hasMany(MubalighTugasan::class);
    }

    public function mubalighSetempat() {
        return $this->hasMany(MubalighSetempat::class);
    }
}
