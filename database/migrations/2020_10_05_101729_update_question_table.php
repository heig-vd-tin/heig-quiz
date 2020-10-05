<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuestionTable extends Migration
{
    /**
     * From now on, the name of a question is optional.
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('name')->change();
        });
    }
}
