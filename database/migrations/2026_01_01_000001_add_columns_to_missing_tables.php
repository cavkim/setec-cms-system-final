<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('budget_categories')) {
            Schema::create('budget_categories', function (Blueprint $table) {
                $table->id();
                $table->string('category_name', 100);
                $table->string('description', 200)->nullable();
                $table->string('color_hex', 7)->default('#185FA5');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } elseif (! Schema::hasColumn('budget_categories', 'category_name')) {
            Schema::table('budget_categories', function (Blueprint $table) {
                $table->string('category_name', 100)->after('id');
                $table->string('description', 200)->nullable()->after('category_name');
                $table->string('color_hex', 7)->default('#185FA5')->after('description');
                $table->boolean('is_active')->default(true)->after('color_hex');
            });
        }

        if (! Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
                $table->unsignedBigInteger('category_id')->nullable();
                $table->text('description');
                $table->decimal('amount', 12, 2);
                $table->date('expense_date');
                $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('category_id')->references('id')->on('budget_categories')->nullOnDelete();
            });
        } elseif (! Schema::hasColumn('expenses', 'project_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->unsignedBigInteger('project_id')->after('id');
                $table->unsignedBigInteger('category_id')->nullable()->after('project_id');
                $table->text('description')->after('category_id');
                $table->decimal('amount', 12, 2)->after('description');
                $table->date('expense_date')->after('amount');
                $table->unsignedBigInteger('submitted_by')->after('expense_date');
                $table->unsignedBigInteger('approved_by')->nullable()->after('submitted_by');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('approved_by');
                $table->text('notes')->nullable()->after('status');

                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
                $table->foreign('submitted_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('documents') && ! Schema::hasColumn('documents', 'project_id')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->unsignedBigInteger('project_id')->after('id');
                $table->string('document_name', 200)->after('project_id');
                $table->enum('document_type', ['contract', 'permit', 'blueprint', 'report', 'photo', 'inspection', 'other'])->default('other')->after('document_name');
                $table->string('file_url', 255)->after('document_type');
                $table->string('file_extension', 10)->nullable()->after('file_url');
                $table->integer('file_size')->nullable()->after('file_extension');
                $table->integer('version_number')->default(1)->after('file_size');
                $table->boolean('is_latest')->default(true)->after('version_number');
                $table->unsignedBigInteger('uploaded_by')->after('is_latest');
                $table->text('description')->nullable()->after('uploaded_by');

                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
                $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('inspections') && ! Schema::hasColumn('inspections', 'project_id')) {
            Schema::table('inspections', function (Blueprint $table) {
                $table->unsignedBigInteger('project_id')->after('id');
                $table->string('inspection_type', 150)->after('project_id');
                $table->string('inspector_name', 150)->after('inspection_type');
                $table->date('inspection_date')->after('inspector_name');
                $table->date('next_inspection_date')->nullable()->after('inspection_date');
                $table->enum('status', ['pending', 'passed', 'failed'])->default('pending')->after('next_inspection_date');
                $table->text('findings')->nullable()->after('status');

                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('vendors') && ! Schema::hasColumn('vendors', 'vendor_name')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->string('vendor_name', 150)->after('id');
                $table->string('contact_person', 150)->nullable()->after('vendor_name');
                $table->string('email', 150)->nullable()->after('contact_person');
                $table->string('phone', 50)->nullable()->after('email');
                $table->string('payment_terms', 100)->default('Net 30')->after('phone');
                $table->decimal('rating', 2, 1)->default(0)->after('payment_terms');
                $table->boolean('is_active')->default(true)->after('rating');
            });
        }
    }

    public function down(): void
    {
        //
    }
};
