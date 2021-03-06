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

Route::get('/install', 'InstallController@index');
Route::post('/install', 'InstallController@install');

Route::get('/{title?}', 'IndexController@index');

//Initialize geetest
Route::get('auth/geetest','AuthController@getGeetest');
//Register
Route::get('/auth/register', 'AuthController@showRegisterView');
Route::post('/auth/register/captcha', 'AuthController@sendTextCaptcha');
Route::post('/auth/register', 'AuthController@addUser');
//Login
Route::get('/auth/login', 'AuthController@showLoginView');
Route::post('/auth/login', 'AuthController@login');
//Logout
Route::get('/auth/logout', 'AuthController@logout');
//Change password
Route::get('/auth/forget', 'AuthController@showForgetView');
Route::post('/auth/forget/captcha', 'AuthController@sendForgetTextCaptcha');
Route::post('/auth/forget', 'AuthController@changePassword');
//Confirm password
Route::get('/auth/confirm', 'AuthController@showConfirmView');
Route::post('/auth/confirm', 'AuthController@confirmLogin');

//Page Manage
Route::get('/page/left-nav/{title?}', 'PageController@index');
Route::post('/page', 'PageController@store')->middleware('checklogin');
Route::post('/page/move/{id}', 'PageController@move')->middleware('checksu');
Route::put('/page/{id}', 'PageController@update')->middleware('checksu');
Route::delete('/page/{id}', 'PageController@destroy')->middleware('checksu', 'checkrel');

//Wiki Manage
Route::post('/{title}/update', 'WikiController@store')->middleware('checklogin');
Route::put('/{title}/restore/{id}', 'WikiController@restore')->middleware('checklogin');
Route::get('/{title}/history', 'WikiController@history');
Route::get('/{title}/history/{id}', 'WikiController@getOneVersion');

//Comment Manage
Route::get('/{title}/comment', 'CommentController@index');
Route::post('/{title}/comment', 'CommentController@store')->middleware('checklogin');
Route::delete('/{title}/comment/{id}', 'CommentController@destroy')->middleware('checklogin');
Route::post('/{title}/comment/{id}/star', 'CommentController@star')->middleware('checklogin');

//Notice Manage
Route::post('/{title}/notice','NoticeController@store')->middleware('checkadmin');
Route::delete('/{title}/notice/{id}','NoticeController@destroy')->middleware('checkadmin');
Route::get('/{title}/notice','NoticeController@index');

//User Manage
Route::get('/user/all','UserController@allUser')->middleware('checkadmin');

//System Message Manage
Route::post('/admin/systemMessage','SystemMessageController@store')->middleware('checkadmin');
