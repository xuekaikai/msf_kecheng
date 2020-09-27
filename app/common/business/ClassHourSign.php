<?php

namespace app\common\business;

use app\common\model\ClassHour;
use app\common\model\ClassHourSign as ModelClassHourSign;
use app\common\model\Teacher;

class ClassHourSign
{
    /**
     * 班级列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function listClassHourSignUp($where, $page, $limit)
    {
        $whereSql = [];
        if (!empty($where['class_date'])) {
            $whereSql[] = ['class_date', '=', $where['class_date']];
        }
        if (!empty($where['start_time'])) {
            $whereSql[] = ['start_time', '=', $where['start_time']];
        }
        if (!empty($where['end_time'])) {
            $whereSql[] = ['end_time', '=', $where['end_time']];
        }
        if (!empty($where['user_id'])) {
            $whereSql[] = ['user_id', '=', $where['user_id']];
        }
        if (!empty($where['teacher_id'])) {
            $whereSql[] = ['teacher_id', '=', $where['teacher_id']];
        }
        if (!empty($where['type'])) {
            $whereSql[] = ['type', '=', $where['type']];
        }
        if (!empty($where['class_id'])) {
            $whereSql[] = ['class_id', '=', $where['class_id']];
        }
        if (!empty($where['id'])) {
            $whereSql[] = ['id', '=', $where['id']];
        }

        $whereOr = [];
        // 前台一周课表筛选
        if (!empty($where['class_date_week'])) {
            foreach ($where['class_date_week'] as $key => $val) {
                $whereOr[] = ['class_date', '=', $val];
            }
        }
        $data = ModelClassHourSign::listClassHourSignUp($whereSql, $whereOr, $page, $limit);
        if (empty($data->toArray())) {
            throw new \Exception("获取数据失败");
        }
        $data = $data->toArray();
        foreach ($data as $key => &$val) {
            // 学生未打卡总数
            $val['user_is_sign_in'] = ModelClassHourSign::where('class_date', $val['class_date'])
                ->where('start_time', $val['start_time'])
                ->where('end_time', $val['end_time'])
                ->where('type', 0)
                ->where('is_sign_in', 0)
                ->count();
            // 学生已打卡总数
            $val['user_sign_in'] = ModelClassHourSign::where('class_date', $val['class_date'])
                ->where('start_time', $val['start_time'])
                ->where('end_time', $val['end_time'])
                ->where('type', 0)
                ->where('is_sign_in', 1)
                ->count();
            // 能否打卡
            if (time() > strtotime($val['class_date'] . ' ' . $val['start_time']) && time() < strtotime($val['class_date'] . ' ' . $val['end_time'])) {
                // 可以打卡
                $val['is_sign'] = 0;
            } else {
                $val['is_sign'] = 1;
            }
        }
        $count = ClassHour::count();
        $adminCount = ModelClassHourSign::where($whereSql)->whereOr($whereOr)->count();
        return ['data' => $data, 'count' => $count, 'adminCount' => $adminCount];
    }

    /**
     * 我的课程
     *
     * @param int $user_id
     * @param int $type
     * @return array
     */
    public static function listMeClassHour($page, $limit, $user_id, $type)
    {
        if ($type == 2) {
            $class_id = ModelClassHourSign::where('user_id', $user_id)->column('class_id');
        } else {
            $class_id = ModelClassHourSign::where('teacher_id', $user_id)->column('class_id');
        }
        $class_id = array_unique($class_id);
        $data = ClassHour::page($page, $limit)->select($class_id);
        if(empty($data->toArray())){
            throw new \Exception("获取失败");
        }
        $count_time_type = 0;
        foreach ($data as $key => &$value) {
            $value['zongkeshi'] = ModelClassHourSign::where('class_id', $value['id'])->where('type', 1)->count();
            // 总课时
            $ClassHourSign = ModelClassHourSign::where('class_id', $value['id'])->where('type', 1)->order('id', 'desc')->find();
            if (time() > strtotime($ClassHourSign->class_date . ' ' . $ClassHourSign->end_time)) {
                $value['time_type'] = 1;
            } else {
                $value['time_type'] = 0;
                $count_time_type += 1;
            }
            $value['teacher_name'] = Teacher::where('id', $ClassHourSign->teacher_id)->value('name');
            $value['sign'] = $ClassHourSign->toArray();
        }
        // 总共多少班级
        $count = ClassHour::count();
        $classHour = ClassHour::select($class_id);
        $sumClass = count($classHour);
        return ['data' => $data, 'count' => $count, 'count_time_type' => $count_time_type, 'sumClass' => $sumClass];
    }

    /**
     * 打卡
     *
     * @param int $sign_id
     * @return void
     */
    public static function isSignIn($sign_id, $is_sign_in)
    {

        $classHourSign = ModelClassHourSign::find($sign_id);
        // 能否打卡
        if (time() < strtotime($classHourSign->class_date . ' ' . $classHourSign->start_time)) {
            throw new \Exception("打卡时间未到");
        }
        if (time() > strtotime($classHourSign->class_date . ' ' . $classHourSign->end_time)) {
            throw new \Exception("打卡时间已过");
        }
        $classHourSign->is_sign_in = $is_sign_in;
        $classHourSign->save();
        if (empty($classHourSign)) {
            throw new \Exception("打卡失败");
        }
        return $classHourSign;
    }

    /**
     * 后台打卡
     *
     * @param int $sign_id
     * @return void
     */
    public static function isSignInAdmin($sign_id, $is_sign_in)
    {

        $classHourSign = ModelClassHourSign::find($sign_id);
        $classHourSign->is_sign_in = $is_sign_in;
        $classHourSign->save();
        if (empty($classHourSign)) {
            throw new \Exception("打卡失败");
        }
        return $classHourSign;
    }

    /**
     * 获取班级学生
     *
     * @param int $class_id
     * @return array
     */
    public static function studentList($class_id)
    {
        $list = ModelClassHourSign::where('class_id', $class_id)->where('type', 0)->column('user_id');
        if (empty($list)) {
            throw new \Exception("获取失败",200);
        }
        $list = array_unique($list);
        $student = [];
        foreach ($list as $key => $val) {
            $student[] = $val;
        }
        return $student;
    }

    /**
     * 添加学生
     *
     * @param int $class_id
     * @param array $user_id
     * @return json
     */
    public static function saveStudent($class_id, $user_id)
    {
        $class_time = ModelClassHourSign::where('class_id', $class_id)->where('type', 1)->column('class_date,start_time,end_time');
        $saveStudent = [];
        foreach ($class_time as $key => $value) {
            foreach ($user_id as $k => $v) {
                $saveStudent[] = ['class_id' => $class_id, 'user_id' => intval($v), 'class_date' => $value['class_date'], 'start_time' => $value['start_time'], 'end_time' => $value['end_time']];
            }
        }
        $save = new ModelClassHourSign();
        $save->saveAll($saveStudent);
        if (empty($save)) {
            throw new \Exception("添加失败");
        }
        return $save;
    }

    /**
     * 删除学生
     *
     * @param int $user_id
     * @return void
     */
    public static function delStudent($user_id, $class_id)
    {
        $del = ModelClassHourSign::where('user_id', $user_id)->where('class_id', $class_id)->where('type', 0)->delete();
        if (empty($del)) {
            throw new \Exception("删除成功");
        }
        return $del;
    }
}
