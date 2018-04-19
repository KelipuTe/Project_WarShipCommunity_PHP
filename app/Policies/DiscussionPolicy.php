<?php

namespace App\Policies;

use App\Discussion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscussionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function discussionDelete(User $user,Discussion $discussion){
        return $user->ownsDiscussion($discussion);
    }
}
