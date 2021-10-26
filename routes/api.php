<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//public routes
//user
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
//private routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //user
    Route::get('userinfo', [UserController::class, 'getuserinfo']);
    Route::post('user', [UserController::class, 'adduser']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::put('changepass/user/{id}', [UserController::class, 'changepassword']);
    Route::delete('user/{id}', [UserController::class, 'deleteuser']);
    Route::post('logout', [UserController::class, 'logout']);
    //todo 
    Route::post('todo', [TodoController::class, 'store']);
    Route::get('todo', [TodoController::class, 'gettask']);
    Route::put('todo/{id}', [TodoController::class, 'updateTask']);
    Route::delete('todo/{id}', [TodoController::class, 'deletetask']);
    Route::get('todo/users', [TodoController::class, 'users']);
    //permission
    Route::post('permission', [PermissionController::class, 'store']);
    Route::put('permission/{id}', [PermissionController::class, 'update']);
    Route::get('permission', [PermissionController::class, 'getpermissions']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
