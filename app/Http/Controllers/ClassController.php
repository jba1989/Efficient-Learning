<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList as ClassList;
use App\Models\TotalClass as TotalClass;

class ClassController extends Controller
{
    public function classList()
    {
        $data = ClassList->all()->paginate(20);
        return view('/mooc/classlist', $data);
    }

    public function classOfSchool($school)
    {
        $data = TotalClass->where('school', $school)->orderBy('id', 'asc')->paginate(30);
        return view('/mooc/classOfSchool', $data);
    }

    public function singleClass($classId)
    {
        $data = TotalClass->where('classId', $classId)->orderBy('classId', 'asc');
        return view('/mooc/singleclass', $data);
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
