<?php

namespace App\Traits;

use App\Helpers\RolePermission;
use Illuminate\Database\Eloquent\Model;

trait HandlesActiveRolePermission
{
    /**
     * Get the permission key based on action and resource name.
     */
    protected static function permissionKey(string $action): string
    {
        return $action . '_' . static::getPermissionSlug();
    }

    /**
     * Get the base slug for permission based on the resource class name.
     * Example: GenerusResource => generus
     */
    protected static function getPermissionSlug(): string
    {
        return strtolower(str_replace('Resource', '', class_basename(static::class)));
    }

    // Filament resource access gates
    public static function canViewAny(): bool
    {
        return RolePermission::can(static::permissionKey('view_any'));
    }

    public static function canView(Model $record): bool
    {
        return RolePermission::can(static::permissionKey('view'));
    }

    public static function canCreate(): bool
    {
        return RolePermission::can(static::permissionKey('create'));
    }

    public static function canEdit(Model $record): bool
    {
        return RolePermission::can(static::permissionKey('update'));
    }

    public static function canDelete(Model $record): bool
    {
        return RolePermission::can(static::permissionKey('delete'));
    }

    public static function canForceDelete(Model $record): bool
    {
        return RolePermission::can(static::permissionKey('force_delete'));
    }

    public static function canRestore(Model $record): bool
    {
        return RolePermission::can(static::permissionKey('restore'));
    }

    public static function canReplicate(Model $record): bool
    {
        return RolePermission::can(static::permissionKey('replicate'));
    }

    public static function canReorder(): bool
    {
        return RolePermission::can(static::permissionKey('reorder'));
    }
}
