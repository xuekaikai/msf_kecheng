<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Course extends Model
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

    /**
     * 课程列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return object
     */
    public static function listCourse($whereSql, $page, $limit)
    {
        return self::with(['getCourseClassName', 'getCourseClassTypeName', 'getTeacher'])
            ->where($whereSql)
            ->append(['type_text', 'state_text'])
            ->page($page, $limit)
            ->order('create_time', 'desc')
            ->select();
    }

    /**
     * 添加 / 编辑课程
     *
     * @param array $data
     * @param int $id
     * @return void
     */
    public static function addOrUpdateCourse(array $data, int $id = null)
    {
        if (!empty($id)) {
            $updateCourse = self::update($data, ['id' => $id]);
            return $updateCourse;
        } else {
            $addCourse = self::create($data);
            return $addCourse;
        }
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

    /**
     * 获取器 转换文字state
     *
     * @param string $value
     * @return void
     */
    public function getStateTextAttr($value, $data)
    {
        $status = [1 => '预约', 2 => '试听'];
        return $status[$data['state']];
    }
}
