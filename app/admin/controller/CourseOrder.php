<?php

namespace app\admin\controller;

use app\common\business\CourseOrder as BusinessCourseOrder;
use app\common\lib\AjaxResult;
class CourseOrder extends AdminBase
{
    /**
     * 获取订单
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return josn
     */
    public function listCourseOrder($where, $page, $limit)
    {
        try {
            $data = BusinessCourseOrder::listCourseOrder($where, $page, $limit);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取订单成功', $data, 200);
    }

    /**
     * 删除订单
     *
     * @param int $id
     * @return json
     */
    public function delCourseOrder($id)
    {
        try {
            BusinessCourseOrder::delCourse($id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('删除成功', 200);
    }
}
