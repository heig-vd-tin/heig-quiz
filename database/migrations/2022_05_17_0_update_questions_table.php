<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuestionsTable extends Migration
{
    //Ajout des colonnes et modification de l'enum
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->after('explanation', function ($table) {
                $table->text('hint')->nullable()->default(null);
                $table->tinyInteger('is_public')->nullable()->default(false);
                $table->unsignedInteger('count_views')->nullable()->default(0);
                $table->double('points')->nullable()->default(0);
                $table->foreignId('user_id')->nullable()->constrained();
            });
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIfExists(['hint', 'is_public', 'count_views', 'points', 'user_id']);
        });
    }
}