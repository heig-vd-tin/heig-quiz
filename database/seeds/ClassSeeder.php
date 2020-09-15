<?php

use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('classrooms')->insert([
            'number' => '2',
            'course_id' => '1',
            //'name' => 'Info1',
            'semester' => '1',
            'year' => '2020'
        ]);
    }
}
