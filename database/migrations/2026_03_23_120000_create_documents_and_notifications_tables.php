<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
                $table->string('document_name', 200);
                $table->enum('document_type', ['contract', 'permit', 'blueprint', 'report', 'photo', 'inspection', 'other'])->default('other');
                $table->string('file_url', 255);
                $table->string('file_extension', 10)->nullable();
                $table->unsignedInteger('file_size')->nullable();
                $table->unsignedInteger('version_number')->default(1);
                $table->boolean('is_latest')->default(true);
                $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
        Schema::dropIfExists('notifications');
    }
};
