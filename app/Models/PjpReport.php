<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PjpReport extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::addGlobalScope(function(Builder $builder): void
        {
            $user = auth('web')->user();
    
            // Pastikan user punya relasi userable
            if (! $user->userable_type || ! $user->userable_id) {
                return;
            }
    
            $builder->where('reportable_type', $user->userable_type)
                    ->where('reportable_id', $user->userable_id);
        });
    }

    public function reportable()
    {
        return $this->morphTo();
    }

    public function pjpSchedule()
    {
        return $this->belongsTo(PjpSchedule::class);
    }

    public function kegiatan()
    {
        return $this->hasMany(PjpKegiatanReport::class);
    }

    public function musyawaroh()
    {
        return $this->hasMany(PjpMusyawarohReport::class);
    }

    public function pengurus()
    {
        return $this->hasMany(PjpPengurusReport::class);
    }
}
