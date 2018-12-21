<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ClassValidate;
use App\Models\ClassList;
use App\Models\TitleList;
use App\Models\Message;
use Illuminate\Support\Facades\Redis;
use Config;
use Exception;
use Log;

class ClassController extends Controller
{   
    public function showIndex()
    {        
        return view('mooc.index');
    }

    public function showClass(ClassValidate $request)
    {
        try {
            // input資料設定
            $classId = $request->input('class', '');
            $school = $request->input('school', 'ntu');
            $classType = $request->input('type', '熱門課程');
            $search = $request->input('search', '');

            // 設定讀取頁數        
            $classPage = $request->input('page', 1);
            $classPerPage = $request->input('class_per_page', Config::get('constants.options.class_per_page'));
            $titlePage = $request->input('page', 1);
            $msgPage = $request->input('msg_page', 1);
            $titlePerPage = $request->input('title_per_page', Config::get('constants.options.title_per_page'));
            $msgPerPage = $request->input('msg_per_page', Config::get('constants.options.msg_per_page'));
            
            // 進入搜尋結果
            if ($search != '') {
                $classes = ClassList::where('classId', 'like', "%$search%")
                ->orWhere('className', 'like', "%$search%")
                ->paginate($classPerPage);

                return view('mooc.classList', [
                    'classes' => $classes,
                    'search' => $search,
                ]);
            }
        
            // 進入課程章節選單
            if ($classId != '') {
                // 若資料庫中沒有此classId則轉導
                if (!in_array($classId, json_decode(Redis::get('classIdList')))) {
                    return redirect()->route('class');
                }

                $conditions = array('classId' => $classId);

                $classes = ClassList::where($conditions)->first();
                $titles = TitleList::where($conditions)->orderBy('titleId', 'asc')->paginate($titlePerPage);
                $messages = Message::where($conditions)->orderBy('id', 'asc')->paginate($msgPerPage, ['*'], 'msg_page');
                
                return view('mooc.singleClass', [
                    'classes' => $classes,
                    'titles' => $titles,
                    'messages' => $messages,
                    'page' => $titlePage,
                    'msg_page' => $msgPage,                
                ]);
            }

            // 若資料庫中沒有此classType則轉導
            if (($classType != '熱門課程') && (!in_array($classType, json_decode(Redis::get('classTypes_' . $school))))) {
                return redirect()->route('class');
            }
                
            // 進入課程選單
            $conditions = array(
                'school' => $school,
                'classType' => $classType,
            );        
            
            if ($classType == '熱門課程') {           
                $classes = ClassList::select('class_list.classId', 'className', 'teacher', 'classType', 'school', 'likeCount')->join('class_list_like', 'class_list.classId', 'class_list_like.classId')->orderBy('likeCount', 'desc')->paginate($classPerPage);
            } else {
                $classes = ClassList::where($conditions)->orderBy('id', 'asc')->paginate($classPerPage);
            }
            
            return view('mooc.classList', [
                'classes' => $classes,
                'school' => $school,
                'type' => $classType,
                'classTypes' => json_decode(Redis::get('classTypes_' . $school)),
            ]);
        } catch (Exception $err) {
            Log::error($err);            
        }
    }
}
