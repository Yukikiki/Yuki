<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * 根据用户名查询用户信息
     * @param $username
     * @return mixed
     */
    public function check_exists($username)
    {
        $ret = $this -> where('username', $username) -> first();
        if(empty($ret)){
            return false;
        }
        return $ret;
    }

    /**
     * 新增一个用户
     * @param $data
     * @return array|bool|string
     */
    public function setAdd($data)
    {
        if(empty($data)){
            return returnData(RTN_STATUS_ERROR, '参数有误！');
        }
        $data['created_at'] = nowDate();
        $data['updated_at'] = nowDate();
        $ret = $this -> insertGetId($data);
        if(empty($ret)){
            return false;
        }
        return $ret;
    }
}
