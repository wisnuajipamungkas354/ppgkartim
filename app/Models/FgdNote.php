<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FgdNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'fgd_group_id', 
        'fgd_theme_id',
        'notulis_name',
        'problem',
        'penyebab',
        'solusi',
        'ap_deskripsi',
        'ap_nama_kegiatan',
        'ap_peserta',
        'ap_waktu',
        'ap_dana',
        'peran_keimaman',
        'peran_pengurus',
        'peran_orang_tua',
        'peran_mubaligh',
        'peran_ahli_pendidik',
    ];

    public function group()
    {
        return $this->belongsTo(FgdGroup::class, 'fgd_group_id');
    }

    public function theme()
    {
        return $this->belongsTo(FgdTheme::class, 'fgd_theme_id');
    }
}
