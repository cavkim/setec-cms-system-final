<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 50)->nullable()->after('password');
            $table->string('status', 32)->default('active')->after('phone');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->string('specialization')->nullable()->after('user_id');
            $table->decimal('hourly_rate', 10, 2)->default(0)->after('certification_expiry');
            $table->date('hire_date')->nullable()->after('hourly_rate');
        });

        Schema::create('project_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['project_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_team');

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn(['specialization', 'hourly_rate', 'hire_date']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'status']);
        });
    }
};
