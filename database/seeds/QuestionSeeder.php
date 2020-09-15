<?php

use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([
            'name' => 'Transformation binaire',
            'content' => '## Que vaut 0x7 en binnaire',
            'answer' => '0b00111',
            'difficulty' => '2',
            'explanation' => ''
        ]);

        DB::table('questions')->insert([
            'name' => 'Transformation binaire',
            'content' => '## Que vaut 0x8 en binnaire',
            'answer' => '0b001000',
            'difficulty' => '2',
            'explanation' => ''
        ]);

        DB::table('questions')->insert([
            'name' => 'Transformation binaire',
            'content' => '## Que vaut 0x0 en binnaire',
            'answer' => '0b00',
            'difficulty' => '1',
            'explanation' => ''
        ]);
    }
}
