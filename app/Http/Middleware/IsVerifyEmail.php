<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
  
class IsVerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('user')->user()->verified) {
            auth()->logout();
            return redirect()->back()
                    ->with('warning', 'You need to confirm your account. We have sent you an activation code, please check your email.');
          }
   
        return $next($request);
    }
}
