<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList;
use App\Models\TotalClass;
use App\Models\Message;
use Validator;
use Config;
use Illuminate\Support\Facades\Redis;

class ClassController extends Controller
{   
    public function showIndex()
    {        
        return view('mooc.index');
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
            'class_per_page' => 'nullable|integer|in([25, 50,100])',
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
            $titlePage = $request->input('page', 1);
            $msgPage = $request->input('msg_page', 1);
            $titlePerPage = $request->input('title_per_page', Config::get('constants.options.title_per_page'));
            $msgPerPage = $request->input('msg_per_page', Config::get('constants.options.msg_per_page'));
        
            $classes = ClassList::where($conditions)->first();
            $titles = TotalClass::where($conditions)->orderBy('titleId', 'asc')->paginate($titlePerPage);
            $messages = Message::where($conditions)->orderBy('id', 'asc')->paginate($msgPerPage, ['*'], 'msg_page');
            
            return view('mooc.singleClass', [
                'classes' => $classes,
                'titles' => $titles,
                'messages' => $messages,
                'page' => $titlePage,
                'msg_page' => $msgPage,
            ]);
        }

        $classPage = $request->input('page', 1);
        $classPerPage = $request->input('class_per_page', Config::get('constants.options.class_per_page'));

        // 進入課程選單
        if (isset($input['school']) && $input['school'] != '') {
            $school = $input['school'];
            $conditions = array_merge($conditions, ['school' => $input['school']]);
        }

        if (isset($input['type']) && $input['type'] != '') {
            $classType = $input['type'];
            $conditions = array_merge($conditions, ['classType' => $input['type']]);
        }
        
        $classes = ClassList::where($conditions)->orderBy('', 'asc')->paginate($classPerPage);
        
        return view('mooc.classList', [
            'classes' => $classes,
            'school' => $school,
            'type' => $classType
        ]);
    }
}
