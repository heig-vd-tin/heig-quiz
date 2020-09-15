<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'unique_id' => '100',
            'firstname' => 'Tutu',
            'lastname' => 'Toto',
            'email' => 'tutu.toto@heig-vd.ch',
            'name' => 'Tutu Toto',
            'password' => Hash::make('password'),
            'gender' => 'male',
            'affiliation' => ''
        ]);

        DB::table('users')->insert([
            'unique_id' => '102',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@heig-vd.ch',
            'name' => 'John Doe',
            'password' => Hash::make('password'),
            'gender' => 'male',
            'affiliation' => ''
        ]);

        DB::table('users')->insert([
            'unique_id' => '103',
            'firstname' => 'Nicole',
            'lastname' => 'Doe',
            'email' => 'nicoledoe@heig-vd.ch',
            'name' => 'Nicole Doe',
            'password' => Hash::make('password'),
            'gender' => 'female',
            'affiliation' => ''
        ]);
    }
}
