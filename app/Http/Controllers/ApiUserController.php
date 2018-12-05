<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassIdValidate;
use Validator;
use Auth;

class ApiUserController extends Controller
{
    public function show(ClassIdValidate $request)
    {
        $classId = $request->input('classId');
        if (Auth::check()) {
            $favoriteArr = (isset(Auth::user()->favorite)) ? Auth::user()->favorite : array();
            $favorite = in_array($classId, $favoriteArr) ? true : false;

            return response()->json(['data' => ['favorite' => $favorite], 'errMsg' => ''], 200);
        }

        return response()->json(['data' => '', 'errMsg' => ''], 200);
    }

    public function update(ClassIdValidate $request)
    {
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

        Auth::user()->favorite = $favoriteArr;
        Auth::user()->save();

        return response()->json(['data' => ['favorite' => $favorite], 'errMsg' => ''], 200);
    }

    public function delete(ClassIdValidate $request)
    {
        $classId = $request->input('classId');
        $favoriteArr = (isset(Auth::user()->favorite)) ? Auth::user()->favorite : array();
        
        $favoriteIndex = array_search($classId, $favoriteArr);

        if ($favoriteIndex !== false) {           
            unset($favoriteArr[$favoriteIndex]);
            Auth::user()->favorite = $favoriteArr;
            Auth::user()->save();
        }

        return response()->json(['data' => '', 'errMsg' => ''], 200);
    }
}
