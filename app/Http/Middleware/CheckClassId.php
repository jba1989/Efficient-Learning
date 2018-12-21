<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Exception;
Use Redis;
use Log;


class CheckClassId
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
        try {
            $errMsg = trans('dictionary.Wrong ClassId');

            // 若不存在此classId則返回
            if (!in_array($input['classId'], json_decode(Redis::get('classIdList')))) {
                if ($request->ajax()) {
                    return response()->json(['message' => '', 'errors' => ['message' => $errMsg]], 403);
                }
        
                if (!in_array($input['classId'], json_decode(Redis::get('classIdList')))) {
                    return redirect()->back()->withErrors(trans('dictionary.Wrong ClassId'));
                }
            }

            return $next($request);
        } catch (Exception $err) {
            Log::error($err);
        }
    }
}
