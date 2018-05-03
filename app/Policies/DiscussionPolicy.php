<?php

namespace App\Policies;

use App\Discussion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * 这个类负责处理与 讨论(Discussion) 有关的权限
 * Class DiscussionPolicy
 * @package App\Policies
 */
class DiscussionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * 判断用户是否有删除帖子的权限
     * @param User $user
     * @param Discussion $discussion
     * @return bool
     */
    public function discussionDelete(User $user,Discussion $discussion){
        return $user->ownsDiscussion($discussion);
    }
}
