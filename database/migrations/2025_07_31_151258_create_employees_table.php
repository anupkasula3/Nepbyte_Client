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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('position');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->decimal('salary', 10, 2)->nullable();
            $table->date('hire_date');
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->enum('employment_type', ['full-time', 'part-time', 'contract', 'intern']);
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('skills')->nullable();
            $table->string('profile_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
