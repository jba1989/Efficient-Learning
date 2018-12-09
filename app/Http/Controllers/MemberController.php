<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList;
use Auth;

class MemberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*
    public function __construct()
    {
        $this->middleware('auth');
    }
*/
    public function userInfo()
    {
        if (Auth::user()->favorite != null) {
            $favorites = ClassList::select('classId', 'className')->whereIn('classId', Auth::user()->favorite)->get()->toArray();
            
            return view('/mooc/member', ['favorites' => $favorites]);
        } else {
            return view('/mooc/member');
        }
    }
}
