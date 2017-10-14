<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 用户讨论关注类
 * Class UserDiscussionFollow
 * @package App
 */
class UserDiscussionFollow extends Model
{
    /**
     * 指定模型对应的数据库表
     * @var string
     */
    protected $table = 'user_discussion';

    protected  $fillable = ['user_id','discussion_id'];
}
