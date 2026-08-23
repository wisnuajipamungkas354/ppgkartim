<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FgdSession extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date', 'status'];

    public function groups()
    {
        return $this->hasMany(FgdGroup::class);
    }

    public function themes()
    {
        return $this->hasMany(FgdTheme::class);
    }
}
