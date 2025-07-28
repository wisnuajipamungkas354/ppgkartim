<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->nm_kelompok = strtoupper($model->nm_kelompok ?? '');
            $model->alias = $model->alias ? strtoupper($model->alias) : null;
            $model->nm_masjid = $model->nm_masjid ? strtoupper($model->nm_masjid) : null;
        });
    }

    public function desa() {
        return $this->belongsTo(Desa::class);
    }

    public function insan() {
        return $this->hasMany(Insan::class);
    }
}
