<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Roster extends Model
{
    use HasFactory;

    protected $withCount = [
        'students'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    function students() {
        return $this->belongsToMany(Student::class);
    }

    function activities() {
        return $this->hasMany(Activity::class);
    }

    function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    function orientations() {
        $data = $this->students()->select(DB::raw('count(*) as count, orientation'))->groupBy('orientation')->get();
        $orientations = [];
        foreach ($data as $orientation) {
            $orientations[$orientation->orientation] = $orientation->count;
        }
        return $orientations;
    }
}
