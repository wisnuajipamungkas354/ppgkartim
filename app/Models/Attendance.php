<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'check_in_at'  => 'datetime',
        'check_out_at' => 'datetime',
    ];

    /* Relasi balik ke event */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /* Relasi balik ke peserta */
    public function participant()
    {
        return $this->belongsTo(EventParticipant::class, 'participant_id', 'id');
    }
}
