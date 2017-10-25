<?php
/**
 * 自定义全局函数
 * Created by PhpStorm.
 * User: Yuki
 * Date: 2017/10/20
 * Time: 16:31
 */
use Illuminate\Support\Facades\Request;

/**
 * 获取参数方法
 * @param null $key
 * @param null $default
 * @return mixed
 */
function rq($key = null, $default = null)
{
    if(!$key){
        return Request::all();
    }
    return Request::get($key, $default);
}

/**
 * 检查是否登录
 * @return mixed
 */
function needLogin()
{
    if (!session('user_info.uid')){
        return returnData(RTN_STATUS_NOT_LOGIN, '用户未登录！');
    }
}

/**
 * @param $status
 * @param $msg
 * @param $data
 * @param int $type 1/数组形式  2/json形式
 * @return array|string
 */
function returnData($status, $msg, $data = null, $type = 1)
{
    $ret = [
        'status' => $status ?: 0,
        'msg'    => $msg ?: null,
        'data'   => $data?: null
    ];
    if($type == 2){
        $ret = json_encode($ret);
    }
    return $ret;
}

/**
 * 获取当前时间
 * @return false|string
 */
function nowDate()
{
    return date('Y-m-d H:i:s', time());
}