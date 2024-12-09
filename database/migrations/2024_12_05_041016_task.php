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
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id')->nullable();
            $table->bigInteger('parent_id')->nullable();
            // $table->bigInteger('sub_parent_id')->nullable();
            $table->bigInteger('users_id')->nullable();
            $table->string('name');
            $table->string('status'); //['working', 'open', 'close', 'overdue']
            $table->time("start_time");
            $table->time("end_time");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};
