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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->string('industry')->nullable();
            $table->enum('client_type', ['individual', 'small_business', 'enterprise', 'government']);
            $table->enum('status', ['active', 'inactive', 'prospect'])->default('prospect');
            $table->text('notes')->nullable();
            $table->decimal('total_contract_value', 15, 2)->nullable();
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
