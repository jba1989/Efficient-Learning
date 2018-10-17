<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Auth;

class MessageController extends Controller
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

        Message::create([
            'classId' => $input['classId'],
            'fatherId' => $input['fatherId'],
            'userName' => $user->name,
            'message' => $input['message'],
        ]);
    }

    /**
     * 取得所有對此classId的留言
     *
     * @param  string  $classId
     * @return \Illuminate\Http\Response
     */
    public function show($classId)
    {
        $data = Message::where('classId', $classId)->orderBy('created_at', 'asc')->paginate(30);
        return $data;
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

        Message::where('id', $id)->where('userName', $user->name)->update(['message', $input['message']]); 
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

        Message::where('id', $id)->where('userName', $user->name)->delete();
    }
}
