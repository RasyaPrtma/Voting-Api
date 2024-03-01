<?php

namespace App\Http\Middleware;

use App\Models\Authentications;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = Authentications::where('token',$request->bearerToken())->first();
        if($token !== null){
            return $next($request);
        }else{
            throw new AuthenticationException;
        }
    }
}
