<?php

namespace app\common\model;

use think\Model;

class CourseClass extends Model
{
    /**
     * 关联课程二级分类查询
     *
     * @return void
     */
    public function courseClass()
    {
        return $this->hasMany(CourseClassType::class,'class_id');
    }

    /**
     * 关联二级分类查询所有分类
     *
     * @return object
     */
    public static function getWhereClassIdList()
    {
        return self::with('courseClass')->select();
    }
}