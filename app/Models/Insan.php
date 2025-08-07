<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insan extends Model
{
    use SoftDeletes;
    
    protected $guarded = ['id'];

    protected $casts = [
        'url_foto' => 'array',
    ];

    public function insanRole() {
        return $this->hasMany(InsanRole::class);
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function desa() 
    { 
        return $this->belongsTo(Desa::class); 
    }

    public function daerah() 
    { 
        return $this->belongsTo(Daerah::class); 
    }
}
