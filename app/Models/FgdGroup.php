<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FgdGroup extends Model
{
    use HasFactory;

    protected $fillable = ['fgd_session_id', 'name'];

    public function session()
    {
        return $this->belongsTo(FgdSession::class, 'fgd_session_id');
    }

    public function notes()
    {
        return $this->hasMany(FgdNote::class);
    }
}
