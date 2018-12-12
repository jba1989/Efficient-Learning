<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassValidate;
use Auth;
use Exception;
use DB;
class ApiUserController extends Controller
{
    public function show(ClassValidate $request)
    {
        try {
            $classId = $request->input('classId');
            if (Auth::check()) {
                $favoriteArr = (isset(Auth::user()->favorite)) ? Auth::user()->favorite : array();
                $favorite = in_array($classId, $favoriteArr) ? true : false;

                return response()->json(['data' => ['favorite' => $favorite], 'errMsg' => ''], 200);
            }

            return response()->json(['data' => '', 'errMsg' => ''], 200);
        } catch (Exception $err) {
            Log::error($err);
        }
    }

    public function update(ClassValidate $request)
    {
        try {
            $classId = $request->input('classId');
            $favoriteArr = (isset(Auth::user()->favorite)) ? Auth::user()->favorite : array();

            $favoriteIndex = array_search($classId, $favoriteArr);

            if ($favoriteIndex === false) {
                array_unshift($favoriteArr, $classId);
                $favorite = true;

                // 我的最愛最多只能存20筆資料
                while(count($favoriteArr) > 20) {
                    array_pop($favoriteArr);
                }            
            } else {
                unset($favoriteArr[$favoriteIndex]);
                $favorite = false;
            }

            // 寫入資料庫
            DB::beginTransaction();
            Auth::user()->favorite = $favoriteArr;
            Auth::user()->save();
            DB::commit();

            return response()->json(['data' => ['favorite' => $favorite], 'errMsg' => ''], 200);
        } catch (Exception $err) {
            DB::rollBack();
            Log::error($err);
        }
    }

    public function delete(ClassValidate $request)
    {
        try {
            $classId = $request->input('classId');
            $favoriteArr = (isset(Auth::user()->favorite)) ? Auth::user()->favorite : array();
            
            $favoriteIndex = array_search($classId, $favoriteArr);
            
            if ($favoriteIndex !== false) {           
                unset($favoriteArr[$favoriteIndex]);

                // 寫入資料庫
                DB::beginTransaction();
                Auth::user()->favorite = $favoriteArr;
                Auth::user()->save();
                DB::commit();
            }            

            return response()->json(['data' => '', 'errMsg' => ''], 200);
        } catch (Exception $err) {
            DB::rollBack();
            Log::error($err);
        }
    }
}
