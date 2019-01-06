<?php

namespace App\Http\Middleware;

use Closure;

class Languages
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
        if (session()->has('lang')){
            if (session()->get('lang') == 'ar'){
                app()->setLocale('ar');
            }elseif(session()->get('lang') == 'en'){
                app()->setLocale('en');
            }
        }else{
            app()->setLocale('en');
        }
        return $next($request);
    }
}
