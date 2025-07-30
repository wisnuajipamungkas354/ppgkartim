<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class RolePermission
{
    public static function can(string $permission): bool
    {
        $user = Auth::user();
        $activeRoleId = session('active_role_id');

        if (!$user || !$activeRoleId) return false;

        $role = Role::with('permissions')->find($activeRoleId);

        return $role?->permissions->contains('name', $permission);
    }
}