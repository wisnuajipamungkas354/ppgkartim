<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $guarded = ['id'];

    public function generus() {
        return $this->hasMany(Generus::class);
    }
}
