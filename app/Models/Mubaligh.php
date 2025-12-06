<?php

namespace App\Models;

use App\Helpers\AccessHelper;
use Illuminate\Database\Eloquent\Model;

class Mubaligh extends Model
{
    protected $guarded = ['id'];

    public function insan() {
        return $this->belongsTo(Insan::class);
    }

     // Local SCope
    public function scopeOwned($query)
    {
        if(AccessHelper::isSuperAdmin()) $query;
        elseif(AccessHelper::isDaerah()) $query->whereHas('insan', fn($q) => $q->where('daerah_id', auth()->user()->daerah_id));
        elseif(AccessHelper::isDesa()) $query->whereHas('insan', fn($q) => $q->where('desa_id', auth()->user()->desa_id));
        elseif(AccessHelper::isKelompok()) $query->whereHas('insan', fn($q) => $q->where('kelompok_id', auth()->user()->kelompok_id));

        return $query;
    }
}
