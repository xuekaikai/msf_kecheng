<?php

namespace app\admin\controller;

use app\common\business\Teachers as BusTeacher ;
use app\common\BaseController;
use app\common\lib\AjaxResult;


class AdminTeacher extends AdminBase
{
    /**
     * 创建教师
     * @param  $data
     * 
     */
    public function editorTeacher($data)
    {
        try {
            BusTeacher::createTeacher($data);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('创建成功',200);
    }

    /**
     * 教师列表
     * @param  $iphone
     * @param  $page
     * @return json
     */
    public function teacherList($phone='',$page,$limit)
    {
        try {
            $data =  BusTeacher::Teacherlist($phone,$page,$limit);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }

    /**
     * 删除教师
     * @param $id
     * @return void
     */
    public function delTeacher($id)
    {
        try {
            BusTeacher::delTeacher($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('删除成功',200);
    }

    /**
     * 信息修改
     * @param  $id
     * @param  $data
     * @return void
     */
    public function updateTeacher($id,$data)
    {
        try {
            BusTeacher::updateTeacher($id,$data);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('修改成功',200);
    }
}