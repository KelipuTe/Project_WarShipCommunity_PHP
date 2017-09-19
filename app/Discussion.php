<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $fillable = [
        'title','body','user_id','last_user_id','published_at'
    ];

    /**
     * 可以将published_at变量转换为Carbon类型
     * @var array
     */
    protected $dates = ['published_at'];

    /**
     * 数据预处理 setAttribute
     * et关键字+Name+Attribute关键字
     * Name 是开头大写的数据库列名
     * @param $date
     */
    public function setPublishedAtAttribute($date){
        $this->attributes['published_at'] = Carbon::createFromFormat('Y-m-d',$date);
    }

    /**
     * 查询语句 queryScope
     * scope关键字+Name
     * Name 是方法名
     * @param $query
     */
    public function scopePublished($query){
        $query -> where('published_at','<=',Carbon::now());
    }

    public function user(){
        return $this->belongsTo(User::class);//通过discussion拿到user
    }

    public function comments(){
        return $this->hasMany(Comment::class);//通过discussion拿到comment
    }
}
