<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(){
        return view('users.input-form');
    }

    // public function view($id)
    // {
    //     $user = User::find($id);
    //     return view('users.input-form', ['user' => $user, 'mode' => 'view']);
    // }


    public function store(Request $request){
        $request->validate([
            "name"=> 'required|string|max:255',
            "email"=> 'required|email|max:255|unique:users,email',
            "password"=> 'required|string|min:8|max:20',
        ]);

        User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            'status' => false,
        ]);

        return redirect('list-user')->with('status','User created successfully with roles.');
    }

    public function userList()
    {
        $users = User::orderBy('name')->get();
        return view('users.list', compact('users'));
    }

    public function getUserRolesAndPermissions($userId) {
        $user = User::with(['roles', 'permissions'])->find($userId);
        if ($user) {
            $roles = $user->roles->pluck('id')->all();
            $permissions = $user->permissions->pluck('id')->all();
            return response()->json(['roles' => $roles, 'permissions' => $permissions]);
        }
        return response()->json(['roles' => [], 'permissions' => []], 404); // User not found
    }

    public function updateUserRoleOrPermission(){
        $users = User::orderBy('name')->get();
        $roles = Role::orderBy('id')->get();
        $permissions = Permission::orderBy('id')->get();
        return view('permissions.edit', compact('users', 'roles', 'permissions'));
    }

    public function assignRoleOrPermissionToUser(Request $request) {
        $user = User::find($request->userId);
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $type = $request->type; // 'role' or 'permission'
        $entityId = $request->entityId; // ID of either the role or the permission

        if ($type === 'role') {
            $entity = Role::findById($entityId);
        } else if ($type === 'permission') {
            $entity = Permission::findById($entityId);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid type specified'], 400);
        }

        if (!$entity) {
            return response()->json(['status' => 'error', 'message' => ucfirst($type) . ' not found'], 404);
        }

        try {
            if ($request->isChecked === 'true') {
                if ($type === 'role' && !$user->hasRole($entity)) {
                    $user->assignRole($entity);
                } elseif ($type === 'permission' && !$user->hasPermissionTo($entity)) {
                    $user->givePermissionTo($entity);
                }
            } else {
                if ($type === 'role' && $user->hasRole($entity)) {
                    $user->removeRole($entity);
                } elseif ($type === 'permission' && $user->hasPermissionTo($entity)) {
                    $user->revokePermissionTo($entity);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update ' . $type . ': ' . $e->getMessage()], 500);
        }

        return response()->json(['status' => 'success', 'message' => ucfirst($type) . ' updated successfully']);
    }

    public function updateUserStatus(Request $request)
    {
        $user = User::find($request->userId);
        if ($user) {
            $user->status = $request->status;
            $user->save();
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
    }
}

