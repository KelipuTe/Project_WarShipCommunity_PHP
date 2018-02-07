<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 讨论类
 * Class Discussion
 * @package App
 */
class Discussion extends Model
{
    protected $table = 'discussions';

    protected $fillable = [
        'title','body','user_id','last_user_id','published_at'
    ];

    /**
     * 可以将 published_at 变量转换为 Carbon 类型
     * @var array
     */
    protected $dates = ['published_at'];

    /**
     * 数据预处理 setAttribute
     * set 关键字 + Name + Attribute 关键字
     * Name 是开头大写的数据库列名
     * @param $date
     */
    public function setPublishedAtAttribute($date){
        $this->attributes['published_at'] = Carbon::createFromFormat('Y-m-d',$date);
    }

    /**
     * 查询语句 queryScope
     * scope 关键字 + Name
     * Name 是方法名
     * @param $query
     */
    public function scopePublished($query){
        $query -> where('published_at','<=',Carbon::now());
    }

    /**
     * 通过 discussion 找到 user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * 通过 discussion 找到所有的 comment
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    /**
     * 定义 user 和 discussion 在 user_discussion 表中的多对多关系
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userDiscussion(){
        return $this->belongsToMany(User::class,'user_discussion')->withTimestamps();
    }

    /**
     * 计算已经关注该讨论的用户数量
     * @return int
     */
    public function hasFollowedUser(){
        return $this->userDiscussion()->where('discussion_id',$this->id)->count();
    }
}
