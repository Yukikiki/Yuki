<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * 博客模型
 * Class Blog
 * @package App\models
 */
class Blog extends Model
{
    //
    public function getList($where = [], $limit = 15, $skip = 0)
    {
        $where['status'] = 1;
        $ret = $this
            -> where($where)
            -> orderBy('updated_at')
            -> limit($limit)
            -> offset($skip)
            -> get(['id', 'title', 'content', 'uid', 'created_at', 'updated_at'])
            -> keyBy('id');
        if(empty($ret)){
            return false;
        }
        return $ret;
    }

    /**
     * 获取单条博客
     * @param $id
     * @return bool
     */
    public function getBlogById($id)
    {
        if(!isset($id)){
            return false;
        }
        $ret = $this -> where('id', $id) -> first();
        if(empty($ret)){
            return false;
        }
        return $ret;
    }
    /**
     * 添加博客
     * @param $data
     * @return bool
     */
    public function setAdd($data)
    {
        if(empty($data)){
            return false;
        }
        $data['created_at'] = nowDate();
        $data['updated_at'] = nowDate();
        $ret = $this -> insertGetId($data);
        if(empty($ret)){
            return false;
        }
        return $ret;
    }

    /**
     * 更改
     * @param $data
     * @param $where
     * @return bool
     */
    public function setBlogById($data, $where)
    {
        if(empty($data) || empty($where)){
            return false;
        }
        $ret = $this -> where($where) -> update($data);
        if(!$ret){
            return false;
        }
        return true;
    }

}
