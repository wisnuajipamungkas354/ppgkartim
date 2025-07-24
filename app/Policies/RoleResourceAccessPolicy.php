<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RoleResourceAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoleResourceAccessPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_role::access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RoleResourceAccess $roleResourceAccess): bool
    {
        return $user->can('view_role::access');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_role::access');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RoleResourceAccess $roleResourceAccess): bool
    {
        return $user->can('update_role::access');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RoleResourceAccess $roleResourceAccess): bool
    {
        return $user->can('delete_role::access');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_role::access');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, RoleResourceAccess $roleResourceAccess): bool
    {
        return $user->can('force_delete_role::access');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_role::access');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, RoleResourceAccess $roleResourceAccess): bool
    {
        return $user->can('restore_role::access');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_role::access');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, RoleResourceAccess $roleResourceAccess): bool
    {
        return $user->can('replicate_role::access');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_role::access');
    }
}
