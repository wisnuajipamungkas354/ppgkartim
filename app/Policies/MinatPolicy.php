<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Minat;
use Illuminate\Auth\Access\HandlesAuthorization;

class MinatPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_minat');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Minat $minat): bool
    {
        return $user->can('view_minat');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_minat');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Minat $minat): bool
    {
        return $user->can('update_minat');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Minat $minat): bool
    {
        return $user->can('delete_minat');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_minat');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Minat $minat): bool
    {
        return $user->can('force_delete_minat');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_minat');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Minat $minat): bool
    {
        return $user->can('restore_minat');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_minat');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Minat $minat): bool
    {
        return $user->can('replicate_minat');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_minat');
    }
}
