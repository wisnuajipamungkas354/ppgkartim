<?php

namespace App\Helpers;

use App\Models\Role;
use App\Models\RoleResourceAccess;
use Illuminate\Database\Eloquent\Builder;

class AccessHelper
{
    public static function getActiveRoleName()
    {
        $roleId = session('active_role_id') ?? null;
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

    /**
     * Mengecek apakah yang login memiliki role daerah
     */
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
