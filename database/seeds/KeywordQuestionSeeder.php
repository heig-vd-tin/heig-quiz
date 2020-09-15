<?php

use Illuminate\Database\Seeder;

class KeywordQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('keyword_question')->insert([
            'keyword_id' => '1',
            'question_id' => '1'
        ]);

        DB::table('keyword_question')->insert([
            'keyword_id' => '2',
            'question_id' => '1'
        ]);

        DB::table('keyword_question')->insert([
            'keyword_id' => '3',
            'question_id' => '1'
        ]);

        DB::table('keyword_question')->insert([
            'keyword_id' => '2',
            'question_id' => '2'
        ]);
    }
}
