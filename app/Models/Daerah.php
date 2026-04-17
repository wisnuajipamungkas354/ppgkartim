<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Daerah extends Model
{
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->nm_daerah = strtoupper($model->nm_daerah);
            $model->alias = $model->alias ? strtoupper($model->alias) : null;
        });
    }

    public function users()
    {
        return $this->morphMany(User::class, 'userable');
    }

    public function pjpSchedules()
    {
        return $this->morphMany(PjpSchedule::class, 'scheduleable');
    }

    public function desa() {
        return $this->hasMany(Desa::class);
    }
    
    public function kelompok() {
        return $this->hasManyThrough(Kelompok::class, Desa::class);
    }

    public function insan() {
        return $this->hasMany(Insan::class);
    }
}
