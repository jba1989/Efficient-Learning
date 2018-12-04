<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Requests\MessageValidate;
use Auth;
use Validator;

class MessageController extends Controller
{        
    /**
     * 新增對某classId的留言
     *
     * @param  \App\Http\Requests\MessageValidate  $request
     */
    public function create(MessageValidate $request)
    {
        $input = $request->all();

        Message::insert([
            'classId' => $input['classId'],
            'fatherId' => (isset($input['fatherId']) ? $input['fatherId'] : null),
            'userName' => Auth::user()->name,
            'message' => htmlspecialchars($input['message']),
            ]);

        return redirect()->back();
    }
}
