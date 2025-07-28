<?php

namespace App\Models;

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

    public function daerah() {
        return $this->belongsTo(Daerah::class);
    }

    public function kelompok() {
        return $this->hasMany(Kelompok::class);
    }

    public function insan() {
        return $this->hasMany(Insan::class);
    }
}
