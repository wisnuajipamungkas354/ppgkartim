<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Event extends Model
{
    protected $guarded = ['id'];

    public $incrementing = false;

    protected $casts = [
        'date'         => 'date',
        'start_time'   => 'datetime:H:i',
        'end_time'     => 'datetime:H:i',
        'column_config'=> 'array',
    ];

    // Event "creating" untuk otomatis mengisi pengkodean id
    protected static function booted()
    {
        static::creating(function ($model) {
            // Panggil fungsi untuk generate id
            $model->id = self::generateUniqueId($model);
        });
    }

    // Fungsi untuk generate ID unik
    public static function generateUniqueId($model)
    {
        // Ambil tahun dan bulan saat ini
        $dateYearMonth = date('dym'); // Dua digit tanggal tahun & bulan (contoh: 312411)
        $uniqueStr = self::generateRandomString(5);

        // Ambil ID unik yang belum dipakai untuk bulan dan tahun yang sama
        $getLastId = self::latest('id')->value('id');
        $prefixId = (int)substr($getLastId, 0, 6);
        $uniqueId = 1;

        // Jika id terakhir sama dengan bulan dan tahun hari ini maka id terakhir + 1
        if($prefixId == $dateYearMonth) { 
            $lastIdNumber = (int)substr($getLastId, 6, 3);
            $uniqueId= $lastIdNumber + 1;
        }

        // Format ID unik menjadi tiga digit
        $uniqueIdFormatted = str_pad($uniqueId, 3, '0', STR_PAD_LEFT);

        // Gabungkan tahun, bulan, dan ID unik untuk membuat ID lengkap
        $finalId = strval($dateYearMonth) . strval($uniqueIdFormatted) . $uniqueStr;

        // Create QR-Code Images
        $generateQr = QrCode::format('png')->style('round')->size(300)->margin(1)->errorCorrection('H')->generate(url('events/' . $finalId . '/presensi'));
        Storage::disk('public')->put('events/qr-images/' . $finalId . '.png', $generateQr);

        return $finalId;
    }

    public static function generateRandomString($length) {
        return substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    /** Relasi ke peserta */
    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    /** Relasi ke absensi */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
