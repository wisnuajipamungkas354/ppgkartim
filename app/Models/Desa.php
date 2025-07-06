<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $fillable = ['nm_desa'];

    public function kelompok() {
        return $this->hasMany(Kelompok::class);
    }
}
