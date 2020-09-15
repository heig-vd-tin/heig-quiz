<?php

use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('students')->insert([
            'orientation' => 'AA',
            'type' => 'TP',
            'user_id' => '2'
        ]);

        DB::table('students')->insert([
            'orientation' => 'AA',
            'type' => 'TP',
            'user_id' => '3'
        ]);
    }
}
