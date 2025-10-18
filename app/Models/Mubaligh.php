<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mubaligh extends Model
{
    protected $guarded = ['id'];

    public function insan() {
        return $this->belongsTo(Insan::class);
    }
}
