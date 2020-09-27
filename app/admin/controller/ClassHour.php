<?php

namespace app\admin\controller;

use app\common\business\ClassHour as BusinessClassHour;
use app\common\business\ClassHourSign;
use app\common\lib\AjaxResult;

class ClassHour
{
    /**
     * 添加/编辑 班级
     *
     * @param array $data
     * @param int $id
     * @return json
     */
    public function addOrUpulodClassHour($data, $id = '')
    {
        try {
            BusinessClassHour::addOrUpulodClassHour($data, $id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('添加成功', 200);
    }

    /**
     * 获取班级数据
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return json
     */
    public function listClassHour($where = [], $page = 0, $limit = 10)
    {
        try {
            $data = BusinessClassHour::listClassHour($where, $page, $limit);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    /**
     * 删除班级
     *
     * @param [type] $id
     * @return json
     */
    public function delClassHour($id)
    {
        try {
            BusinessClassHour::delClassHour($id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('删除成功', 200);
    }

    /**
     * 获取班级打卡列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return json
     */
    public function listClassHourSign($where, $page = 0, $limit = 100)
    {
        try {
            $data = BusinessClassHour::listClassHourSign($where, $page, $limit);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    /**
     * 班级列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return json
     */
    public function listClassHourSignUp($where, $page, $limit)
    {
        try {
            $data = ClassHourSign::listClassHourSignUp($where, $page, $limit);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    /**
     * 打卡
     *
     * @param int $sign_id
     * @return json
     */
    public function isSignIn($sign_id, $is_sign_in)
    {
        try {
            ClassHourSign::isSignInAdmin($sign_id, $is_sign_in);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('打卡成功', 200);
    }

    /**
     * 修改班级
     *
     * @param array $data
     * @param int $id
     * @return json
     */
    public function saveClassHour($data, $id)
    {
        try {
            BusinessClassHour::saveClassHour($data, $id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('修改成功', 200);
    }

    /**
     * 班级学生
     *
     * @param int $class_id
     * @return json
     */
    public function studentList($class_id)
    {
        try {
            $data = ClassHourSign::studentList($class_id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    /**
     * 添加学生
     *
     * @param int $class_id
     * @param array $user_id
     * @return json
     */
    public function saveStudent($class_id,$user_id)
    {
        try {
            ClassHourSign::saveStudent($class_id,$user_id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('添加成功',200);
    }

    /**
     * 删除学生
     *
     * @param int $user_id
     * @return json
     */
    public function delStudent($user_id,$class_id)
    {
        try {
            ClassHourSign::delStudent($user_id,$class_id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('删除成功',200);
    }
}
