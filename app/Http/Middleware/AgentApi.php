<?php

namespace App\Http\Middleware;

use Closure;
use App\AgentToken;

class AgentApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request1 = json_decode($request->getContent());
        if ($request1) {
            $token = $request1->token;
            $user_id = $request1->user_id;
            $count = AgentToken::where('user_id', $user_id)->where('token', $token)->where('login', true)->count();
            
            if ($count == 0) {
                return ['status' => 'unauthorized'];
            }
        } else {
            $token = $request->token;
            $user_id = $request->user_id;
            $count = AgentToken::where('user_id', $user_id)->where('token', $token)->where('login', true)->count();
            if ($count == 0) {
                return ['status' => 'unauthorized'];
            }
        }
        return $next($request);
    }
}