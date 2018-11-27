<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList;
use Validator;
use Illuminate\Validation\Rule;
use Auth;

class ApiClassController extends Controller
{
    public function showLikeCount(Request $request)
    {
        $input = $request->all();

        $rules = [            
            'classId' => 'nullable|alpha_num|max:12',            
        ];

        $validator = Validator::make($input, $rules);
        
        if ($validator->fails()) {
            return response()->json(['data' => '', 'errMsg' => ''], 403);
        }

        $data = ClassList::select('likeCount', 'dislikeCount')->where('classId', $input['classId'])->first();
        $prefer = '';

        // like
        if (isset($data->likeCount) && ($data->likeCount != '')) {
            $likeCountArr = explode(',', $data->likeCount);
            $likeCount = count($likeCountArr);

            if (Auth::check() && in_array(Auth::user()->id, $likeCountArr)) {
                $prefer = 'like';
            }
        } else {
            $likeCount = 0;
        }

        // dislikeCount
        if (isset($data->dislikeCount) && ($data->dislikeCount != '')) {
            $dislikeCountArr = explode(',', $data->dislikeCount);
            $dislikeCount = count($dislikeCountArr);

            if (Auth::check() && in_array(Auth::user()->id, $dislikeCountArr)) {
                $prefer = 'dislike';
            }
        } else {
            $dislikeCount = 0;
        }
        
        $data = array(
            'likeCount' => $likeCount, 
            'dislikeCount' => $dislikeCount, 
            'prefer' => $prefer,
        );
        
        return response()->json(['data' => $data, 'errMsg' => ''], 200);
    }

    public function updateLikeCount(Request $request)
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

        $data = ClassList::select('likeCount', 'dislikeCount')->where('classId', $input['classId'])->first();
        
        $likeStr = $data->likeCount;
        $dislikeStr = $data->dislikeCount;
        $likeArr = array();
        $dislikeArr = array();
        $status = false;
        $prefer = '';

        if (isset($likeStr) && ($likeStr != '')) {
            $likeArr = explode(',', $likeStr);
        }

        if (isset($dislikeStr) && ($dislikeStr != '')) {
            $dislikeArr = explode(',', $dislikeStr);
        }

        $inLikeIndex = array_search(Auth::user()->id, $likeArr);
        $inDislikeIndex = array_search(Auth::user()->id, $dislikeArr);
        
        switch ($input['prefer']) {
            case 'like':
                if ($inLikeIndex === false) {                    
                    $likeArr[] = Auth::user()->id;
                    $likeStr = implode(',', $likeArr);
                    $status = true;
                    $prefer = 'like';
                } else {
                    unset($likeArr[$inLikeIndex]);
                    $likeStr = implode(',', $likeArr);
                    $status = true;                    
                }

                if ($inDislikeIndex !== false) {
                    unset($dislikeArr[$inDislikeIndex]);
                    $status = true;
                    $dislikeStr = implode(',', $dislikeArr);
                }
                break;                
            case 'dislike':
                if ($inLikeIndex !== false) {
                    unset($likeArr[$inLikeIndex]);
                    $likeStr = implode(',', $likeArr);
                    $status = true;
                }

                if ($inDislikeIndex === false) {
                    $dislikeArr[] = Auth::user()->id;
                    $dislikeStr = implode(',', $dislikeArr);
                    $status = true;
                    $prefer = 'dislike';
                } else {
                    unset($dislikeArr[$inDislikeIndex]);
                    $dislikeStr = implode(',', $dislikeArr);
                    $status = true;
                }
                break;
        }

        if ($status == true) {
            ClassList::where('classId', $input['classId'])->update(['likeCount' => $likeStr, 'dislikeCount' => $dislikeStr]);
        }

        $data = array(
            'likeCount' => count($likeArr), 
            'dislikeCount' => count($dislikeArr), 
            'prefer' => $prefer,
        );

        return response()->json(['data' => $data, 'errMsg' => ''], 200);
    }
}
