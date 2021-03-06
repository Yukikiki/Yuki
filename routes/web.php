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
Route::get('user/reg', 'UserController@create');
Route::get('user/login', 'UserController@login') -> name('login');
Route::get('user/logout', 'UserController@logout');
Route::get('blog/issue', 'BlogController@create');
Route::get('blog/change', 'BlogController@change');
Route::get('blog/detail', 'BlogController@detail');
Route::get('blog/delete', 'BlogController@remove');
Route::get('comm/add', 'CommentController@create');
Route::get('comm/like', 'CommentController@like');