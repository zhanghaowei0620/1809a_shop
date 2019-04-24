<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::any('xmladd','Weixin\WeixinController@xmladd');
//Route::any('accessToken','Weixin\WeixinController@accessToken');
Route::any('userInfo','Weixin\WeixinController@userInfo');
Route::any('createadd','Weixin\WeixinController@createadd');
Route::any('openiddo','Weixin\WeixinController@openiddo');
//微信支付
Route::any('wpay','Weixin\WeixinController@wpay');
Route::any('arr2Xml','Weixin\WeixinController@arr2Xml');
Route::any('notify','Weixin\WeixinController@notify');
Route::any('wstatus','Weixin\WeixinController@wstatus');
Route::any('paySuccess','Weixin\WeixinController@paySuccess');
Route::any('jsdemo','Weixin\JssdkController@jsdemo');

Route::get('getImg', 'Weixin\JssdkController@getImg');








