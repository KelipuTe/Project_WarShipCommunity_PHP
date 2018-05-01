<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name','label'];

    public function permissions(){
        return $this->belongsToMany(Permission::class,'permission_role');
    }

    public function users(){
        return $this->belongsToMany(User::class,'role_user');
    }

    public function addPermission(Permission $permission){
        return $this->permissions()->save($permission); // 这个方法可以为 role 添加 permission
    }
}
