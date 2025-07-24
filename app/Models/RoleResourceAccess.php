<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleResourceAccess extends Model
{
    protected $guarded = ['id'];

    public function role() 
    {
        return $this->belongsTo(Role::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
