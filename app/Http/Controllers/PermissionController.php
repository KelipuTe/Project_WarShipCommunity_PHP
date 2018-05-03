<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Response;

class PermissionController extends Controller
{
    /**
     * PermissionController constructor.
     */
    public function __construct(){
        $this->middleware('admin');
    }

    /**
     * 权限管理中心页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function index(){
        return view('office.permission.permission');
    }

    /**
     * 新增角色
     * @param Request $request
     * @return mixed
     */
    public function addRole(Request $request){
        Role::create($request->all());
        return Response::json(['roles' => Role::all()]);
    }

    /**
     * 获得所有角色
     * @return mixed
     */
    public function getRoles(){
        $rules = Role::with('permissions')->get();
        return Response::json(['roles' => $rules]);
    }

    /**
     * 角色详细页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function role(){
        return view('office.permission.role');
    }

    /**
     * 获得角色详细信息
     * @param Request $request
     * @return mixed
     */
    public function getRole(Request $request){
        $id = $request->input('id');
        $role = Role::find($id);
        $rolePermissions = $role->permissions()->get();
        $rolePermissionIds = [];
        foreach($rolePermissions as $permission){
            array_push($rolePermissionIds,$permission->id);
        }
        return Response::json([
            'role' => $role,
            'roleUsers' => $role->users()->get(['user_id','username']),
            'rolePermissionIds' => $rolePermissionIds,
            'permissions' => Permission::all()
        ]);
    }

    /**
     * 新增权限
     * @param Request $request
     * @return mixed
     */
    public function addPermission(Request $request){
        Permission::create($request->all());
        return Response::json([
            'permissions' => Permission::all()
        ]);
    }

    /**
     * 更改角色权限
     * @param Request $request
     * @return mixed
     */
    public function togglePermission(Request $request){
        $role = Role::find($request->input('roleId'));
        $permission = Permission::find($request->input('permissionId'));
        $role->permissions()->toggle($permission);
        return Response::json([
            'role' => $role,
            'rolePermissions' => $permission,
            'permissions' => Permission::all()
        ]);
    }

    /**
     * 赋予用户角色
     * @param Request $request
     * @return mixed
     */
    public function giveRoleToUser(Request $request){
        $roleId = $request->input('roleId');
        $userId = $request->input('userId');
        $username = $request->input('username');
        $user = null;
        if($userId != null){
            $user = User::find($userId);
        }
        if($username != null){
            $user = User::where('username','=',$username);
        }
        if($user == null){
            return Response::json([
                'user' => $user
            ]);
        }
        $role = Role::find($roleId);
        $user->roles()->toggle($role);
        return Response::json([
            'user' => $user
        ]);
    }

    /**
     * 收回用户角色
     * @param Request $request
     * @return mixed
     */
    public function removeRoleFromUser(Request $request){
        $roleId = $request->input('roleId');
        $userId = $request->input('userId');
        $user = User::find($userId);
        $role = Role::find($roleId);
        $user->roles()->toggle($role);
        return Response::json([
            'user' => $user
        ]);
    }
}
