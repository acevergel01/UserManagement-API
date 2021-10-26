<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'username',
        'admin',
        'um_access',
        'um_modify',
        'ui_access',
        'ui_add',
        'ui_update',
        'ui_delete',
        'todo_access',
        'todo_add',
        'todo_update',
        'todo_delete',
    ];
}
