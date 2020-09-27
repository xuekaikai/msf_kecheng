<?php

namespace app\common\business;

use app\common\lib\AjaxResult;
use app\common\model\ClassHour as ModelClassHour;
use app\common\model\ClassHourSign;

class ClassHour
{
    /**
     * 添加/编辑 班级
     *
     * @param array $data
     * @param int $id
     * @return void
     */
    public static function addOrUpulodClassHour($data, $id = '')
    {
        // 班级人数
        $data['people_count'] = count($data['class_user']);
        $data['class_time_count'] = count($data['class_time']);
        $addOrUpulodClassHour = ModelClassHour::addOrUpulodClassHour($data, $id = '');
        if (empty($addOrUpulodClassHour)) {
            throw new \Exception("添加班级失败");
        }

        // 课时数据处理
        $Class_hour_sign = [];
        // 创建学生打卡
        foreach ($data['class_user'] as $key => $value) {
            foreach ($data['class_time'] as $k => $val) {
                $Class_hour_sign[] = [
                    'class_id' => $addOrUpulodClassHour,
                    'user_id' => $value,
                    'class_date' => $val['class_date'],
                    'start_time' => $val['start_time'],
                    'end_time' => $val['end_time']
                ];
            }
        }

        // 创建老师打卡
        foreach ($data['class_time'] as $k => $val) {
            $Class_hour_sign[] = [
                'class_id' => $addOrUpulodClassHour,
                'teacher_id' => $data['teacher_id'],
                'class_date' => $val['class_date'],
                'start_time' => $val['start_time'],
                'end_time' => $val['end_time'],
                'type' => 1
            ];
        }
        $addClassHourSign = new ClassHourSign();
        $addClassHourSign->saveAll($Class_hour_sign);
        if (empty($addClassHourSign)) {
            throw new \Exception("添加课时失败");
        }
        return $addClassHourSign;
    }

    /**
     * 获取班级数据
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function listClassHour($where = [], $page, $limit)
    {
        $whereSql = [];
        if (!empty($where['name'])) {
            $whereSql[] = ['name', 'like', '%' . $where['name'] . '%'];
        }
        if (!empty($where['teacher_phone'])) {
            $whereSql[] = ['teacher_phone', '=', $where['teacher_phone']];
        }
        if (!empty($where['id'])) {
            $whereSql[] = ['id', '=', $where['id']];
        }
        $data = ModelClassHour::listClassHour($whereSql, $page, $limit);
        if (empty($data)) {
            return AjaxResult::msg('暂无课程', 200);
        }
        $count = ModelClassHour::count();
        return ['list' => $data, 'count' => $count];
    }

    /**
     * 删除班级
     *
     * @param [type] $id
     * @return void
     */
    public static function delClassHour($id)
    {
        $del = ModelClassHour::destroy($id);
        if ($del == true) {
            $delClassHourSign = ClassHourSign::where('class_id', '=', $id)->delete();
            if (empty($delClassHourSign)) {
                throw new \Exception("删除班级次目录失败");
            }
        }
        if ($del != true) {
            throw new \Exception("删除班级主目录失败");
        }
        return $del;
    }

    /**
     * 获取班级打卡列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function listClassHourSign($where, $page = 0, $limit = 100)
    {
        $whereSql = [];
        // 上课日期
        if (!empty($where['class_date'])) {
            $whereSql[] = ['class_date', '=', $where['class_date']];
        }
        // 班级ID
        if (!empty($where['class_id'])) {
            $whereSql[] = ['class_id', '=', $where['class_id']];
        }
        // 上课开始时间
        if (!empty($where['start_time'])) {
            $whereSql[] = ['start_time', '=', $where['start_time']];
        }
        // 上课结束时间
        if (!empty($where['end_time'])) {
            $whereSql[] = ['end_time', '=', $where['end_time']];
        }
        $data = ClassHourSign::listClassHourSign($whereSql, $page, $limit);
        if (empty($data)) {
            throw new \Exception("获取数据失败");
        }
        // 学生未打卡总数
        $user_is_sign_in = ClassHourSign::where($whereSql)->where('is_sign_in', 0)->where('type', 0)->count();
        // 学生已打卡总数
        $user_sign_in = ClassHourSign::where($whereSql)->where('is_sign_in', 1)->where('type', 0)->count();
        // 总数
        $count = ClassHourSign::where($whereSql)->count();
        return ['data' => $data, 'user_is_sign_in' => $user_is_sign_in, 'user_is_sign' => $user_sign_in, 'count' => $count];
    }

    /**
     * 修改班级
     *
     * @param array $data
     * @param int $id
     * @return void
     */
    public static function saveClassHour($data, $id)
    {
        $save = ModelClassHour::update($data, ['id' => $id]);
        if (empty($save)) {
            throw new \Exception("修改失败");
        }
        return $save;
    }
}
