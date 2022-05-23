<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class updateAnswersTable extends Migration
{
    public function up()
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->after('is_correct', function ($table) {
                $table->double('points');
            });
        });
    }

    public function down()
    {
        Schema::table('answers', function (Blueprint $table) {
            
            $table->dropColumn('points');
            
        });
    }
}