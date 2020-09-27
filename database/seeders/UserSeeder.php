<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Str;

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
            'affiliation' => 'member;staff',
            'api_token' => Str::random(60),
        ]);

        User::create([
            'unique_id' => '7454',
            'firstname' => 'Yves',
            'lastname' => 'Chevallier',
            'email' => 'yves.chevallier@heig-vd.ch',
            'name' => 'Yves Chevallier',
            'password' => 'shibboleth',
            'gender' => '1',
            'affiliation' => 'member;staff',
            'api_token' => Str::random(60),
        ]);

        User::create([
            'unique_id' => '666',
            'firstname' => 'Sponge',
            'lastname' => 'Bob',
            'email' => 'sponge.bob@heig-vd.ch',
            'name' => 'Sponge Bob',
            'password' => 'shibboleth',
            'gender' => '1',
            'affiliation' => 'member;student',
            'api_token' => Str::random(60),
        ]);

        User::factory()->count(50)->create()->each(function ($user) {
            if ($user->affiliation == 'member;student') {
                $user->studentDetails()->save(Student::factory()->make());
            }
        });
    }
}
