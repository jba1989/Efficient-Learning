<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Auth;
use Validator;

class MessageController extends Controller
{        
    /**
     * 新增對某classId的留言
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        $rules = [
            'classId' => 'required|alpha_num|max:12',
            'fatherId' => 'nullable|integer',
            'message' => 'required|max:300',
        ];

        $messages = [
            'required' => ':attribute 為必填欄位',
            'alpha_dash' => ':attribute 請勿輸入特殊字元',
            'integer' => ':attribute 只限輸入數字',
            'max'=> ':attribute 不可超過 :max 個字',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Message::insert([
            'classId' => $input['classId'],
            'fatherId' => (isset($input['fatherId']) ? $input['fatherId'] : null),
            'userName' => $user->name,
            'message' => htmlspecialchars($input['message']),
            ]);

        return redirect('/index/class?class=' . $input['classId']);
    }

    /**
     * 取得所有對此classId的留言
     *
     * @param  string  $classId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $input = $request->all();

        $rules = [
            'classId' => 'required|alpha_num|max:12',
            'msg_page' => 'nullable|integer|max:4',
            'msg_per_page' => 'nullable|integer|in([25, 50,100])',
        ];

        $validator = Validator::make(['classId' => $classId], $rules);

        if ($validator->fails()) {
            return response()->json(['data' => null], 200);
        }

        // 設定讀取頁數
        $msg_page = (isset($input['msg_page'])) ? $input['msg_per_page'] : 1;
        $msg_per_page = (isset($input['msg_per_page'])) ? $input['msg_per_page'] : 25;
        
        $data = Message::where('classId', $classId)
            ->orderBy('id', 'asc')
            ->paginate($msg_per_page, ['*'], $msg_page);
        
        return response()->json(['data' => $data], 200);
    }

    /**
     * 修改某id的留言
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request)
    {        
        $user = Auth::user();
        $input = $request->all();
dd ($input);
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
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        Message::where(['id' => $input['id'], 'userName' => $user->name])
            ->update(['message' => $input['message']]);
        
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
            return redirect()->back()->withErrors($validator);
        }
        
        Message::where($conditions)->delete([
            'id'=> $input['id'],
            'userName'=> $user->name,
        ]);
    }
}
