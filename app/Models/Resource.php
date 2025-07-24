<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'is_active'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_resource_accesses');
    }
}
