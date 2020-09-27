<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class ClassHour extends Model
{
    /**
     * 软删除定义
     */
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    /**
     * 关联课程一级分类取neme值
     *
     * @return void
     */
    public function getCourseClassName()
    {
        return $this->hasOne(CourseClass::class, 'id', 'class_id')->bind(['class_name' => 'label']);
    }

    /**
     * 关联课程二级分类取name值
     *
     * @return void
     */
    public function getCourseClassTypeName()
    {
        return $this->hasOne(CourseClassType::class, 'id', 'type_id')->bind(['type_name' => 'label']);
    }

    /**
     * 关联老师
     *
     * @return void
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, 'id', 'teacher_id')->bind(['teacher_name' => 'name', 'teacher_head' => 'head']);
    }

    // /**
    //  * 我的课程
    //  *
    //  * @param int $page
    //  * @param int $limit
    //  * @return object
    //  */
    // public static function listMeClassHour($page = 0, $limit = 1, $user_id)
    // {
    //     return self::with('getTeacher')->append(['type_text'])->page($page, $limit)->select();
    // }

    /**
     * 添加/编辑 班级
     *
     * @param array $data
     * @param int $id
     * @return void
     */
    public static function addOrUpulodClassHour($data, $id)
    {
        if (!empty($id)) {
            $updateCourse = self::update($data, ['id' => $id]);
            return $updateCourse;
        } else {
            $addCourse = self::create($data);
            return $addCourse->id;
        }
    }

    /**
     * 获取班级列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return object
     */
    public static function listClassHour($whereSql, $page, $limit)
    {
        return self::with(['getCourseClassName', 'getCourseClassTypeName', 'getTeacher'])
            ->where($whereSql)
            ->append(['type_text'])
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
    public function getTypeTextAttr($value, $data)
    {
        $status = [1 => '一对一', 2 => '小班', 3 => '大班'];
        return $status[$data['type']];
    }
}
