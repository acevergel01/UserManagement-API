<?php

namespace App\Http\Controllers;

use App\Models\todo;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helpers;

class TodoController extends Controller
{
    public function users(Request $request)
    {
        if (Helpers::hasPermission($request, 'todo_add')) {
            $user = User::all('username');
            return $user;
        }
    }
    public function store(Request $request)
    {
        if (Helpers::hasPermission($request, 'todo_add')) {
            $fields = $request->validate([
                'assigned_to' => 'required|string',
                'subject' => 'required',
                'task' => 'required',
                'deadline' => 'required',
                'notes' => 'string|nullable',
                'status' => 'required|string',
            ]);

            $user = User::where('username', $request->only(["assigned_to"]))->first();
            $id = $user->id;

            $todo = todo::create([
                'user_id' => $id,
                'subject' => $fields['subject'],
                'task' => $fields['task'],
                'assigned_to' => $fields['assigned_to'],
                'deadline' => $fields['deadline'],
                'notes' => $fields['notes'] ?? null,
                'status' => $fields['status'],
            ]);
            $response = [
                'user' => $user,
                'todo' => $todo,
                'message' => 'Added new task successfuly'
            ];
            return $response;
        }
    }
    public function gettask(Request $request)
    {
        if (Helpers::hasPermission($request, 'todo_access')) {
            if (Helpers::isAdmin($request)) {           
                $user = User::all('username');
                $todo = todo::all();
                $me = $request->user();
                $perm = Permission::where('user_id', $me->id)->first();
                $response = [
                    'users' => $user,
                    'todo' => $todo,
                    'todo_access' => $perm['todo_access'],
                    'todo_add' => $perm['todo_add'],
                    'todo_update' => $perm['todo_update'],
                    'todo_delete' => $perm['todo_delete'],
                ];
            }
            else{
                $me = $request->user();
                $user = $me->username;
                $todo = 
                DB::table('todos')
                ->where('assigned_to', $me->username)
                ->get();
                $perm = Permission::where('user_id', $me->id)->first();
                $response = [
                    'users' => $user,
                    'todo' => $todo,
                    'todo_access' => $perm['todo_access'],
                    'todo_add' => $perm['todo_add'],
                    'todo_update' => $perm['todo_update'],
                    'todo_delete' => $perm['todo_delete'],
                ];
            }
 
            return $response;
        }
    }

    public function deletetask(Request $request, $id)
    {
        if (Helpers::hasPermission($request, 'todo_delete')) {
            $todo = todo::destroy($id);
            if ($todo) {
                $response = [
                    'todo' => $todo,
                    'message' => "Task Deleted"
                ];
            }
            return $response;
        }
    }

    public function updateTask(Request $request, $todoid)
    {
        if (Helpers::hasPermission($request, 'todo_update')) {
            $fields = $request->validate([
                'assigned_to' => 'required|string',
                'subject' => 'required',
                'task' => 'required',
                'deadline' => 'required',
                'notes' => 'string|nullable',
                'status' => 'required|string',
            ]);

            $user = User::where('username', $request->only(["assigned_to"]))->first();
            $userid = $user->id;

            $todo = todo::find($todoid);
            $todo->update($fields + ['user_id' => $userid]);
            $response = [
                'todo' => $todo,
                'message' => "Task Updated"
            ];
            return $response;
        }
    }
}
