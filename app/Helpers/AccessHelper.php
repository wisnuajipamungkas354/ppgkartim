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

    public static function isKelompok(): bool 
    {
        return in_array(self::getActiveRoleName(), ['pjp_kelompok', 'mudamudi_kelompok']);
    }

    public static function isDesa(): bool 
    {
        return in_array(self::getActiveRoleName(), ['pjp_desa', 'mudamudi_desa']);
    }

    public static function isDaerah(): bool 
    {
        return in_array(self::getActiveRoleName(), ['phppg', 'mudamudi_daerah', 'kurikulum', 'tenaga_pendidik']);
    }

    public static function isSuperAdmin(): bool 
    {
        return in_array(self::getActiveRoleName(), ['super_admin']);
    }

    public static function isMudamudi(): bool 
    {
        return in_array(self::getActiveRoleName(), ['mudamudi_daerah', 'mudamudi_desa', 'mudamudi_kelompok']);
    }

    public static function isPpg(): bool 
    {
        return in_array(self::getActiveRoleName(), ['phppg', 'kurikulum', 'pjp_desa', 'pjp_kelompok', 'tenaga_pendidik']);
    }
}
