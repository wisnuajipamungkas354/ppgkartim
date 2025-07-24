<?php

namespace App\Helpers;

use App\Models\Role;
use App\Models\RoleResourceAccess;
use Illuminate\Database\Eloquent\Builder;

class AccessHelper
{
    public static  function getAccessibleResourceSlugsForCurrentUser(): array
    {
        $roleId = session('active_role_id');

        if (!$roleId) return [];

        return RoleResourceAccess::where('role_id', $roleId)
            ->with('resource')
            ->whereHas('resource', fn(Builder $query) => $query->where('is_active', 1))
            ->get()
            ->pluck('resource.slug')
            ->toArray();
    }

    public static function canAccess(string $resourceSlug): bool
    {
        return in_array($resourceSlug, self::getAccessibleResourceSlugsForCurrentUser());
    }

    public static function getActiveRoleName(): string
    {
        $roleId = session('active_role_id');
        return Role::where('id', $roleId)->value('name');
    }
}
