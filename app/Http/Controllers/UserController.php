<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Helpers;
class UserController extends Controller
{
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|exists:users',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!Hash::check($fields['password'], $user->password)) {
            return response([
                'errors' => ['message' => ['Invalid password.']]
            ], 401);
        }

        $token = $user->createToken('usertoken')->plainTextToken;

        $response = [
            'message' => "Login Success",
            'token' => $token
        ];
        return response($response, 201);
    }
    public function register(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|confirmed|string|min:6',
            'birthday' => 'required|date|before_or_equal:' . \Carbon\Carbon::now()->subYears(18)->format('Y/m/d'),
        ], [
            'birthday.before_or_equal' => 'Must be atleast 18 years old to register.'
        ]);
        $user = User::create([
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'birthday' => $fields['birthday'],
            'age' => \Carbon\Carbon::createFromFormat("Y/m/d", $request->birthday)->age,
        ]);
        Permission::create([
            'username' => $fields['username'],
            'user_id' => $user->id,
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

        $response = [
            'user' => $user,
            'message' => 'Signup Success'
        ];
        return response($response, 201);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return [
            'message' => 'Logged out'
        ];
    }

    //Private routes functions
    public function getuserinfo(Request $request)
    {
        if (Helpers::hasPermission($request, 'ui_access')) {
            $me = $request->user();
            $perm = Permission::where('user_id', $me->id)->first();
            if (Helpers::isAdmin($request)) {
                $users = User::all();
            } else {
                $users = User::whereIn(
                    'id',
                    Permission::select(['user_id'])
                        ->whereRaw('`permissions`.`user_id` = `users`.`id` and `permissions`.`admin`!=1')
                )
                    ->get();
            }
            $response = [
                'users' => $users,
                'admin' => $me['admin'],
                'uid' => $perm['user_id'],
                'ui_access' => $perm['ui_access'],
                'ui_add' => $perm['ui_add'],
                'ui_update' => $perm['ui_update'],
                'ui_delete' => $perm['ui_delete'],
            ];

            return $response;
        }
    }
    public function adduser(Request $request)
    {
        if (Helpers::hasPermission($request, 'ui_add')) {
            $fields = $request->validate([
                'username' => 'required|string|unique:users,username',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|min:6',
                'birthday' => 'required|date|before_or_equal:' . \Carbon\Carbon::now()->subYears(18)->format('Y/m/d'),
            ], [
                'birthday.before_or_equal' => 'Must be atleast 18 years old to register.'
            ]);
            $user = User::create([
                'username' => $fields['username'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password']),
                'birthday' => $fields['birthday'],
                'age' => \Carbon\Carbon::createFromFormat("Y/m/d", $request->birthday)->age,
            ]);
            Permission::create([
                'username' => $fields['username'],
                'user_id' => $user->id,
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

            $response = [
                'user' => $user,
                'message' => 'Added new user'
            ];
            return $response;
        }
    }
    public function deleteuser($id, Request $request)
    {
        if (Helpers::hasPermission($request, 'ui_delete')) {
            $response = [
                'user' => User::destroy($id),
                'message' => "Deleted user."
            ];
            return $response;
        }
    }
    public function update(Request $request, $id)
    {
        if (Helpers::hasPermission($request, 'ui_update')) {
            $fields = $request->validate([
                'email' => 'required|string|unique:users,email',
                'birthday' => 'required|date|before_or_equal:' . \Carbon\Carbon::now()->subYears(18)->format('Y/m/d'),
            ], [
                'birthday.before_or_equal' => 'Must be atleast 18 years old to register.'
            ]);
            $user = User::find($id);
            $age = \Carbon\Carbon::createFromFormat("Y/m/d", $request->birthday)->age;
            $user->update($fields + ['age' => $age]);
            $response = [
                'user' => $user,
                'message' => "Updated Successfuly"
            ];
            return $response;
        }
    }
    public function changepassword(Request $request, $id)
    {
        if (Helpers::hasPermission($request, 'ui_update')) {
            $fields = $request->validate([
                'password' => 'required|min:6|'
            ]);
            $user = $request->only(["password"]);
            $user['password'] = Hash::make($user['password']);
            User::find($id)->update($user);

            $response = [
                'user' => $user,
                'message' => "Updated Successfuly"
            ];
            return $response;
        }
    }
}
