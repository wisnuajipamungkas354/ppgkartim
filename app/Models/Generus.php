<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Helpers\AccessHelper;

class Generus extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'detail_status' => 'array',
        'detail_minat' => 'array',
        'detail_siap_nikah' => 'array',
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

        static::addGlobalScope('mudamudi', function(Builder $builder) {
            if(AccessHelper::isMudamudi()) {
                $builder->where('jenis_data', 'MM');
            }
        });

        // Menyembunyikan data insan yang ter-soft delete
        static::addGlobalScope('hasInsan', function ($query) {
            $query->whereHas('insan', function ($q) {
                $q->whereNull('deleted_at');
            });
        });
    }

    public function insan() {
        return $this->belongsTo(Insan::class);
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

    // Local SCope
   public function scopeOwned($query)
   {
     if(AccessHelper::isSuperAdmin()) $query;
     elseif(AccessHelper::isDaerah()) $query->whereHas('insan', fn($q) => $q->where('daerah_id', auth('web')->user()->daerah_id));
     elseif(AccessHelper::isDesa()) $query->whereHas('insan', fn($q) => $q->where('desa_id', auth('web')->user()->desa_id));
     elseif(AccessHelper::isKelompok()) $query->whereHas('insan', fn($q) => $q->where('kelompok_id', auth('web')->user()->kelompok_id));

     return $query;
   }
}
