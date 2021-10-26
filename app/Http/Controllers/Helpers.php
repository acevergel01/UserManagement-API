<?php

namespace App\Http\Controllers;
use App\Models\Permission;

class Helpers{

    public static function hasPermission($request, $permission)
    {
        $me = $request->user();
        $perm = Permission::where('user_id', $me->id)->first();
        return $perm->$permission;
    }
    public static function isAdmin($request)
    {
        $me = $request->user();
        $perm = Permission::where('user_id', $me->id)->first();
        return $perm['admin'];
    }
}
