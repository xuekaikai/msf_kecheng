<?php

namespace app\common\model;

use think\Model;

class ClassHourSign extends Model
{
    /**
     * 关联班级获取班级数据
     *
     * @return void
     */
    public function getClassHour()
    {
        return $this->hasOne(ClassHour::class, 'id', 'class_id')->bind(['class_name' => 'name', 'class_img' => 'img', 'class_type' => 'type', 'people_count' => 'people_count', 'class_time_count' => 'class_time_count']);
    }
    /**
     * 关联课程二级分类取name值
     *
     * @return void
     */
    public function getCourseClassTypeName()
    {
        return $this->hasOne(Users::class, 'id', 'user_id')->bind(['user_name' => 'name', 'user_head' => 'head']);
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
     * 班级目录
     *
     * @param array $whereSql
     * @param int $page
     * @param int $limit
     * @return object
     */
    public static function listClassHourSignUp($whereSql, $whereOr, $page, $limit)
    {
        return self::with(['getClassHour', 'getTeacher'])->where($whereSql)->whereOr($whereOr)->append(['class_type_text'])->page($page, $limit)->order('create_time', 'desc')->select();
    }

    /**
     * 打卡列表
     *
     * @param int $class_hour_id
     * @return object
     */
    public static function listClassHourSign($whereSql, $page, $limit)
    {
        return self::with(['getCourseClassTypeName', 'getTeacher', 'getClassHour'])
            ->where($whereSql)
            ->append(['type_text', 'is_sign_in_text'])
            ->page($page, $limit)
            ->order('create_time', 'desc')
            ->select();
    }

    /**
     * 获取器 转换文字Type
     *
     * @param string $value
     * @return void
     */
    public function getClassTypeTextAttr($value, $data)
    {
        $status = [1 => '一对一', 2 => '小班', 3 => '大班'];
        return $status[$data['class_type']];
    }
    /**
     * 获取器 转换文字Type
     *
     * @param string $value
     * @return void
     */
    public function getTypeTextAttr($value, $data)
    {
        $status = [0 => '学生', 1 => '老师'];
        return $status[$data['type']];
    }

    /**
     * 获取器 转换文字state
     *
     * @param string $value
     * @return void
     */
    public function getIsSignInTextAttr($value, $data)
    {
        $status = [0 => '未打卡', 1 => '已打卡'];
        return $status[$data['is_sign_in']];
    }
}
