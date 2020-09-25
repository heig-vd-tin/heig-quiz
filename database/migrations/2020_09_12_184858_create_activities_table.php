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
            // $table->enum('state', [
            //     'idle',
            //     'openned',
            //     'started',
            //     'stopped',
            //     'ended'
            // ]);
            $table->dateTime('opened_at')->nullable()->comment('Activity opening time');
            $table->dateTime('started_at')->nullable()->comment('Activity start time');
            $table->dateTime('ended_at')->nullable()->comment('Activity end time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
