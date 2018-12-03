<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Http\Requests\MessageValidate;
use Validator;
use Auth;

class ApiMessageController extends Controller
{
    /**
     * 新增對某classId的留言
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function create(MessageValidate $request)
    {        
        $input = $request->all();

        $status = Message::insert([
            'classId' => $input['classId'],
            'fatherId' => (isset($input['fatherId']) ? $input['fatherId'] : null),
            'userName' => Auth::user()->name,
            'message' => htmlspecialchars($input['message']),
            ]);

        if ($status) {
            return response()->json(['message' => '', 'errors' => array()], 200);
        } else {            
            $errMsg = array(trans('dictionary.Fail'));
            return response()->json(['message' => '', 'errors' => ['message' => $errMsg]], 403);
        }
    }

    /**
     * 修改某id的留言
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(MessageValidate $request)
    {
        $id = $request->input('id');
        $message = htmlspecialchars($request->input('message'));

        $status = Message::where(['id' => $id, 'userName' => Auth::user()->name])
            ->update(['message' => $message]);
            
        if ($status) {
            return response()->json(['message' => '', 'errors' => array()], 200);
        } else {            
            $errMsg = array(trans('dictionary.Edit') . trans('dictionary.Fail'));
            return response()->json(['message' => '', 'errors' => ['message' => $errMsg]], 403);
        }
    }

    /**
     * 刪除某id的留言
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function delete(MessageValidate $request)
    { 
        $id = $request->input('id');

        $status = Message::where(['id'=> $id, 'userName'=> Auth::user()->name])->delete();

        if ($status) {
            return response()->json(['message' => '', 'errors' => ''], 200);
        } else {
            $errMsg = array(trans('dictionary.Delete') . trans('dictionary.Fail'));
            return response()->json(['message' => '', 'errors' => ['message' => $errMsg]], 403);
        }
    }
}
