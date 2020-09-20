<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class KeywordQuestionSeeder extends Seeder
{
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
