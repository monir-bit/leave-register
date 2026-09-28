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
        Schema::create('employee_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->foreignId('leave_type_id')->constrained('leave_types');
            $table->unsignedInteger('balance_in_days');
            $table->unsignedSmallInteger('year');
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->unique(['employee_id', 'leave_type_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_leave_balances');
    }
};
