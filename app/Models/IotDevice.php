<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IotDevice extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'api_token',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
