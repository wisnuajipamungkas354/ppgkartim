<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Generus extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'detail_status' => 'array',
        'detail_minat' => 'array',
    ];

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

            // Create QR-Code Images
            $generateQr = QrCode::format('png')->style('round')->size(300)->margin(1)->errorCorrection('H')->generate($generus->nis);
            Storage::disk('public')->put('generus/qr-images/' . $generus->nis . '.png', $generateQr);
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
