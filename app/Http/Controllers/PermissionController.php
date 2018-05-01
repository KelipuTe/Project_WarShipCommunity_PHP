<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Response;

class PermissionController extends Controller
{
    function index(){
        return view('office.permission.permission');
    }


    public function addRole(Request $request){
        Role::create($request->all());
        return Response::json(['roles' => Role::all()]);
    }

    public function getRoles(){
        $rules = Role::with('permissions')->get();
        return Response::json(['roles' => $rules]);
    }

    public function role(){
        return view('office.permission.role');
    }

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

    public function addPermission(Request $request){
        Permission::create($request->all());
        return Response::json([
            'permissions' => Permission::all()
        ]);
    }

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
