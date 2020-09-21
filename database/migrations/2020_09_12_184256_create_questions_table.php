<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content'); // Markdown

            // ['A', 'B'] for multiple answers
            // {'pattern' : '/regex/'}
            // {'contains' : 'string'}
            // {'expression' : '$_ > 23 && $_ < 42'}
            $table->json('answer');
            $table->enum('type', ['short-answer', 'multiple-choice', 'code', 'fill-in-the-gaps'])->default('short-answer');
            $table->json('options');
            $table->enum('difficulty', ['easy', 'medium', 'hard', 'insane'])->default('easy');
            $table->text('explanation')->nullable()->default(null); // Markdown
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
