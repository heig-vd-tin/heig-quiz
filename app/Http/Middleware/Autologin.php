<?php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\User;
use Closure;
use Auth;

class AutoLogin
{
    public function handle(Request $request, Closure $next)
    {
        $identity = config('devel.autologin');

        if (!Auth::check() && config('app.debug') && $identity) {
            Auth::login(User::orWhere([
                'id' => $identity,
                'email' => $identity,
                'unique_id' => $identity
            ], true /* remembering */)->firstOrFail());
        }

        return $next($request);
    }
}
