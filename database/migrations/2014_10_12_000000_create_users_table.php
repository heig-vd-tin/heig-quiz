<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('unique_id')->comment('Switch AAI unique ID');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('name');
            $table->string('password')->comment('Always `shibboleth` for AAI authentication');
            $table->tinyInteger('gender');
            $table->string('affiliation')->comment('Usually `member;staff` or `member;student`');
            $table->rememberToken();
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
