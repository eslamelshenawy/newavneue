<?php

namespace App\Http\Middleware;

use Closure;

class lead
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

		if (!auth('lead')->user()) {
			return redirect('lead_login');
		}
        return $next($request);
    }
}
