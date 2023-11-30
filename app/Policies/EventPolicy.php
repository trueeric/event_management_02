<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    //* viewAny(?User $user)表示可不傳 User(user_id)參數
    public function viewAny(?User $user): bool
    {
        return true;

    }
    /**
     * Determine whether the user can view the model.
     */
    //* viewAny(?User $user)表示可不傳 User(user_id)參數
    public function view(?User $user, Event $event): bool
    {
        return true;

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //* User不加? 需傳user 參數
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {

        //* User不加? 需傳user 參數
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        //* User不加? 需傳user 參數
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        //
    }
}
