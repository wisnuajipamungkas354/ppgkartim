<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insan extends Model
{
    use SoftDeletes;
    
    protected $guarded = ['id'];

    public function insanRole() {
        return $this->hasMany(InsanRole::class);
    }
}
