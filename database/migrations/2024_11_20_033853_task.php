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
            $table->integer('id', true);
            $table->integer('project_id');
            $table->string('name');
            $table->string('status'); //['working', 'open', 'close', 'overdue']
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('project')->onUpdate("CASCADE")->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
