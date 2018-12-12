<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Http\Requests\ApiMessageValidate;
use Auth;
use Exception;
use DB;

class ApiMessageController extends Controller
{
    /**
     * 新增對某classId的留言
     *
     * @param  \App\Http\Requests\ApiMessageValidate  $request
     */
    public function create(ApiMessageValidate $request)
    {     
        try {   
            $input = $request->all();

            // 寫入資料庫
            DB::beginTransaction();
            $status = Message::insert([
                'classId' => $input['classId'],
                'fatherId' => (isset($input['fatherId']) ? $input['fatherId'] : null),
                'userName' => Auth::user()->name,
                'message' => htmlspecialchars($input['message']),
            ]);
            DB::commit();

            if ($status) {
                return response()->json(['message' => '', 'errors' => array()], 200);
            } else {            
                $errMsg = array(trans('dictionary.Fail'));
                return response()->json(['message' => '', 'errors' => ['message' => $errMsg]], 403);
            }
        } catch (Exception $err) {
            DB::rollBack();
            Log::error($err);
        }
    }

    /**
     * 修改某id的留言
     * 
     * @param  \App\Http\Requests\ApiMessageValidate  $request
     */
    public function update(ApiMessageValidate $request)
    {
        try { 
            $id = $request->input('id');
            $message = htmlspecialchars($request->input('message'));

            // 寫入資料庫
            DB::beginTransaction();
            $status = Message::where(['id' => $id, 'userName' => Auth::user()->name])
                ->update(['message' => $message]);
            DB::commit();

            if ($status) {
                return response()->json(['message' => '', 'errors' => array()], 200);
            } else {            
                $errMsg = array(trans('dictionary.Edit') . trans('dictionary.Fail'));
                return response()->json(['message' => '', 'errors' => ['message' => $errMsg]], 403);
            }
        } catch (Exception $err) {
            DB::rollBack();
            Log::error($err);
        }
    }

    /**
     * 刪除某id的留言
     *
     * @param  \App\Http\Requests\ApiMessageValidate  $request
     */
    public function delete(ApiMessageValidate $request)
    {
        try {
            $id = $request->input('id');

            // 寫入資料庫
            DB::beginTransaction();
            $status = Message::where(['id'=> $id, 'userName'=> Auth::user()->name])->delete();
            DB::commit();

            if ($status) {
                return response()->json(['message' => '', 'errors' => ''], 200);
            } else {
                $errMsg = array(trans('dictionary.Delete') . trans('dictionary.Fail'));
                return response()->json(['message' => '', 'errors' => ['message' => $errMsg]], 403);
            }
        } catch (Exception $err) {
            DB::rollBack();
            Log::error($err);
        }
    }
}
