<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Generus extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::creating(function ($generus) {
            if (empty($generus->nis)) {
                $tanggal = now()->format('ymd');
    
                $count = self::whereDate('created_at', now())
                    ->where('nis', 'like', $tanggal . '%')
                    ->count();
    
                $suffix = str_pad($count + 1, 2, '0', STR_PAD_LEFT);
                $generus->nis = $tanggal . $suffix;
            }
        });
    }

    public function insanRole() {
        return $this->belongsTo(InsanRole::class);
    }

    public function kelasPpg() {
        return $this->belongsTo(KelasPpg::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }
    
    public function minat() {
        return $this->belongsTo(Minat::class);
    }
}
