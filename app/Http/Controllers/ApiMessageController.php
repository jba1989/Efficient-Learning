<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Auth;
use Validator;

class ApiMessageController extends Controller
{
    /**
     * 修改某id的留言
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        $rules = [
            'id' => 'integer',
            'message' => 'required|max:300',
        ];

        $messages = [
            'required' => ':attribute 為必填欄位',
            'integer' => ':attribute 只限輸入數字',
            'max'=> ':attribute 不可超過 :max 個字',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['data' => '', 'errMsg' => $validator->errors()->first()], 403);
        }

        $status = Message::where(['id' => $input['id'], 'userName' => $user->name])
            ->update(['message' => $input['message']]);

        if ($status) {
            return response()->json(['data' => '', 'errMsg' => ''], 200);
        } else {            
            return response()->json(['data' => '', 'errMsg' => trans('Edit.Fail') . trans('dictionart.Fail')], 403);
        }
    }

    /**
     * 刪除某id的留言
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function delete(Request $request)
    {                
        $user = Auth::user();        
        $input = $request->all();

        $rules = ['id' => 'integer'];
        $messages = ['integer' => ':attribute 只限輸入數字'];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['data' => '', 'errMsg' => $validator->errors()->first()], 403);
        }
        
        $status = Message::where(['id'=> $input['id'], 'userName'=> $user->name])->delete();

        if ($status) {
            return response()->json(['data' => '', 'errMsg' => ''], 200);
        } else {            
            return response()->json(['data' => '', 'errMsg' => trans('Delete.Fail') . trans('dictionart.Fail')], 403);
        }
    }
}
