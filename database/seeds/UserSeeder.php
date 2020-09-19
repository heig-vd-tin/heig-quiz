<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'unique_id' => '1456',
            'firstname' => 'Tony',
            'lastname' => 'Maulaz',
            'email' => 'tony.maulaz@heig-vd.ch',
            'name' => 'Tony Maulaz',
            'password' => 'shibboleth',
            'gender' => '1',
            'affiliation' => 'member;staff'
        ]);

        User::create([
            'unique_id' => '7454',
            'firstname' => 'Yves',
            'lastname' => 'Chevallier',
            'email' => 'yves.chevallier@heig-vd.ch',
            'name' => 'Yves Chevallier',
            'password' => 'shibboleth',
            'gender' => '1',
            'affiliation' => 'member;staff'
        ]);

        factory(App\Models\User::class, 50)->create()->each(function ($user) {
            if ($user->affiliation == 'member;student') {
                $user->studentDetails()->save(factory(App\Models\Student::class)->make());
            }
        });
    }
}
