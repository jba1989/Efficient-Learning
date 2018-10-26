<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList;
use App\Models\TotalClass;
use App\Models\Message;
use App\Repositories\ClassRepository;

class ClassController extends Controller
{
    public function classList()
    {
        $data = $this->classRepository->classList();
        return view('mooc.classList', ['data' => $data]);
    }

    public function classListOfType($classType)
    {
        $data = ClassList::where('classType', $classType)->orderBy('id', 'asc')->paginate(30);
        return view('mooc.classList', ['data' => $data]);
    }

    public function classOfSchool($school)
    {
        $data = ClassList::where('school', $school)->orderBy('id', 'asc')->paginate(30);
        return view('mooc.classList', ['data' => $data]);
    }

    public function singleClass($classId)
    {
        $data = TotalClass::where('classId', $classId)->orderBy('titleId', 'asc')->paginate(30);
        $message = Message::where('classId', $classId)->where('fatherId', null)->orderBy('created_at', 'asc')->paginate(30);
        return view('mooc.singleClass', ['data' => $data, 'message' => $message]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
