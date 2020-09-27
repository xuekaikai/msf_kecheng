<?php

namespace app\common\model;

use think\Model;

class CourseClassType extends Model
{
    /**
     * 根据课程一级分类获取二级分类
     *
     * @param int $class_id
     * @return object
     */
    public static function getClassTypeList($class_id)
    {
        return  self::where('class_id',$class_id)->select();
    }
}