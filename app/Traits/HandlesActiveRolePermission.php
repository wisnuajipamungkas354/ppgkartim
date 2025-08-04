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

    protected static function isCombinedWords($string)
    {
        // lakukan pengecekan regex: apakah ada huruf kapital A-Z setelah karakter pertama
        return preg_match('/[A-Z]/', substr($string, 1));
    }

    /**
     * Get the base slug for permission based on the resource class name.
     * Example: GenerusResource => generus
     */
    protected static function getPermissionSlug(): string
    {
        // Menghilangkan kata Resource
        $slug = str_replace('Resource', '', class_basename(static::class));

        if(static::isCombinedWords($slug)) {
            $str = strtolower(preg_replace('/([A-Z])/', '::$1', $slug));
            $str = ltrim($str, ':');
        } else {
            $str = strtolower($slug);
        }

        return $str;
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
