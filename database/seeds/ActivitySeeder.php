<?php

use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('activities')->insert([
            'quiz_id' => '1',
            'classroom_id' => '1',
            'duration' => '10'
        ]);
    }
}
