<?php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use Closure;
use Auth;

class AutoLogin
{
    public function handle(Request $request, Closure $next)
    {
        $identity = config('devel.autologin');

	$surname = $_SERVER['HTTP_SURNAME'];
	$name = $_SERVER['HTTP_GIVENNAME'];
	$email = $_SERVER['HTTP_MAIL'];
	$aff = $_SERVER['HTTP_AFFILIATION'];
	$uid = $_SERVER['HTTP_UNIQUEID'];
	$full = $surname . " " . $name;

	$u = User::where('unique_id', $uid)->first();
	if($u == null){
		$u = new User();
		$u->unique_id = $uid;
		$u->firstname = $name;
		$u->lastname = $surname;
		$u->email = $email;
		$u->name = $full;
		$u->affiliation = $aff;
		$u->save();

		if( str_contains($aff, 'student') ){
			$s = new Student();
			$s->user_id = $u->id;
			$s->save();	
		}	
	}

	if(!Auth::check()){
		Auth::loginUsingId($u->id);
	}

        return $next($request);
    }
}
