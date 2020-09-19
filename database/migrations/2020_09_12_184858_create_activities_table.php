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
            $table->foreignId('classroom_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('duration')->comment('In seconds');
            $table->enum('state', ['hidden', 'ready', 'in_progress', 'finish']);
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_propositions')->default(false);
            $table->integer('seed')->default(0)->comment('For random generator');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
