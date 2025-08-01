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
        Schema::create('project_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['member', 'lead', 'reviewer', 'observer'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('left_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('responsibilities')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'employee_id']);
            $table->index(['project_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_team_members');
    }
};
