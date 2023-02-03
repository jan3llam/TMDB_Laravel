<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResponseController extends Controller
{
    static function success($data){

        return response()->json(array("code"=>0,"message"=>"success","data"=>$data), 200);
    }
    static function error($code,$message){

        return response()->json(array("code"=>$code,"message"=>$message,"data"=>null), 200);
    }
}
