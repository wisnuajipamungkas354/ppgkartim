<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'data_json' => 'array',
    ];

    /* Relasi balik ke event */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /* Relasi ke presensi */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'participant_id', 'id');
    }
}
