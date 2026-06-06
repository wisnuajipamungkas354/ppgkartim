<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjpPengurusReport extends Model
{
    protected $guarded = ['id'];

    public function pjpReport()
    {
        return $this->belongsTo(PjpReport::class);
    }
}
