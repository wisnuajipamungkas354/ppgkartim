<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dapukan extends Model
{
    protected $guarded = ['id'];

    public function insanRole() {
        return $this->hasMany(InsanRole::class);
    }
}
