<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'unique_id' => '10',
            'firstname' => 'Tony',
            'lastname' => 'Maulaz',
            'email' => 'tony.maulaz@heig-vd.ch',
            'name' => 'Tony Maulaz',
            'password' => 'shibboleth',
            'gender' => '1',
            'affiliation' => 'member;staff'
        ]);
        DB::table('users')->insert([
            'unique_id' => '10',
            'firstname' => 'Yves',
            'lastname' => 'Chevallier',
            'email' => 'yves.chevallier@heig-vd.ch',
            'name' => 'Yves Chevallier',
            'password' => 'shibboleth',
            'gender' => '1',
            'affiliation' => 'member;staff'
        ]);
        DB::table('users')->insert([
            'unique_id' => '100',
            'firstname' => 'Nicole',
            'lastname' => 'Lebouquet',
            'email' => 'nicole.lebouquet@heig-vd.ch',
            'name' => 'Nicole Lebouquet',
            'password' => 'shibboleth',
            'gender' => '2',
            'affiliation' => 'member;student'
        ]);
        DB::table('users')->insert([
            'unique_id' => '100',
            'firstname' => 'Frédéric',
            'lastname' => 'Giggoletta',
            'email' => 'frederic.giggoletta@heig-vd.ch',
            'name' => 'Frédéric Giggoletta',
            'password' => 'shibboleth',
            'gender' => '1',
            'affiliation' => 'member;student'
        ]);
    }
}
