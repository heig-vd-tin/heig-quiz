<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomsTable extends Migration
{
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // A, B, C, D...
            $table->tinyInteger('semester'); // 0 : fall, 1 : spring
            $table->year('year');
            $table->foreignId('course_id')->constrained();
            $table->foreignId('teacher_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('classes'); // Haha I had the same issue Tony :)
    }
}
