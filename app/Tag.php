<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 标签类
 * Class Tag
 * @package App
 */
class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = ['body'];
}
