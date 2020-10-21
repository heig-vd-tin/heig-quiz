<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

use App\Models\Roster;
use App\Models\User;

class RosterFactory extends Factory
{
    protected $model = Roster::class;

    public function definition()
    {
        $teachers_id = User::where('affiliation', 'not like', '%student%')->pluck('id')->toArray();
        return [
            'name' => Arr::random(['A', 'B', 'C']),
            'semester' => Arr::random([0, 0, 0, 0, 0, 1]),
            'year' => Arr::random([2018, 2019, 2020, 2020, 2020, 2020, 2020, 2020, 2021]),
            'teacher_id' => Arr::random($teachers_id),
        ];
    }
}
