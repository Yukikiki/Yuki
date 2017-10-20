<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    private $_user_mod;
    public function __construct()
    {
        $this -> _user_mod = new User;
    }

    /**
     * 检查参数
     */
    public function check_parameter()
    {
        $username = rq('username');
        $password = rq('password');
        if($password && $username){
            return ['username' => $username, 'password' => $password];
        }
        return false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $check_parameter = $this -> check_parameter();
        if(!$check_parameter){
            return returnData(RTN_STATUS_ERROR, '用户名和密码不可为空！');
        }
        $user_exists = $this -> _user_mod -> check_exists($check_parameter['username']);
        if($user_exists){
            return returnData(RTN_STATUS_ERROR, '用户名已存在！');
        }
        /*密码加密 bcrypt 是另一种加密写法*/
        $password = Hash::make($check_parameter['password']);

        $data = [
            'username' => $check_parameter['username'],
            'password' => $password,
        ];

        $ret = $this -> _user_mod -> add($data);
        if(!$ret){
            return returnData(RTN_STATUS_ERROR, '注册失败！');
        }
        return returnData(RTN_STATUS_SUCCESS, '注册成功！', $ret);



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
