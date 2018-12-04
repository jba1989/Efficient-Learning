<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // ajax 請求，判斷用戶是否登入
        if ($request->ajax()) {            
            if (Auth::guard($guard)->check() == false) {
                return response()->json(['message' => '', 'errors' => ['message' => [trans('Login Please')]]], 403);
            } else {
                return $next($request);
            }
        }
  
        // 判斷用戶是否登入
        if (Auth::guard($guard)->check()) {
            return $next($request);
        } else {
            return redirect()->back()->withErrors(trans('auth.Login Please'))->withInput();
        }
    }
}
