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
        DB::table('classes')->insert([
            'number' => '2',
            'course' => 'Info1',
            'name' => 'Info1',
            'semester' => '1',
            'year' => '2020'
        ]);
    }
}
