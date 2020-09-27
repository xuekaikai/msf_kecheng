<?php

namespace app\api\controller;

use app\common\business\Course as BusinessCourse;
use app\common\lib\AjaxResult;

class Course extends AuthBase
{
    /**
     * 课程一级分类
     *
     * @return json
     */
    public function listCourseClass()
    {
        try {
            $data = BusinessCourse::listCourseClass();
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    /**
     * 课程二级分类
     *
     * @param int $class_id
     * @return json
     */
    public function listCourseClassType($class_id)
    {
        try {
            $list = BusinessCourse::listCourseClassType($class_id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $list, 200);
    }

    /**
     * 获取课程列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return json
     */
    public function listCourse($where = [], $page, $limit)
    {
        try {
            $data = BusinessCourse::listCourse($where, $page, $limit);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }
}
