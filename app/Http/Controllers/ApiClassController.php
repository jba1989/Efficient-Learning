<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList;
use App\Models\ClassListLike;
use Validator;
use Illuminate\Validation\Rule;
use Auth;

class ApiClassController extends Controller
{
    public function show(Request $request)
    {
        $input = $request->all();

        $rules = [            
            'classId' => 'nullable|alpha_num|max:12',            
        ];

        $validator = Validator::make($input, $rules);
        
        if ($validator->fails()) {
            return response()->json(['data' => '', 'errMsg' => ''], 403);
        }

        $data = ClassListLike::where('classId', $input['classId'])->first();
        $prefer = '';

        // 若無這筆資料則新建一個實例
        if (empty($data)) {
            $likeCount = 0;
            $dislikeCount = 0;
        } else {
            $likeArr = (isset($data->likeCount)) ? $data->likeCount : array();
            $dislikeArr = (isset($data->dislikeCount)) ? $data->dislikeCount : array();

            // like
            $likeCount = count($likeArr);
            $dislikeCount = count($dislikeArr);

            if (Auth::check()) {
                if (in_array(Auth::user()->id, $likeArr)) {
                    $prefer = 'like';
                } 

                if (in_array(Auth::user()->id, $dislikeArr)) {
                    $prefer = 'dislike';
                }
            }
        }

        $data = array(
            'likeCount' => $likeCount, 
            'dislikeCount' => $dislikeCount, 
            'prefer' => $prefer,
        );
        
        return response()->json(['data' => $data, 'errMsg' => ''], 200);
    }

    public function update(Request $request)
    {
        $input = $request->all();

        $rules = [
            'classId' => 'nullable|alpha_num|max:12',
            'prefer' => Rule::in(['like', 'dislike']),
        ];

        $validator = Validator::make($input, $rules);
        
        if ($validator->fails()) {
            return response()->json(['data' => '', 'errMsg' => ''], 403);
        }
        
        $data = ClassListLike::where('classId', $input['classId'])->first();

        // 若無這筆資料則新建一個實例
        if (empty($data)) {
            $data = new ClassListLike;            
        }

        // 資料設定
        $likeArr = (isset($data->likeCount)) ? $data->likeCount : array();
        $dislikeArr = (isset($data->dislikeCount)) ? $data->dislikeCount : array();
        $prefer = '';

        $inLikeIndex = array_search(Auth::user()->id, $likeArr);
        $inDislikeIndex = array_search(Auth::user()->id, $dislikeArr);
        
        switch ($input['prefer']) {
            case 'like':
                if ($inLikeIndex === false) {
                    array_push($likeArr, Auth::user()->id);
                    $prefer = 'like';
                } else {
                    unset($likeArr[$inLikeIndex]);
                }

                if ($inDislikeIndex !== false) {
                    unset($dislikeArr[$inDislikeIndex]);
                }
                break;
            case 'dislike':
                if ($inLikeIndex !== false) {
                    unset($likeArr[$inLikeIndex]);
                }

                if ($inDislikeIndex === false) {
                    array_push($dislikeArr, Auth::user()->id);
                    $prefer = 'dislike';
                } else {
                    unset($dislikeArr[$inDislikeIndex]);
                }
                break;
        }
    
        // 寫入資料庫
        $data->classId = $input['classId'];
        $data->likeCount = $likeArr;
        $data->dislikeCount = $dislikeArr;
        $data->save();

        $data = array(
            'likeCount' => count($likeArr), 
            'dislikeCount' => count($dislikeArr), 
            'prefer' => $prefer,
        );

        return response()->json(['data' => $data, 'errMsg' => ''], 200);
    }
}
