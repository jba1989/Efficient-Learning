<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Requests\MessageValidate;
use Auth;
use Exception;
use DB;

class MessageController extends Controller
{        
    /**
     * 新增對某classId的留言
     *
     * @param  \App\Http\Requests\MessageValidate  $request
     */
    public function create(MessageValidate $request)
    {
        try {
            $input = $request->all();

            // 寫入資料庫
            DB::beginTransaction();
            Message::insert([
                'classId' => $input['classId'],
                'fatherId' => (isset($input['fatherId']) ? $input['fatherId'] : null),
                'userName' => Auth::user()->name,
                'message' => $input['message'],
            ]);
            DB::commit();

            return redirect()->back();
        } catch (Exception $err) {
            DB::rollBack();
            Log::error($err);
            return redirect()->back()->withInput();
        }
    }
}
