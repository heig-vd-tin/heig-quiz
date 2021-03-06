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
            $table->foreignId('roster_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('duration')->comment('In seconds');
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_propositions')->default(false);
            $table->bigInteger('seed')->default(0)->comment('For random generator');
            $table->boolean('hidden')->default(false);

            $table->timestamp('started_at')->nullable()->comment('Activity start time');
            $table->timestamp('opened_at')->nullable()->comment('When the activity was called to be joined');
            $table->timestamp('ended_at')->nullable()->comment('When the activity was finished');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
