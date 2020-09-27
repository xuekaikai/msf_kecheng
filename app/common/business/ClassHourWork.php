<?php

namespace app\common\business;

use app\common\model\ClassHourSign;
use app\common\model\ClassHourWork as ModelClassHourWork;
use app\common\model\Temperatures;

class ClassHourWork
{
    /**
     * 添加/更新 班级作业
     *
     * @param array $data
     * @param int $id
     * @return void
     */
    public static function addOrUpdateClassHourWork($data, $id = '')
    {
        $data['wrok_img'] = json_encode($data['wrok_img']);
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        // 老师体温
        $data['temperature'] = Temperatures::where('tid', $data['teacher_id'])->value('temperature');
        $addOrUpdate = ModelClassHourWork::addOrUpdateClassHourWork($data, $id);
        if (empty($addOrUpdate)) {
            throw new \Exception("操作失败");
        }
        return $addOrUpdate;
    }

    /**
     * 作业列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function list($where, $page, $limit)
    {
        $whereSql = [];
        $whereOr = [];
        if (!empty($where['start_time'])) {
            $whereSql[] = ['start_time', '>=', strtotime($where['start_time'] . ' ' . '00:00:00')];
            $whereSql[] = ['start_time', '<=', strtotime($where['start_time'] . ' ' . '23:59:59')];
        }
        if (!empty($where['id'])) {
            $whereSql[] = ['id', '=', $where['id']];
        }
        if (!empty($where['user_id'])) {
            if (!empty($where['start_time'])) {
                // 找出用户相关的班级ID
                $class_id = ClassHourSign::where('user_id', $where['user_id'])
                    ->where('start_time', '>=', strtotime($where['start_time'] . ' ' . '00:00:00'))
                    ->where('start_time', '<=', strtotime($where['start_time'] . ' ' . '23:59:59'))
                    ->column('class_id');
            } else {
                // 找出用户相关的班级ID
                $class_id = ClassHourSign::where('user_id', $where['user_id'])->column('class_id');
            }
            // 班级ID去重
            $class_id = array_unique($class_id);
        }
        if (!empty($class_id)) {
            foreach ($class_id as $key => $value) {
                $whereOr[] = ['class_id', '=', $value];
            }
        }
        // dump($whereSql);exit;
        // 根据老师Id搜索
        if (!empty($where['teacher_id'])) {
            $whereSql[] = ['teacher_id', '=', $where['teacher_id']];
        }
        // 根据作业标题模糊搜索
        if (!empty($where['work_name'])) {
            $whereSql[] = ['work_name', 'like', '%' . $where['work_name'] . '%'];
        }
        // 根据班级搜索
        if (!empty($where['class_id'])) {
            $whereSql[] = ['class_id', '=', $where['class_id']];
        }
        $data = ModelClassHourWork::list($whereSql, $whereOr, $page, $limit)->toArray();
        if (empty($data)) {
            throw new \Exception("获取失败");
        }
        foreach ($data as $key => &$val) {
            $val['wrok_img'] = json_decode($val['wrok_img']);
            $val['start_time'] = date('Y-m-d H:i:s', $val['start_time']);
            $val['end_time'] = date('Y-m-d H:i:s', $val['end_time']);
        }
        $count = ModelClassHourWork::count();
        return ['data' => $data, 'count' => $count];
    }

    /**
     * 删除作业
     *
     * @param int $id
     * @return void
     */
    public static function del($id)
    {
        $del = ModelClassHourWork::destroy($id);
        if (empty($del)) {
            throw new \Exception("删除失败");
        }
        return $del;
    }
}
