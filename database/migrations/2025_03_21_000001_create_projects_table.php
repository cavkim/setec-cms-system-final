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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->string('location')->nullable();
            $table->enum('status', ['planning', 'in_progress', 'on_hold', 'completed'])->default('planning');
            $table->decimal('budget_allocated', 15, 2)->default(0);
            $table->decimal('budget_spent', 15, 2)->default(0);
            $table->integer('progress_percent')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
