<?php
/**
 * 评论模型
 * Created by PhpStorm.
 * User: Yuki
 * Date: 2017/10/26
 * Time: 11:52
 */

namespace App\models;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * 添加评论
     * @param $data
     * @return bool
     */
    public function setAdd($data)
    {
        if(empty($data)){
            return false;
        }
        $data['created_at'] = nowDate();
        $ret = $this -> insertGetId($data);
        if(empty($ret)){
            return false;
        }
        return $ret;
    }

    /**
     * 点赞
     * @param $id
     * @return bool
     */
    public function setLikeById($id)
    {
        if(isset($id)){
            return false;
        }
        $ret = $this -> where('id', $id) -> increment('like', 1);
        if(!$ret){
            return false;
        }
        return true;
    }
}