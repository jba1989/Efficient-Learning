<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Auth;

class ApiMessageController extends Controller
{    
    /**
     * 新增對某classId的留言
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
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
            return redirect("/class/$classId")->withErrors($validator)->withInput();
        }

        Message::create([
            'classId' => $input['classId'],
            'fatherId' => $input['fatherId'],
            'userName' => $user->name,
            'message' => htmlspecialchars($input['message']),
        ]);

        return $this->show($input['classId']);
    }

    /**
     * 取得所有對此classId的留言
     *
     * @param  string  $classId
     * @return \Illuminate\Http\Response
     */
    public function show($classId)
    {
        $data = Message::where('classId', $classId)->orderBy('created_at', 'desc')->paginate(30);
        dd ($data);
        return response()->json($data, 200);
    }

    /**
     * 修改某id的留言
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function edit(Request $request)
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
            return redirect("/class/$classId")->withErrors($validator)->withInput();
        }

        Message::where('id', $id)->where('userName', $user->name)->update(['message', $input['message']]);
        
        return $this->show($input['classId']);
    }

    /**
     * 刪除某id的留言
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function delete($id)
    {
        $user = Auth::user();
        $input = $request->all();

        $rules = [
            'id' => 'integer',
        ];

        $messages = [
            'integer' => ':attribute 只限輸入數字',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return redirect("/class/$classId")->withErrors($validator);
        }

        Message::where('id', $id)->where('userName', $user->name)->delete();

        return $this->show($input['classId']);
    }
}
