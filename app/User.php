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
        'username', 'email','email_confirm_code','email_confirm', 'password','avatar'
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
        $this->attributes['password'] = \Hash::make($password);//密码加密
    }

    /**
     * 通过user找到属于该用户的所有discussion
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discussions(){
        return $this->hasMany(Discussion::class,'user_id');
    }

    /**
     * 通过user找到属于该用户的所有comment
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(){
        return $this->hasMany(Comment::class,'user_id');
    }

    /**
     * 定义user和discussion在user_discussion表中的多对多关系
     * 这个函数通过用户找到所有被用户关注的讨论
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userDiscussion(){
        return $this->belongsToMany(Discussion::class,'user_discussion')->withTimestamps();
    }

    /**
     * user关注discussion
     * @param $discussion
     * @return array
     */
    public function userDiscussionFollow($discussion){
        /*return UserDiscussionFollow::create([
            'user_id' => $this->id,
            'discussion_id' => $discussion,
        ]);//得到discussion的id并创建user关注discussion的记录*/
        return $this->userDiscussion()->toggle($discussion);//toggle()方法，如果数据库不存在该数据就创建，存在就删除
    }

    /**
     * 判断user是否已经关注discussion
     * @param $discussion
     * @return int
     */
    public function hasFollowedDiscussion($discussion){
        return $this->userDiscussion()->where('discussion_id',$discussion)->count();
    }

    /**
     * 定义user和user在user_user表中的多对多关系
     * 这个函数通过关注者找到被关注的用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userUserFollowed(){
        /*self::class就是User::class*/
        /*注意一下参数里的两个key*/
        return $this->belongsToMany(self::class,'user_user','follower_id','followed_id')->withTimestamps();
    }

    /**
     * 定义user和user在user_user表中的多对多关系
     * 这个函数通过被关注的用户找到关注者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userUserFollower(){
        /*注意一下参数里的两个key*/
        return $this->belongsToMany(self::class,'user_user','followed_id','follower_id')->withTimestamps();
    }

    /**
     * user关注user
     * @param $user
     * @return array
     */
    public function userUserFollow($user){
        return $this->userUserFollowed()->toggle($user);
    }

    /**
     * 通过user找到所有的向该用户发送的personalLetter
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fromPersonalLetter(){
        return $this->hasMany(PersonalLetter::class,'from_user_id');
    }

    /**
     * 通过user找到所有的向该用户发送的personalLetter
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function toPersonalLetter(){
        return $this->hasMany(PersonalLetter::class,'to_user_id');
    }

    /**
     * 通过用户找到其对应的账户
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account(){
        return $this->hasOne(Account::class,'user_id');
    }
}
