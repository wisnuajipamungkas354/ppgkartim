<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function resources()
    {
        return $this->belongsToMany(Resource::class, 'role_resource_accesses');
    }
}