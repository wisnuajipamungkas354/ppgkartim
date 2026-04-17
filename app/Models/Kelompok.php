<?php

namespace App\Models;

use App\Helpers\AccessHelper;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'foto_masjid' => 'array',
        'lokasi' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->nm_kelompok = strtoupper($model->nm_kelompok ?? '');
            $model->alias = $model->alias ? strtoupper($model->alias) : null;
            $model->nm_masjid = $model->nm_masjid ? strtoupper($model->nm_masjid) : null;
        });
    }

    public function users()
    {
        return $this->morphMany(User::class, 'userable');
    }

    public function laporanPjps()
    {
        return $this->morphMany(PjpReport::class, 'reportable');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function insan()
    {
        return $this->hasMany(Insan::class);
    }

    // Local Scope
    public function scopeOwned($query)
    {
        if (AccessHelper::isSuperAdmin()) $query;
        elseif (AccessHelper::isDaerah()) $query->whereHas('desa', fn($q) => $q->where('daerah_id', auth('web')->user()->userable_id));
        elseif (AccessHelper::isDesa()) $query->where('desa_id', auth('web')->user()->userable_id);
        elseif (AccessHelper::isKelompok()) $query->where('id', auth('web')->user()->userable_id);

        return $query;
    }
}
