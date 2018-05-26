<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 讨论类
 * Class Discussion
 * @package App
 */
class Discussion extends Model
{
    use SoftDeletes; // 使用软删除

    protected $table = 'discussions'; //模型对应的数据表

    /**
     * 声明可批量赋值的数据
     * @var array
     */
    protected $fillable = [
        'title','body','user_id','last_user_id','published_at','set_top'
    ];

    /**
     * 可以将 published_at 变量转换为 Carbon 类型
     * @var array
     */
    protected $dates = ['published_at','deleted_at'];

    /**
     * 数据预处理 setAttribute
     * set(关键字) + Name(开头大写的数据库列名) + Attribute(关键字)
     * @param $date
     */
    public function setPublishedAtAttribute($date){
        $this->attributes['published_at'] = Carbon::createFromFormat('Y-m-d',$date);
    }

    /**
     * 声明向模型中添加的数据
     * @var array
     */
    protected $appends = ['relatedInfo'];

    public function getRelatedInfoAttribute(){
        $username = $this->user()->get(['username']);
        $avatar = $this->user()->get(['avatar']);
        $lastUser = User::find($this->attributes['last_user_id']);
        $update_diff = Carbon::parse($this->attributes['updated_at']);
        $data = [
            'username' => $username[0]->username,
            'avatar' => $avatar[0]->avatar,
            'lastUsername' => $lastUser->username,
            'countComments' => $this->comments()->count(),
            'update_diffForHumans' => $update_diff->diffForHumans()
        ];
        return $data;
    }

    /**
     * 筛选已发表的讨论
     * 查询语句 queryScope
     * scope(关键字) + Name(方法名)
     * @param $query
     */
    public function scopePublished($query){
        $query->where('published_at','<=',Carbon::now());
    }

    /**
     * 筛选在黑名单内的讨论
     * @param $query
     */
    public function scopeBlacklist($query){
        $query->where('blacklist','!=',true);
    }

    /**
     * 按浏览量降序排序
     * @param $query
     */
    public function scopeHotDiscussion($query){
        $query->orderBy('hot_discussion','desc');
    }

    /**
     * 按推荐量降序排序
     * @param $query
     */
    public function scopeNiceDiscussion($query){
        $query->orderBy('nice_discussion','desc');
    }

    /**
     * 按置顶状态降序排序
     * @param $query
     */
    public function scopeSetTop($query){
        $query->orderBy('set_top','desc');
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
     * 定义 user 和 discussion 在 user_discussion 表中的多对多关系，关注关系
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userDiscussion(){
        return $this->belongsToMany(User::class,'user_discussion')->withTimestamps();
    }

    /**
     * 计算已经关注该讨论的用户数量
     * @return int
     */
    public function countFollowedUser(){
        return $this->userDiscussion()->where('discussion_id',$this->id)->count();
    }
}
