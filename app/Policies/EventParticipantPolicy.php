<?php

namespace App\Policies;

use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventParticipantPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EventParticipant $eventParticipant): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EventParticipant $eventParticipant): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EventParticipant $eventParticipant): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EventParticipant $eventParticipant): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EventParticipant $eventParticipant): bool
    {
        return true;
    }

    public function canAttach(User $user): bool
    {
        return true;
    }

    public function canDetach(User $user): bool
    {
        return true;
    }

    public function canDetachAny(User $user): bool
    {
        return true;
    }

    public function canAssociate(User $user): bool
    {
        return true;
    }

    public function canDissociate(User $user): bool
    {
        return true;
    }

    public function canDissociateAny(User $user): bool
    {
        return true;
    }

}
