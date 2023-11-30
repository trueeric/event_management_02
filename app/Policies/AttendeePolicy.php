<?php

namespace App\Policies;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;

class AttendeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        //* viewAny(?User $user)表示可不傳 User(user_id)參數
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Attendee $attendee): bool
    {
        //* viewAny(?User $user)表示可不傳 User(user_id)參數
        return true;
        //
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
    //* no update action
    // public function update(User $user, Attendee $attendee, Event $event): bool
    // {
    // }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Attendee $attendee): bool
    {
        return $user->id === $attendee->event->user_id ||
        $user->id === $attendee->user_id;

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Attendee $attendee): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Attendee $attendee): bool
    {
        //
    }
}
