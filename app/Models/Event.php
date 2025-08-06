<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'date'         => 'date',
        'start_time'   => 'datetime:H:i',
        'end_time'     => 'datetime:H:i',
        'column_config'=> 'array',
    ];

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
