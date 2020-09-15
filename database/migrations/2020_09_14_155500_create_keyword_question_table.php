<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordQuestionTable extends Migration
{
    public function up()
    {
        Schema::create('keyword_question', function (Blueprint $table) {
            $table->foreignId('keyword_id')->constrained();
            $table->foreignId('question_id')->constrained();
        });
    }

    public function down()
    {
        Schema::dropIfExists('keyword_question');
    }
}
