<?php

namespace App\Providers;

use App\Permission;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\Discussion' => 'App\Policies\DiscussionPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /*
         * 使用数据库进行权限管理
         * 这里使用循环声明权限
         */
        foreach($this->getPermissions() as $permission){
            // function () use () {}，use() 用于连接外部变量
            Gate::define($permission->name,function (User $user) use ($permission){
                return $user->hasRole($permission->roles);
            });
        }
    }

    private function getPermissions(){
        return Permission::with('roles')->get();
    }
}
