<?php

namespace app\admin\controller;

use app\common\business\ClassHourWork as BusinessClassHourWork;
use app\common\lib\AjaxResult;

class ClassHourWork extends AdminBase
{
    /**
     * 添加/更新 班级作业
     *
     * @param array $data
     * @param int $id
     * @return json
     */
    public function addOrUpdate($data, $id = '')
    {
        try {
            BusinessClassHourWork::addOrUpdateClassHourWork($data, $id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('操作成功', 200);
    }

    /**
     * 作业列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return json
     */
    public function list($where, $page, $limit)
    {
        try {
            $data = BusinessClassHourWork::list($where, $page, $limit);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    /**
     * 删除作业
     *
     * @param int $id
     * @return json
     */
    public function del($id)
    {
        try {
            BusinessClassHourWork::del($id);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('删除成功', 200);
    }
}
