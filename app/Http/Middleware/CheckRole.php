<?php

namespace App\Http\Middleware;

use Closure;
use Request;

class CheckRole
{
	public function handle($request, Closure $next, $role)
	{
		if($request->user()->hasRole($role) || !$role)
			return $next($request);

        $error_code = 403;

        if (Request::wantsJson() || strncmp(Request::path(), "api/", 4) === 0) {
            return response()->json([
                'error' => 'Unauthorized: only for teachers!',
                'documentation_url' => url('docs')
            ], $error_code);
        } else {
            return abort($error_code);
        }
	}

	private function getRequiredRoleForRoute($route)
	{
		$actions = $route->getAction();
		return isset($actions['roles']) ? $actions['roles'] : null;
	}
}
