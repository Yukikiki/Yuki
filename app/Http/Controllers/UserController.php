<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $data['username'] = rq('username');
        $data['password'] = rq('password');
        if($data['password'] && $data['username']){
            return returnData(RTN_STATUS_SUCCESS, '', $data);
        }
        return returnData(RTN_STATUS_ERROR, '用户名和密码不可为空！');
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
     * 新增用户
     * @return array|string
     */
    public function create()
    {
        //
        $check_parameter = $this -> check_parameter();
        if($check_parameter['status'] == RTN_STATUS_ERROR){
            return returnData(RTN_STATUS_ERROR, $check_parameter['msg']);
        }
        $check_parameter = $check_parameter['data'];
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

        $ret = $this -> _user_mod -> setAdd($data);
        if(!$ret){
            return returnData(RTN_STATUS_ERROR, '注册失败！');
        }
        return returnData(RTN_STATUS_SUCCESS, '注册成功！', $ret);
    }

    /**
     * 用户登录
     * @return array|string
     */
    public function login()
    {
        /**
         * 1. 检测用户输入数据为空
         * 2. 检测用户名密码是否输入正确
         * 3. 输入正确写入session
         */
        $check_parameter = $this -> check_parameter();
        if($check_parameter['status'] == RTN_STATUS_ERROR){
            return returnData(RTN_STATUS_ERROR, $check_parameter['msg']);
        }
        $check_parameter = $check_parameter['data'];
        $user_exists = $this -> _user_mod -> check_exists($check_parameter['username']);
        if(!$user_exists){
            return returnData(RTN_STATUS_ERROR, '该用户不存在！');
        }

        if(!Hash::check($check_parameter['password'], $user_exists['password'])){
            return returnData(RTN_STATUS_ERROR, '用户名密码不正确！');
        }
        $data = [
            'uid' => $user_exists['id'],
            'username' => $user_exists['username'],
        ];
        session() -> put('user_info', $data);
        return returnData(RTN_STATUS_SUCCESS, '用户登录成功！', $user_exists['id']);
    }

    /**
     * 用户登出
     * @return array
     */
    public function logout()
    {
        // 清空所有session;
        //session() -> flush();
        // 删除指定的session
        session() -> forget('user_info');
        // 类似剪切功能 将值拿出来 并且session中不再有该值
        //$user_info = session() -> pull('user_info');
        //dd(session() -> all());
        return returnData(RTN_STATUS_SUCCESS, '用户退出成功！');
    }

}
