<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UangKas extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::creating(function ($uangKas) {
            $uangKas->user_id = auth()->user()->id;
            $uangKas->role_id = session('active_role_id'); // Mengambil role aktif di session
        });

        static::addGlobalScope('ownedKas', function(Builder $builder) {
            $builder->where('user_id', auth()->user()->id)->where('role_id', session('active_role_id'));
        });
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
