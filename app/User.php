<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * 用户类
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email','email_confirm_code','email_confirm', 'password','avatar','blacklist','api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 密码字段预处理
     * @param $password
     */
    public function setPasswordAttribute($password){
        $this->attributes['password'] = \Hash::make($password); // 密码加密
    }

    /**
     * 通过 user 找到属于该用户的所有 discussion
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discussions(){
        return $this->hasMany(Discussion::class,'user_id');
    }

    /**
     * 通过 user 找到属于该用户的所有 comment
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(){
        return $this->hasMany(Comment::class,'user_id');
    }

    /**
     * 定义 user 和 discussion 在 user_discussion 表中的多对多关系
     * 这个函数通过用户找到所有被用户关注的讨论
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userDiscussion(){
        return $this->belongsToMany(Discussion::class,'user_discussion')->withTimestamps();
    }

    /**
     * user 关注 discussion
     * @param $discussion
     * @return array
     */
    public function userDiscussionFollow($discussion){
        /*return UserDiscussionFollow::create([
            'user_id' => $this->id,
            'discussion_id' => $discussion,
        ]);*/ // 得到 discussion 的 id 并创建 user 关注 discussion 的记录
        return $this->userDiscussion()->toggle($discussion); // toggle() 方法，如果数据库不存在该数据就创建，存在就删除
    }

    /**
     * 判断 user 是否已经关注 discussion
     * @param $discussion
     * @return int
     */
    public function hasFollowedDiscussion($discussion){
        return $this->userDiscussion()->where('discussion_id',$discussion)->count();
    }

    /**
     * 定义 user 和 user 在 user_user 表中的多对多关系
     * 这个函数通过关注者找到被关注的用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userUserFollowed(){
        /*
         * self::class 就是 User::class
         * 注意一下参数里的两个 key
         */
        return $this->belongsToMany(self::class,'user_user',
            'follower_id','followed_id')
            ->withTimestamps();
    }

    /**
     * 定义 user 和 user 在 user_user 表中的多对多关系
     * 这个函数通过被关注的用户找到关注者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userUserFollower(){
        /* 注意一下参数里的两个 key */
        return $this->belongsToMany(self::class,'user_user',
            'followed_id','follower_id')
            ->withTimestamps();
    }

    /**
     * user 关注 user
     * @param $user
     * @return array
     */
    public function userUserFollow($user){
        return $this->userUserFollowed()->toggle($user);
    }

    /**
     * 通过 user 找到所有该用户发送的 personalLetter
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fromPersonalLetter(){
        return $this->hasMany(PersonalLetter::class,'from_user_id');
    }

    /**
     * 通过 user 找到所有向该用户发送的 personalLetter
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function toPersonalLetter(){
        return $this->hasMany(PersonalLetter::class,'to_user_id');
    }

    /**
     * 通过 user 找到其对应的 account
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account(){
        return $this->hasOne(Account::class,'user_id');
    }

    /**
     * 通过 user 找到其对应的 sign
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function signs(){
        return $this->hasMany(Sign::class,'user_id');
    }

    public function roles(){
        return $this->belongsToMany(Role::class,'role_user');
        // 使用 $this->roles()->save($role) 可以为 user 添加 role
        // 使用 $this->roles()->detach($role) 可以为 user 移除 role
        // 使用 $this->roles()->attach($role) 可以为 user 添加 role
    }

    public function hasRole($role){
        if(is_string($role)){
            // contains() 判断字符串内是否存在
            return $this->roles->contains('name',$role);
        }
        // intersect() 用于比较两个 Collection 是否相同
        return !! $role->intersect($this->roles)->count();
    }

    public function isAdmin(){
        return ($this->hasRole('admin') || $this->power == 1);
    }

    public function ownsDiscussion($discussion){
        return $this->id == $discussion->id;
    }
}
