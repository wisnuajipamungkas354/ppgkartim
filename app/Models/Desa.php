<?php

namespace App\Models;

use App\Helpers\AccessHelper;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->nm_desa = strtoupper($model->nm_desa ?? '');
            $model->alias = $model->alias ? strtoupper($model->alias) : null;
        });
    }

    public function users()
    {
        return $this->morphMany(User::class, 'userable');
    }

    public function pjpReports()
    {
        return $this->morphMany(PjpReport::class, 'reportable');
    }

    public function pjpSchedules()
    {
        return $this->morphMany(PjpSchedule::class, 'scheduleable');
    }

    public function daerah()
    {
        return $this->belongsTo(Daerah::class);
    }

    public function kelompok()
    {
        return $this->hasMany(Kelompok::class);
    }

    public function insan()
    {
        return $this->hasMany(Insan::class);
    }

    // Local Scope
    public function scopeOwned($query)
    {
        if (AccessHelper::isSuperAdmin()) $query;
        elseif (AccessHelper::isDaerah()) $query->where('daerah_id', auth('web')->user()->userable_id);
        elseif (AccessHelper::isDesa()) $query->where('id', auth('web')->user()->userable_id);

        return $query;
    }
}
