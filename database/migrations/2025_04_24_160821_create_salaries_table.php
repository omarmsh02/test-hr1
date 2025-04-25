<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Links to the employee
            $table->decimal('amount', 10, 2); // Salary amount (e.g., 5000.00)
            $table->string('currency')->default('USD'); // Currency (e.g., USD, EUR)
            $table->date('effective_date'); // Date when the salary becomes effective
            $table->text('notes')->nullable(); // Additional notes about the salary
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salaries');
    }
}