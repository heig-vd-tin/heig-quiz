<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes');
            $table->foreignId('classroom_id')->constrained('classrooms');
            $table->integer('duration'); // In minutes
            $table->enum('state', ['ready', 'in_progress', 'finish']);
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_propositions')->default(false);
            $table->integer('seed')->default(0); // For random generator
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
