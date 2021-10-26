<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helpers;


class PermissionController extends Controller
{
    public function store(Request $request)
    {
        if (Helpers::hasPermission($request, 'um_add')) {
            $user = User::where('username', $request->only(["username"]))->first();
            $id = $user->id;
            $response = Permission::create([
                'user_id' => $id,
                'admin' => false,
                'um_access' => false,
                'um_modify' => false,
                'ui_access' => false,
                'ui_add' => false,
                'ui_update' => false,
                'ui_delete' => false,
                'todo_access' => false,
                'todo_add' => false,
                'todo_update' => false,
                'todo_delete' => false,
            ]);
            return $response;
        }
    }
    public function getpermissions(Request $request)
    {
        if (Helpers::hasPermission($request, 'um_access')) {
            if (Helpers::isAdmin($request)) {
                $permission = Permission::all();
            } else {
                $permission =
                    DB::table('permissions')
                    ->where('admin', '!=', 1)
                    ->get();
            }
            $id = $request->user()->id;
            $perm = Permission::where('user_id', $id)->first();
            $response = [
                'users' => $permission,
                'um_access' => $perm['um_access'],
                'um_modify' => $perm['um_modify'],
                'admin' => $perm['admin']
            ];
            return $response;
        }
    }
    public function update(Request $request, $id)
    {
        if (Helpers::hasPermission($request, 'um_modify')) {
            $fields = $request->validate([
                'admin' => 'boolean',
                'um_access' => 'boolean',
                'um_modify' => 'boolean',
                'ui_access' => 'boolean',
                'ui_add' => 'boolean',
                'ui_update' => 'boolean',
                'ui_delete' => 'boolean',
                'todo_access' => 'boolean',
                'todo_add' => 'boolean',
                'todo_update' => 'boolean',
                'todo_delete' => 'boolean',
            ]);
            $perm = Permission::find($id);
            $perm->update($fields);
            $response = [
                'permission' => $perm,
                'message' => "Updated Successfuly"
            ];
            return $response;
        }
    }
}
