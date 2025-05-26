<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Links to the employee
            $table->date('attendance_date'); // Date of attendance
            $table->time('check_in')->nullable();// Break time
            $table->time('check_out')->nullable(); // Check-out time
            $table->string('status')->default('present'); // Status: present, absent, late, etc.
            $table->text('notes')->nullable(); // Additional notes
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}