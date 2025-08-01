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
            $table->string('name');
            $table->text('description');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_manager_id')->constrained('employees')->onDelete('cascade');
            $table->enum('status', ['planning', 'in_progress', 'on_hold', 'completed', 'cancelled'])->default('planning');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->decimal('budget', 15, 2);
            $table->decimal('actual_cost', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('actual_end_date')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->text('technologies_used')->nullable();
            $table->text('requirements')->nullable();
            $table->text('notes')->nullable();
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
