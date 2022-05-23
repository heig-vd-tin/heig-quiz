<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuizzesTable extends Migration
{
    //Ajout des colonnes
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->after('name', function ($table) {
                $table->tinyInteger('is_exam')->default(false);
            });
        });
    }

    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            
            $table->dropColumn('is_exam');
            
        });
    }
}