<?php

namespace App\Http\Middleware;

use Closure;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && strpos($request->user()->affiliation, 'student') === false)
            return new Response(view('unauthorized')->with('role', 'ADMIN'));

        return $next($request);
    }
}
