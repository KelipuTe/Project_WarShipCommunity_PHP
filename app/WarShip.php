<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 舰船信息类
 * Class WarShip
 * @package App
 */
class WarShip extends Model
{
    protected  $fillable = ['classes','name','no','type','country','picture'];
}
