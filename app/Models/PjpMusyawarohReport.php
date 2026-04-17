<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjpMusyawarohReport extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'dokumentasi' => 'array'
    ];

    public function pjpReport()
    {
        return $this->belongsTo(PjpReport::class);
    }
}
