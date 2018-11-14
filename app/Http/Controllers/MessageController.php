<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MessageService;
use Auth;
use Validator;

class MessageController extends Controller
{    
    protected $message;

    /**
     * 注入Service
     */    
    public function __construct(MessageService $messageService)
    {
        $this->message = $messageService;
    }
    
    /**
     * 新增對某classId的留言
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();
//dd ($input);
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
            return redirect('/index/class?class=' . $input['classId'])->withErrors($validator)->withInput();
        }

        $contents = [
            'classId' => $input['classId'],
            'fatherId' => (isset($input['fatherId']) ? $input['fatherId'] : null),
            'userName' => $user->name,
            'message' => htmlspecialchars($input['message']),
        ];

        $this->message->create($contents);

        return redirect('/index/class?class=' . $input['classId']);
    }

    /**
     * 取得所有對此classId的留言
     *
     * @param  string  $classId
     * @return \Illuminate\Http\Response
     */
    public function show($classId)
    {
        $rules = [
            'classId' => 'required|alpha_num|max:12',
        ];

        $messages = [
            'required' => ':attribute 為必填欄位',
            'alpha_dash' => ':attribute 請勿輸入特殊字元',
            'max'=> ':attribute 不可超過 :max 個字',
        ];

        $validator = Validator::make(['classId' => $classId], $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['data' => null], 200);
        }

        $conditions = ['classId' => $classId];
        $data = $this->message->show($conditions);
        
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
        
        $conditions = [
            'id' => $input['id'],
            'userName' => $user->name,
        ];
        
        $contents = [
            'message' => htmlspecialchars($input['message'])
        ];

        $this->message->update($conditions, $contents);
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
            return redirect("/class/$classId")->withErrors($validator);
        }
        
        $conditions = [
            'id'=> $input['id'],
            'userName'=> $user->name,
        ];

        $this->message->delete($conditions);
    }
}
