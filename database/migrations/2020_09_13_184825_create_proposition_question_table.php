<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropositionQuestionTable extends Migration
{
    public function up()
    {
        /*Schema::create('proposition_question', function (Blueprint $table) {
            $table->foreignId('question_id');
            $table->foreignId('proposition_id');
            $table->boolean('is_correct');
        });*/
    }

    public function down()
    {
        Schema::dropIfExists('proposition_question');
    }
}
