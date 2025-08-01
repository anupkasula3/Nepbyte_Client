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
        Schema::create('project_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('employees')->onDelete('cascade');
            $table->string('activity_type'); // task_created, task_updated, comment_added, file_uploaded, etc.
            $table->string('description');
            $table->json('metadata')->nullable(); // Additional data about the activity
            $table->morphs('subject'); // The object the activity relates to (task, comment, etc.)
            $table->timestamps();

            $table->index(['project_id', 'created_at']);
            $table->index(['activity_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_activities');
    }
};
