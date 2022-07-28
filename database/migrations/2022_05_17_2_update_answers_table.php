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
                $table->double('points')->default(0);
                $table->text('new_validation')->nullable();
                $table->tinyInteger('need_help')->default(false);
            });
        });
    }

    public function down()
    {
        Schema::table('answers', function (Blueprint $table) {
            
          $table->dropIfExists(['points', 'new_validation', 'need_help']);
            
        });
    }
}