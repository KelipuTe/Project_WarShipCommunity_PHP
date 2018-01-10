<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarShip extends Model
{
    protected  $fillable = ['classes','name','no','type','country'];
}
