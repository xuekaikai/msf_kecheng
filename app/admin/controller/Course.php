<?php

namespace app\admin\controller;

use app\common\business\Course as BusinessCourse;
use app\common\lib\AjaxResult;
use app\common\model\Course as ModelCourse;
use app\common\model\CourseClass;

class Course extends AdminBase
{
    /**
     * 获取课程分类
     *
     * @return json
     */
    public function getCourserClass()
    {
        try {
            $data = BusinessCourse::listCourseClassAndType();
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    /**
     * 获取课程一级分类
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
     * 获取课程二级分类
     *
     * @return json
     */
    public function listCourseClassType($class_id)
    {
        try {
            $data = BusinessCourse::listCourseClassType($class_id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    /**
     * 删除分类
     *
     * @param int $id
     * @return json
     */
    public function delCourseClass($id, $type = '')
    {
        try {
            BusinessCourse::delCourseClassOrType($id, $type);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('删除成功', 200);
    }

    /**
     * 添加分类
     *
     * @param string $label
     * @return json
     */
    public function addCourseClass($label, $class_id = '', $type = '')
    {
        try {
            BusinessCourse::addCourseClassOrType($label, $class_id, $type);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('添加成功', 200);
    }

    /**
     * 添加 \ 编辑 课程
     *
     * @param array $data
     * @return json
     */
    public function addOrUpdateCourse(array $data, int $id = null)
    {
        try {
            BusinessCourse::addOrUpdateCourse($data, $id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('操作成功', 200);
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

    /**
     * 删除课程
     *
     * @param int $id
     * @return json
     */
    public function delCourse($id = [1])
    {
        try {
            BusinessCourse::delCourse($id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('删除成功', 200);
    }
}
