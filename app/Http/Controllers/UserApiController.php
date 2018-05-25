<?php

namespace App\Http\Controllers;

use App\ThirdPartyLibrary\Transformer\AccountTransformer;
use App\ThirdPartyLibrary\Transformer\RoleTransformer;
use App\ThirdPartyLibrary\Transformer\UserTransformer;
use Illuminate\Http\Request;
use Auth;

/**
 * Class UserApiController [用户模块 api 控制器]
 * @package App\Http\Controllers
 */
class UserApiController extends ApiController
{
    protected $userTransformer;
    protected $accountTransformer;
    protected $roleTransformer;

    public function __construct(
        UserTransformer $userTransformer,
        AccountTransformer $accountTransformer,
        RoleTransformer $roleTransformer
    )
    {
        $this->userTransformer = $userTransformer;
        $this->accountTransformer = $accountTransformer;
        $this->roleTransformer = $roleTransformer;
    }

    /**
     * 获得用户信息
     * @return mixed
     */
    public function apiGetUserInfo(){
        $user = Auth::guard('api')->user();
        $account = $user->account;
        $roles = $user->roles;
        $data = [
            'user' => $this->userTransformer->transform($user),
            'account' => $this->accountTransformer->transform($account),
            'roles' => $this->roleTransformer->transformCollection($roles->toArray())
        ];
        return $this->response([
            'status' => 'success', 'status_code' => $this->getStatusCode(),
            'data' => $data
        ]);
    }
}
