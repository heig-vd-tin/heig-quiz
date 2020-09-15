<?php

use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        DB::table('courses')->insert([
            'name' => 'Info1',
            'department' => 'tin'
        ]);

        DB::table('courses')->insert([
            'name' => 'Info2',
            'department' => 'tin'
        ]);
    }
}
