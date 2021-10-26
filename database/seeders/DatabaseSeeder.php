<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123456'),
                'birthday' => '2000/01/01',
                'age' => '21'
            ]
        ]);
        DB::table('permissions')->insert([
            [
                'username' => 'admin',
                'user_id' => 1,
                'admin' => true,
                'um_access' => true,
                'um_modify' => true,
                'ui_access' => true,
                'ui_add' => true,
                'ui_update' => true,
                'ui_delete' => true,
                'todo_access' => true,
                'todo_add' => true,
                'todo_update' => true,
                'todo_delete' => true,
            ]
        ]);
    }
}
