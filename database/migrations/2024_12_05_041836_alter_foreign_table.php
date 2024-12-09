<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('gender_id', 'users_fk_gender_id')->references('id')->on('gender')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('roles_id', 'users_fk_roles_id')->references('id')->on('roles')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('task', function (Blueprint $table) {
            $table->foreign('project_id', 'task_fk_project_id')->references('id')->on('project')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('parent_id', 'task_fk_parent_id')->references('id')->on('task')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('users_id', 'task_fk_users_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->dropForeign('task_fk_project_id');
            $table->dropForeign('task_fk_parent_id');
            $table->dropForeign('task_fk_sub_parent_id');
            $table->dropForeign('task_fk_users_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_fk_gender_id');
            $table->dropForeign('users_fk_roles_id');
        });
    }
};
