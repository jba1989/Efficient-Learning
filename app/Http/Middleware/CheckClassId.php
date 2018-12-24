<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Exception;
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

            if ($request->input('classId') != null) {
                $classId = $request->input('classId');
            } elseif ($request->query('class') != null) {
                $classId = $request->query('class');
            }

            // 若不存在此classId則返回
            if (isset($classId) && (!in_array($classId, json_decode(Redis::get('classIdList'))))) {
                if ($request->ajax()) {
                    return response()->json(['message' => '', 'errors' => ['message' => $errMsg]], 403);
                } else {
                    return redirect()->back()->withErrors(trans('dictionary.Wrong ClassId'));
                }
            } else {
                return $next($request);
            }            
        } catch (Exception $err) {
            Log::error($err);
        }
    }
}
