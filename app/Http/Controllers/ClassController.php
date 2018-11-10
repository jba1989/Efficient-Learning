<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClassService;
use Validator;

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

    public function showClass(Request $request)
    {
        $input = $request->all();
        $conditions = array();

        $rules = [
            'school' => 'nullable|alpha_num|max:12',
            'type' => 'nullable|alpha_num|max:12',
            'classId' => 'nullable|alpha_num|max:12',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return redirect("/class");
        }

        if (isset($input['class'])) {
            $conditions = array('classId' => $input['class']);

            $classes = $this->classService->showClassBy($conditions);
            $titles = $this->classService->showTitleBy($conditions);
            $messages = $this->classService->showMessageBy($conditions);

            return view('mooc.singleClass', [
                'classes' => $classes,
                'titles' => $titles,
                'messages' => $messages,
            ]);
        }

        if (isset($input['school'])) {
            $conditions = array_merge($conditions, ['school' => $input['school']]);
        }

        if (isset($input['type'])) {
            $conditions = array_merge($conditions, ['classType' => $input['type']]);
        }

        $classes = $this->classService->showClassBy($conditions);
        return view('mooc.classList', ['classes' => $classes]);
    }
}
