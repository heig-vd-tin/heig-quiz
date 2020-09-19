<?php 

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
	public function handle($request, Closure $next, $role)
	{
		if($request->user()->hasRole($role) || !$role)
			return $next($request);
   
		return abort(403);
	}

	private function getRequiredRoleForRoute($route)
	{
		$actions = $route->getAction();
		return isset($actions['roles']) ? $actions['roles'] : null;
	}
}