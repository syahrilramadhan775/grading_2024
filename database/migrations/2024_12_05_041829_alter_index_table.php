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
        Schema::table('gender', function (Blueprint $table) {
            $table->index('name', 'gender_idx_name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('username', 'users_idx_username');
            $table->index('name', 'users_idx_name');
        });

        Schema::table('project', function (Blueprint $table) {
            $table->index('name', 'project_idx_name');
            $table->index('date_start', 'project_idx_date_start');
            $table->index('date_end', 'project_idx_date_end');
        });

        Schema::table('task', function (Blueprint $table) {
            $table->index('project_id', 'task_idx_project_id');
            $table->index('parent_id', 'task_idx_parent_id');
            $table->index('users_id', 'task_idx_users_id');
            $table->index('name', 'task_idx_name');
            $table->index('status', 'task_idx_status');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->index('name', 'roles_idx_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gender', function (Blueprint $table) {
            $table->dropIndex('gender_idx_name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_idx_username');
            $table->dropIndex('users_idx_name');
        });

        Schema::table('project', function (Blueprint $table) {
            $table->dropIndex('project_idx_name');
            $table->dropIndex('project_idx_date_start');
            $table->dropIndex('project_idx_date_end');
        });

        Schema::table('task', function (Blueprint $table) {
            $table->dropIndex('task_idx_project_id');
            $table->dropIndex('task_idx_parent_id');
            $table->dropIndex('task_idx_sub_parent_id');
            $table->dropIndex('task_idx_users_id');
            $table->dropIndex('task_idx_name');
            $table->dropIndex('task_idx_status');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropIndex('roles_idx_name');
        });
    }
};
