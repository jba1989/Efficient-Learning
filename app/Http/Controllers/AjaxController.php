<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function index(){
        $msg = array(
            'apple',
            'bee',
            'car',
            'DOG',
        );
        return response()->json(array('msg'=> $msg), 200);
     }
}
