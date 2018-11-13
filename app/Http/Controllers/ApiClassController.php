<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClassService;
use App\Models\ClassList;
use Validator;

class ApiClassController extends Controller
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
        $classes = ClassList::select('classId', 'className')->get();
        
        return response()->json(['classes' => $classes], 200);
    }
}
