<?php

namespace app\api\controller;


use  app\common\business\Comment as BusComment;
use app\common\lib\AjaxResult;
// extends AuthBase 
class Comment  extends AuthBase
{
    
    /**
     * 写评论
     * @param $data
     * @return void
     */
    public function createComment($data)
    {
        try{
            BusComment::createComments($data);
        }catch (\Exception $th) {
            return AjaxResult::abort($th);
        }

        return AjaxResult::msg('保存成功',200);
    } 
    
    /**
     * 获取评论
     * @param $tid
     * @return array
     */
    public function commentUser($tid)
    {
        try{
            $data = BusComment::findComment($tid);
        }catch (\Exception $th) {
            return AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功',$data);
        
    }

    /**
     * 我的评论
     * @param $id
     * @param $page
     * @return array
     */
    public function  MyComment($id,$page)
    {
        try{
            $data = BusComment::MyComment($id,$page);
        }catch (\Exception $th) {
            return AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功',$data);
    }

    /**
     * 详情评价
     * @param $id
     * @return array
     */
    public function CommentDetail($id)
    {
        try{
            $data = BusComment::CommentDetail($id);
        }catch (\Exception $th) {
            return AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功',$data);
    }

    /**
     * 判断
     */
    public function  Judge($uid,$tid)
    {
        try{
            $data = BusComment::Judge($uid,$tid);
        }catch (\Exception $th) {
            return AjaxResult::abort($th);
        }
        return json([
            'status' => 200,
            'message' => '成功',
            'code'=>$data
        ]);
    }

}