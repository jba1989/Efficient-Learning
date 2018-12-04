<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList;
use App\Models\TotalClass;
use App\Models\Message;
use Validator;
use Config;

class ClassController extends Controller
{   
    public function __construct()
    {
        $this->classOptions = ClassList::select('classId', 'className')->get();
    }

    public function showIndex()
    {
        return view('mooc.index', ['classOptions' => $this->classOptions]);
    }

    public function showClass(Request $request)
    {
        $input = $request->all();
        $conditions = array();
        $school = '';
        $classType = '';

        $rules = [
            'school' => 'nullable|alpha_num|max:12',
            'type' => 'nullable|alpha_num|max:12',
            'classId' => 'nullable|alpha_num|max:12',
            'page' => 'nullable|integer|max:4',
            'msg_page' => 'nullable|integer|max:4',
            'title_per_page' => 'nullable|integer|in([25, 50,100])',
            'msg_per_page' => 'nullable|integer|in([25, 50,100])',
        ];

        $validator = Validator::make($input, $rules);

        // 返回課程選單
        if ($validator->fails()) {
            return redirect("/class");
        }

        // 進入課程章節選單
        if (isset($input['class'])) {
            $conditions = array('classId' => $input['class']);

            // 設定讀取頁數
            $title_page = (isset($input['page'])) ? $input['page'] : 1;
            $msg_page = (isset($input['msg_page'])) ? $input['msg_page'] : 1;
            $title_per_page = (isset($input['title_per_page'])) ? $input['title_per_page'] : Config::get('constants.options.title_per_page');
            $msg_per_page = (isset($input['msg_per_page'])) ? $input['msg_per_page'] : Config::get('constants.options.msg_per_page');
        
            $classes = ClassList::where($conditions)->first();
            $titles = TotalClass::where($conditions)->orderBy('titleId', 'asc')->paginate($title_per_page);
            $messages = Message::where($conditions)->orderBy('id', 'asc')->paginate($msg_per_page, ['*'], 'msg_page');
            
            return view('mooc.singleClass', [
                'classOptions' => $this->classOptions,
                'classes' => $classes,
                'titles' => $titles,
                'messages' => $messages,
                'page' => $title_page,
                'msg_page' => $msg_page,
            ]);
        }

        // 進入課程選單
        if (isset($input['school']) && $input['school'] != '') {
            $school = $input['school'];
            $conditions = array_merge($conditions, ['school' => $input['school']]);
        }

        if (isset($input['type']) && $input['type'] != '') {
            $classType = $input['type'];
            $conditions = array_merge($conditions, ['classType' => $input['type']]);
        }

        $classes = ClassList::where($conditions)->orderBy('id', 'asc')->paginate(30);
        
        return view('mooc.classList', [
            'classOptions' => $this->classOptions,
            'classes' => $classes,
            'school' => $school,
            'type' => $classType
        ]);
    }
}
