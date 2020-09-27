<?php
namespace app\admin\controller;

use app\common\BaseController;
use app\common\business\Comment as BusComment;
use app\common\lib\AjaxResult;

class AdminComment extends AdminBase
{
    /**
     * 审核评价列表
     * @return array
     */
    public function CommentList($page,$limit)
    {
        try{
            $data = BusComment::commentList($page,$limit);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }

    /**
     * 教师评价
     * @param $tid
     * @param $score
     * @param $page
     * @param $limit
     * @return array
     */
    public function teacherComm($tid,$score='',$page,$limit)
    {
        try{
            $data = BusComment::TeacherComm($tid,$score,$page,$limit);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    } 

    /**
     * 删除评论
     *
     * 
     */
    public function DelComment($status,$data)
    {
        try {
            BusComment::DelComment($status,$data);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('更改成功',200);
    }

}