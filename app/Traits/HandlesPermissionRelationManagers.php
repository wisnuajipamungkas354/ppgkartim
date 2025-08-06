<?php

namespace App\Traits;

use App\Helpers\RolePermission;
use Illuminate\Database\Eloquent\Model;

trait HandlesPermissionRelationManagers
{
  /**
     * Get the permission key based on action and resource name.
     */
    protected function permissionKey(string $action): string
    {
        return $action . '_' . $this->getPermissionSlug();
    }

    protected function isCombinedWords($string)
    {
        // lakukan pengecekan regex: apakah ada huruf kapital A-Z setelah karakter pertama
        return preg_match('/[A-Z]/', substr($string, 1));
    }

    /**
     * Get the base slug for permission based on the resource class name.
     * Example: GenerusResource => generus
     */
    protected function getPermissionSlug(): string
    {
        // Menghilangkan kata Resource
        $slug = str_replace('Resource', '', class_basename(get_class($this)));

        if($this->isCombinedWords($slug)) {
            $str = strtolower(preg_replace('/([A-Z])/', '::$1', $slug));
            $str = ltrim($str, ':');
        } else {
            $str = strtolower($slug);
        }

        return $str;
    }

    public function canView(Model $record): bool
    {
        return RolePermission::can($this->permissionKey('view'));
    }

    public function canCreate(): bool
    {
        return RolePermission::can($this->permissionKey('create'));
    }

    public function canAttach(): bool
    {
        return RolePermission::can($this->permissionKey('attach'));
    }

    public function canDetach(Model $record): bool
    {
        return RolePermission::can($this->permissionKey('detach'));
    }

    public function canDetachAny(): bool
    {
        return RolePermission::can($this->permissionKey('detach_any'));
    }

    public function canAssociate(): bool
    {
        return RolePermission::can($this->permissionKey('associate')); 
    }

    public function canDissociate(Model $record): bool
    {
        return RolePermission::can($this->permissionKey('disssociate')); 
    }

    public function canDissociateAny(): bool
    {
        return RolePermission::can($this->permissionKey('disssociate_any')); 
    }

    public function canEdit(Model $record): bool
    {
        return RolePermission::can($this->permissionKey('update'));
    }

    public function canDelete(Model $record): bool
    {
        return RolePermission::can($this->permissionKey('delete'));
    }

    public function canForceDelete(Model $record): bool
    {
        return RolePermission::can($this->permissionKey('force_delete'));
    }

    public function canRestore(Model $record): bool
    {
        return RolePermission::can($this->permissionKey('restore'));
    }

    public function canReplicate(Model $record): bool
    {
        return RolePermission::can($this->permissionKey('replicate'));
    }

    public function canReorder(): bool
    {
        return RolePermission::can($this->permissionKey('reorder'));
    }
}