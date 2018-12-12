<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList;
use Auth;
use Exception;

class MemberController extends Controller
{
    public function userInfo()
    {
        try {
            if (Auth::user()->favorite != null) {
                $favorites = ClassList::select('classId', 'className')->whereIn('classId', Auth::user()->favorite)->get()->toArray();
                
                return view('/mooc/member', ['favorites' => $favorites]);
            } else {
                return view('/mooc/member');
            }
        } catch (Exception $err) {
            Log::error($err);
        }
    }
}
