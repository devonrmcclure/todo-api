<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFolderTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folder_task', function (Blueprint $table) {
			$table->primary(['folder_id', 'task_id']);
			$table->unsignedInteger('folder_id');
			$table->unsignedInteger('task_id');

			$table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');
			$table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('folder_task');
    }
}
