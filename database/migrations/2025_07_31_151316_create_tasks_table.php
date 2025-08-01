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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('employees')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('employees')->onDelete('cascade');
            $table->enum('status', ['todo', 'in_progress', 'review', 'completed', 'cancelled'])->default('todo');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->date('due_date');
            $table->date('completed_date')->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->integer('actual_hours')->nullable();
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
