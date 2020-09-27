<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class CourseOrder extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    // 关联课程表
    public function getCourse()
    {
        return $this->hasOne(Course::class, 'id', 'course_id')->bind(['course_name' => 'name', 'course_img' => 'img', 'course_price' => 'price', 'course_type' => 'type','course_desc'=>'desc','course_class_time'=>'class_time']);
    }
    // 关联用户表
    public function getUser()
    {
        return $this->hasOne(Users::class, 'id', 'user_id')->bind(['user_name' => 'name', 'user_phone' => 'phone', 'user_head' => 'head']);
    }
    /**
     * 订单列表
     *
     * @param array $whereSql
     * @param int $page
     * @param int $limit
     * @return object
     */
    public static function listCourseOrder($whereSql, $page, $limit)
    {
        return self::with(['getCourse', 'getUser'])->where($whereSql)->order('create_time', 'desc')->append(['type_text'])->page($page, $limit)->select();
    }

    public static function listOrder($whereSql)
    {
        return self::with(['getUser'])->where($whereSql)->select();
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
        return $status[$data['course_type']];
    }
}
