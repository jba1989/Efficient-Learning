<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ClassControllerValidate;
use App\Models\ClassList;
use App\Models\TotalClass;
use App\Models\Message;
use Illuminate\Support\Facades\Redis;
use Config;

class ClassController extends Controller
{   
    public function showIndex()
    {        
        return view('mooc.index');
    }

    public function showClass(ClassControllerValidate $request)
    {
        $classId = $request->input('class', '');
        $school = $request->input('school', '');
        $classType = $request->input('type', '');
        $conditions = array();
        
        // 進入課程章節選單
        if ($classId != '') {
            $conditions = array('classId' => $classId);

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
        if ($school != '') {
            $conditions = array_merge($conditions, ['school' => $school]);
        }

        if ($classType != '') {
            $conditions = array_merge($conditions, ['classType' => $classType]);
        }
        
        $classes = ClassList::where($conditions)->orderBy('id', 'asc')->paginate($classPerPage);
        
        return view('mooc.classList', [
            'classes' => $classes,
            'school' => $school,
            'type' => $classType,
        ]);
    }
}
