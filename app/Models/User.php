<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * 根据用户名查询用户信息
     * @param $username
     * @return Model|null|static
     */
    public function check_exists($username)
    {
        $ret = $this -> where('username', $username) -> first();
        if(empty($ret)){
            return null;
        }
        return $ret;
    }

    public function add($data)
    {
        if(empty($data)){
            return returnData(RTN_STATUS_ERROR, '参数有误！');
        }
        $ret = $this -> insertGetId($data);
        if(empty($ret)){
            return false;
        }
        return $ret;
    }
}
