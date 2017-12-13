<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpaceAdministration extends Model
{
    protected $fillable = ['title','body','user_id'];

    /**
     * 可以将published_at变量转换为Carbon类型
     * @var array
     */
    protected $dates = ['destroyed_at'];

    /**
     * 通过spaceAministration找到user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeNotDestroyed($query){
        $query -> where('ontrack','=',0);
    }
}
