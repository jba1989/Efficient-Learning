<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Validation\Rule;
use Auth;

class ApiUserController extends Controller
{
    public function update(Request $request)
    {
        $input = $request->all();

        $rules = [
            'classId' => 'alpha_num|max:12',
        ];

        $validator = Validator::make($input, $rules);
        
        if ($validator->fails()) {
            return response()->json(['data' => '', 'errMsg' => ''], 403);
        }

        $classId = $request->input('classId');
        $favoriteArr = (isset(Auth::user()->favorite)) ? Auth::user()->favorite : array();
        
        $favoriteIndex = array_search($classId, $favoriteArr);

        if ($favoriteIndex === false) {
            array_push($favoriteArr, $classId);
            $favorite = true;
        } else {
            unset($favoriteArr[$favoriteIndex]);
            $favorite = false;
        }

        Auth::user()->favorite = $favoriteArr;
        Auth::user()->save();

        return response()->json(['data' => ['favorite' => $favorite], 'errMsg' => ''], 200);
    }
}
