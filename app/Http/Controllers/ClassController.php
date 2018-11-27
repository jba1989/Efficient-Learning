<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList;
use App\Models\TotalClass;
use Validator;

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
            'msg_page' => 'nullable|integer|max:4',
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
            $msg_page = (isset($input['msg_page'])) ? $input['msg_per_page'] : 1;
            $msg_per_page = (isset($input['msg_per_page'])) ? $input['msg_per_page'] : 25;
        
            $classes = ClassList::where($conditions)->first();
            $titles = TotalClass::where($conditions)->orderBy('titleId', 'asc')->paginate(30);
            $messages = ClassList::find(1)->messages()->where($conditions)->orderBy('id', 'asc')->paginate($msg_per_page, ['*'], $msg_page);

            return view('mooc.singleClass', [
                'classOptions' => $this->classOptions,
                'classes' => $classes,
                'titles' => $titles,
                'messages' => $messages,
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
