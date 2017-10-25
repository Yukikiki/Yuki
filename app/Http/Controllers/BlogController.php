<?php

namespace App\Http\Controllers;

use App\models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    //
    private $_blog_mod;
    public function __construct()
    {
        $this -> _blog_mod = new Blog;
    }

    public function detail()
    {
        $id = rq('id');
        if($id){
            $blog = $this ->_blog_mod -> getBlogById($id);
            if(!$blog){
                return returnData(RTN_STATUS_ERROR, '不存在该文章！');
            }
            return returnData(RTN_STATUS_SUCCESS, '获取成功！', $blog);
        }
        /* —— 分页处理 ——*/
        // skip 是第几条后的数据 所以当前页的数据输减一
        $limit = rq('limit') ?: 15;
        //$skip = (rq('page') ? rq('page') - 1 : 0) * $limit;
        $skip = rq('page') ? rq('page') : 1;
        /* —— 结束 ——*/
        $res = $this -> _blog_mod -> getList([], $limit, $skip);
        $res = json_decode($res, true);
        if(empty($res)){
            return returnData(0, '数据为空');
        }
        return returnData(1, '获取成功', $res);
    }

    /**
     * 发表博客
     * @return array|string
     */
    public function create()
    {
        $login = needLogin();
        if($login['status'] == RTN_STATUS_NOT_LOGIN){
            return returnData(RTN_STATUS_NOT_LOGIN, $login['msg']);
        }

        $title = rq('title');
        $content = rq('content');
        if(!$title || !$content){
            return returnData(RTN_STATUS_ERROR, '必填项不可为空！');
        }
        $data = [
            'uid' => session('user_info.uid'),
            'title' => $title,
            'content' => $content
        ];
        $ret = $this ->_blog_mod -> setAdd($data);
        if(!$ret){
            return returnData(RTN_STATUS_ERROR, '发表失败！');
        }
        return returnData(RTN_STATUS_SUCCESS, '发表成功！', $ret);
    }

    /**
     * 编辑修改博客
     * @return array|string
     */
    public function change()
    {
        $login = needLogin();
        if($login['status'] == RTN_STATUS_NOT_LOGIN){
            return returnData(RTN_STATUS_NOT_LOGIN, $login['msg']);
        }
        $id = rq('id');
        $title = rq('title');
        $content = rq('content');
        if(!$id){
            return returnData(RTN_STATUS_ERROR, '参数错误！');
        }
        if(!$title && !$content){
            return returnData(RTN_STATUS_ERROR, '必填项不可为空！');
        }
        $blog = $this ->_blog_mod -> getBlogById($id);
        if(!$blog){
            return returnData(RTN_STATUS_ERROR, '不存在该文章！');
        }
        if(session('user_info.uid') != $blog['uid']){
            return returnData(RTN_STATUS_ERROR, '您无编辑该文章的权限！');
        }
        $data = [];
        if($title){
            $data['title'] = $title;
        }
        if($content){
            $data['content'] = $content;
        }
        $ret = $this -> _blog_mod -> setBlogById($data, ['id' => $id]);
        if(!$ret){
            return returnData(RTN_STATUS_ERROR, '保存失败！');
        }
        return returnData(RTN_STATUS_SUCCESS, '保存成功！');
    }

    /**
     * 删除博客
     * @return array|string
     */
    public function remove()
    {
        $login = needLogin();
        if($login['status'] == RTN_STATUS_NOT_LOGIN){
            return returnData(RTN_STATUS_NOT_LOGIN, $login['msg']);
        }
        $id = rq('id');
        if(!$id){
            return returnData(RTN_STATUS_ERROR, '参数错误！');
        }
        $blog = $this ->_blog_mod -> getBlogById($id);
        if(!$blog){
            return returnData(RTN_STATUS_ERROR, '不存在该文章！');
        }
        if(session('user_info.uid') != $blog['uid']){
            return returnData(RTN_STATUS_ERROR, '您无删除该文章的权限！');
        }
        $ret = $this -> _blog_mod -> setBlogById(['status' => 0], ['id' => $id]);
        if(!$ret){
            return returnData(RTN_STATUS_ERROR, '删除失败！');
        }
        return returnData(RTN_STATUS_SUCCESS, '删除成功！');
    }
}

