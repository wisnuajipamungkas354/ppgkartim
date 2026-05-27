<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    protected $guarded = ['id'];

    public function dapukanable()
    {
        return $this->morphTo();
    }
}
