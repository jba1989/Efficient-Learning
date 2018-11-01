<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MessageRepository;
use Auth;
use Validator;

class MessageController extends Controller
{    
    protected $message;

    /**
     * 注入repository
     */    
    public function __construct(MessageRepository $messageRepository)
    {
        $this->message = $messageRepository;
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

        $conditions = array(
            'classId' => $input['classId'],
            'fatherId' => $input['fatherId'],
            'userName' => $user->name,
            'message' => htmlspecialchars($input['message']),
        );

        $this->message->create($conditions);
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

        $conditions = array('classId' => $classId);
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
        
        $this->message->update($user, $input);
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
        
        $this->message->delete($user, $input);
    }
}
