<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PjpSchedule extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'musyawaroh_rutin' => 'array',
        'kegiatan_rutin' => 'array',
        'deadline_laporan' => 'datetime',
    ];

    public static function getLatestForCurrentUser(): ?self
    {
        $user = auth('web')->user();

        return self::owned()->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->first();
    }

    public function scheduleable()
    {
        return $this->morphTo();
    }

    public function pjpReport()
    {
        return $this->hasMany(PjpReport::class);
    }

    public function scopeOwned(Builder $builder)
    {
        $user = auth('web')->user();

        // Pastikan user punya relasi userable
        if (! $user->userable_type || ! $user->userable_id) {
            return;
        }

        $builder->where('scheduleable_type', $user->userable_type)
            ->where('scheduleable_id', $user->userable_id);
    }
}
