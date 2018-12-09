<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList;
use App\Models\ClassListLike;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\ClassValidate;
use Auth;


class ApiClassController extends Controller
{
    public function getOptions()
    {
        $options = Redis::get('classOptions');
        if ($options == null) {
            $options = ClassList::select('classId', 'className')->get();
            Redis::set('classOptions', $options);
        }
        
        return response()->json(['data' => $options, 'errMsg' => ''], 200);
    }

    public function show(ClassValidate $request)
    {
        $classId = $request->input('classId');

        $data = ClassListLike::where('classId', $classId)->first();
        $prefer = '';

        // 若無這筆資料則新建一個實例
        if (empty($data)) {
            $likeCount = 0;
            $dislikeCount = 0;
        } else {
            $likeCount = $data->likeCount;
            $dislikeCount = $data->dislikeCount;

            if (Auth::check()) {
                $likeArr = (isset($data->likeUserList)) ? $data->likeUserList : array();
                $dislikeArr = (isset($data->dislikeUserList)) ? $data->dislikeUserList : array();

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

    public function update(ClassValidate $request)
    {
        $input = $request->all();
        
        $data = ClassListLike::where('classId', $input['classId'])->first();

        // 若無這筆資料則新建一個實例
        if (empty($data)) {
            $data = new ClassListLike;
        }

        // 資料設定
        $likeArr = (isset($data->likeUserList)) ? $data->likeUserList : array();
        $dislikeArr = (isset($data->dislikeUserList)) ? $data->dislikeUserList : array();
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
        $data->likeUserList = $likeArr;
        $data->dislikeUserList = $dislikeArr;
        $data->likeCount = count($likeArr);
        $data->dislikeCount = count($dislikeArr);
        $data->save();

        $data = array(
            'likeCount' => count($likeArr), 
            'dislikeCount' => count($dislikeArr), 
            'prefer' => $prefer,
        );

        return response()->json(['data' => $data, 'errMsg' => ''], 200);
    }
}
