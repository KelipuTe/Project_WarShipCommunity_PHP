<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factory extends Model
{
    protected $fillable = [
       'title','user_id'
    ];
}
