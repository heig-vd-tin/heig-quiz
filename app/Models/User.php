<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'firstname', 'lastname', 'gender', 'affiliation', 'unique_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function hasRole($roles) {
        if (!is_array($roles))
            $roles = [$roles];

        $is_student = strpos($this->affiliation, 'student') === false;
        foreach($roles as $role) {
            if ($role == 'student' && !$is_student)
                return true;
            if ($role == 'teacher' && $is_student)
                return true;
        }

        return false;
    }

    function isTeacher() {
        return $this->hasRole('teacher');
    }

    function isStudent() {
        return $this->hasRole('student');
    }

    function student() {
        return $this->hasOne(Student::class);
    }

    function activities() {
        return $this->hasMany(Activity::class);
    }

    function rosters() {
        return $this->hasMany(Roster::class, 'teacher_id');
    }
}
