<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('username');
            $table->boolean('admin')->default('0');
            $table->boolean('um_access');
            $table->boolean('um_modify');
            $table->boolean('ui_access');
            $table->boolean('ui_add');
            $table->boolean('ui_update');
            $table->boolean('ui_delete');
            $table->boolean('todo_access');
            $table->boolean('todo_add');
            $table->boolean('todo_update');
            $table->boolean('todo_delete');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
