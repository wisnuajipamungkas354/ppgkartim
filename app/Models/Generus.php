<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Generus extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function insanRole() {
        return $this->belongsTo(InsanRole::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }
    
    public function minat() {
        return $this->belongsTo(Minat::class);
    }
}
