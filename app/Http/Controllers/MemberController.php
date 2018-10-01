<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function userInfo()
    {
        $userId = Auth::id();
        $data = DB::table('users')->where('id', userId)->get();
        return view('/mooc/userInfo', $data);
    }

    public function favorite()
    {
        $userId = Auth::id();
        $favoriteStr = DB::table('users')->where('id', userId)->value('favorite');
        $favoriteIdArr = explode($favoriteStr, ',');
        $favoriteClass = DB::table('class_list')->select('classId', 'className')
            ->whereIn('classId', $favoriteIdArr)->get()->orderBy('asc');
        return view('/mooc/favorite', $favoriteClass);
    }
}
