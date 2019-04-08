<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeixinController extends Controller
{
	public function xmladd(Request $request)
        {
	  	 echo $request->input('echostr');
	         $str = file_get_contents("php://input");
	         $objxml = simplexml_load_string($str);
	         file_put_contents("/tmp/1809a.log", $str, FILE_APPEND);
        }
			    
}
