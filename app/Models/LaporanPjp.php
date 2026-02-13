<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPjp extends Model
{
    protected $guarded = ['id'];

    public function reportable()
    {
        return $this->morphTo();
    }

    public function kegiatan()
    {
        return $this->hasMany(LaporanPjpKegiatan::class);
    }

    public function musyawaroh()
    {
        return $this->hasMany(LaporanPjpMusyawaroh::class);
    }

    public function monitoring()
    {
        return $this->hasMany(LaporanPjpMonitoring::class);
    }
}
