<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->string('history_time')->default('');//log of changes in estimation
            $table->text('description');
            $table->float('estimation', 8, 2)->nullable(true);
            $table->integer('review')->default(0);
            $table->float('billed', 8, 2)->nullable(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('task_id')->nullable()->default(NULL);
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->unsignedInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedInteger('user_id')->nullable()->default(NULL);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('tasks');
    }
}
