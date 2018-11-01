<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClassService;

class ClassController extends Controller
{
    protected $classService;

    /**
     * 注入repository
     */    
    public function __construct(ClassService $classService)
    {
        $this->classService = $classService;
    }

    public function showClass()
    {       
        $data = $this->classService->showClass();
        return view('mooc.classList', ['data' => $data]);
    }

    /**
     * 依課程分類查詢
     * 
     * @param string $classType
     * @return view
     */
    public function showClassByType($classType)
    {
        $conditions = array('classType' => $classType);
        $data = $this->classService->showClassBy($conditions);
        return view('mooc.classList', ['data' => $data]);
    }

    /**
     * 依學校查詢課程
     * 
     * @param string $school
     * @return view
     */
    public function showClassBySchool($school)
    {
        $conditions = array('school' => $school);
        $data = $this->classService->showClassBy($conditions);
        return view('mooc.classList', ['data' => $data]);
    }

    /**
     * 依條件查詢章節
     * 
     * @param string $classId
     * @return view
     */
    public function showTitleById($classId)
    {
        $conditions = array('classId' => $classId);
        $data = $this->classService->showTitleBy($conditions);
        $message = Message::where('classId', $classId)->where('fatherId', null)->orderBy('created_at', 'asc')->paginate(30);
        return view('mooc.singleClass', ['data' => $data, 'message' => $message]);
    }
}
