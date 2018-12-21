<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList;
use App\Models\ClassListLike;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\ClassValidate;
use Auth;
use Exception;
use Log;
use DB;

class ApiClassController extends Controller
{
    public function getOptions()
    {         
        try {    
            return response()->json(['data' => Redis::get('classOptions'), 'errMsg' => ''], 200);
        } catch (Exception $err) {
            Log::error($err);            
        }
    }

    public function show(ClassValidate $request)
    {
        try {
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

            $response = array(
                'likeCount' => $likeCount, 
                'dislikeCount' => $dislikeCount, 
                'prefer' => $prefer,
            );
            
            return response()->json(['data' => $response, 'errMsg' => ''], 200);
        } catch (Exception $err) {
            Log::error($err);            
        }
    }

    public function update(ClassValidate $request)
    {
        try {
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
            DB::beginTransaction();
            $data->classId = $input['classId'];
            $data->likeUserList = $likeArr;
            $data->dislikeUserList = $dislikeArr;
            $data->likeCount = count($likeArr);
            $data->dislikeCount = count($dislikeArr);
            $data->save();
            DB::commit();

            $response = array(
                'likeCount' => count($likeArr), 
                'dislikeCount' => count($dislikeArr), 
                'prefer' => $prefer,
            );

            return response()->json(['data' => $response, 'errMsg' => ''], 200);
        } catch (Exception $err) {
            DB::rollBack();
            Log::error($err);
        }
    }
}
