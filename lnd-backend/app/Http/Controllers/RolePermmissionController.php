<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermmissionController extends Controller
{
    public function listRole()
    {
        $data['rows'] = Role::all()->pluck('name');
        $data['type'] = 'role';
        return view('permissions.list', $data);
    }
    public function listPermission()
    {
        $data['rows'] = Permission::all()->pluck('name');
        $data['type'] = 'permission';
        return view('permissions.list', $data);
    }
    public function createRole()
    {
        $data['type'] = 'role';
        return view('permissions.input-form', $data);
    }
    public function createPermission()
    {
        $data['type'] = 'permission';
        return view('permissions.input-form', $data);
    }
    public function storeRole(Request $request)
    {
        $request->validate(['name' => 'required|alpha|max:2555|unique:roles']);
        $created = Role::create(['name' => $request->name]);
        if ($created)
            return redirect('/list-role')->with('status', 'Role added successfully');
        else {
            return back()->with('staus', 'Something went wrong');
        }
    }
    public function storePermission(Request $request)
    {
        $request->validate(['name' => 'required|regex:/^[a-zA-Z\s]+$/|max:2555|unique:permissions']);
        $created = Permission::create(['name' => $request->name]);
        if ($created)
            return redirect()->to('/list-permission')->with('status', 'Permission added successfully');
        else {
            return back()->with('staus', 'Something went wrong');
        }
    }
    
}
