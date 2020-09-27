<?php

namespace app\common\model;

use think\Model;

class ClassHourWork extends Model
{
    /**
     * 关联班级获取班级数据
     *
     * @return void
     */
    public function getClassHour()
    {
        return $this->hasOne(ClassHour::class, 'id', 'class_id')->bind(['class_name' => 'name', 'class_img' => 'img', 'class_type' => 'type', 'people_count' => 'people_count']);
    }

    /**
     * 关联老师
     *
     * @return void
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, 'id', 'teacher_id')->bind(['teacher_name' => 'name', 'teacher_head' => 'head', 'teacher_temperature' => 'temperature']);
    }

    /**
     * 添加/更新 班级作业
     *
     * @param array $data
     * @param int $id
     * @return void
     */
    public static function addOrUpdateClassHourWork($data, $id = '')
    {
        if (empty($id)) {
            $ClassHourWork = self::create($data);
            return $ClassHourWork;
        } else {
            $update = self::update($data, ['id' => $id]);
            return $update;
        }
    }

    /**
     * 作业列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return object
     */
    public static function list($where, $whereOr,$page = 0, $limit = 1)
    {
        return self::with(['getClassHour', 'getTeacher'])->where($where)->whereOr($whereOr)->order('create_time', 'desc')->page($page, $limit)->select();
    }
}
