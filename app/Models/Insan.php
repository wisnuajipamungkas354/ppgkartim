<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\AccessHelper;

class Insan extends Model
{
    use SoftDeletes;
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'url_foto' => 'array',
        'dapukan' => 'array',
        'detail_siap_nikah' => 'array',
        'minat_bakat' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function($insan) {
            $insan->nama = strtoupper($insan->nama);
            $insan->kota_lahir = strtoupper($insan->kota_lahir);
            $insan->nm_ayah = strtoupper($insan->nm_ayah);
            $insan->nm_ibu = strtoupper($insan->nm_ibu);
        });

        static::updating(function($insan) {
            $insan->nama = strtoupper($insan->nama);
            $insan->kota_lahir = strtoupper($insan->kota_lahir);
            $insan->nm_ayah = strtoupper($insan->nm_ayah);
            $insan->nm_ibu = strtoupper($insan->nm_ibu);
        });
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function desa() 
    { 
        return $this->belongsTo(Desa::class); 
    }

    public function daerah() 
    { 
        return $this->belongsTo(Daerah::class); 
    }

    public function generus()
    {
        return $this->hasOne(Generus::class);
    }

    public function mubaligh()
    {
        return $this->hasOne(Mubaligh::class);
    }

    public function pengurus()
    {
        return $this->hasMany(Pengurus::class);
    }
}
