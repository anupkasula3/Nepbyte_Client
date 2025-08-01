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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type'); // task_assigned, task_completed, project_updated, etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional notification data
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_read')->default(false);
            $table->string('action_url')->nullable(); // URL to navigate when clicked
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['project_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
