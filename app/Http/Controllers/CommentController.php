<?php
/**
 * 评论模型
 * Created by PhpStorm.
 * User: Yuki
 * Date: 2017/10/26
 * Time: 11:49
 */

namespace App\Http\Controllers;

use App\models\Blog;
use App\models\Comment;

class CommentController extends Controller
{
    private $_comment_mod;
    private $_blog_mod;
    /**
     * 构造函数
     * CommentController constructor.
     */
    public function __construct()
    {
        $this -> _comment_mod = new Comment;
        $this -> _blog_mod = new Blog;
    }

    public function detail()
    {
        $bid = rq('bid'); // 博客id
    }

    /**
     * 发布评论
     * bid/博客id cid/评论id
     * @return array|string
     */
    public function create()
    {
        $login = needLogin();
        if($login['status'] == RTN_STATUS_NOT_LOGIN){
            return returnData(RTN_STATUS_NOT_LOGIN, $login['msg']);
        }
        $bid = rq('bid');
        $cid = rq('cid');
        if(!$bid){
            return returnData(RTN_STATUS_ERROR, '参数错误！');
        }
        $blog = $this ->_blog_mod -> getBlogById($bid);
        if(!$blog){
            return returnData(RTN_STATUS_ERROR, '不存在该文章！');
        }
        $content = rq('content');
        if(!$content){
            return returnData(RTN_STATUS_ERROR, '您没有输入任何内容！');
        }
        $data = [
            'uid' => session('user_info.uid'),
            'bid' => $bid,
            'cid' => $cid,
            'content' => $content
        ];
        $ret = $this -> _comment_mod -> setAdd($data);
        if(!$ret){
            return returnData(RTN_STATUS_ERROR, '发表失败！');
        }
        return returnData(RTN_STATUS_SUCCESS, '发表成功！', $ret);
    }

    /**
     * 点赞
     * @return array|string
     */
    public function like()
    {
        $cid = rq('cid');
        $ret = $this -> _comment_mod -> setLikeById($cid);
        if(!$ret){
            return returnData(RTN_STATUS_ERROR, '点赞失败！');
        }
        return returnData(RTN_STATUS_SUCCESS, '点赞成功！', $ret);
    }

}