<?php
// database/migrations/2026_01_02_000001_add_profile_fields_to_users.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'avatar'))
                $table->string('avatar')->nullable()->after('email');
            if (!Schema::hasColumn('users', 'phone'))
                $table->string('phone', 30)->nullable()->after('avatar');
            if (!Schema::hasColumn('users', 'status'))
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'phone', 'status']);
        });
    }
};