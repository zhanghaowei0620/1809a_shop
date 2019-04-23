<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JssdkController extends Controller
{
    public function jsdemo(){
        $data = [];
        return view('weixin.jsdemo',$data);
    }
}
