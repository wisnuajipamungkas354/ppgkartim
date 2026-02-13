<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPjpKegiatan extends Model
{
    protected $guarded = ['id'];

    public function laporanPjp()
    {
        return $this->belongsTo(LaporanPjp::class);
    }
}
