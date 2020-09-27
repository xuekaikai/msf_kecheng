<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Subscribe extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getCourseName()
    {
        return $this->hasOne(Course::class,'id','course_id')->bind(['course_name' => 'name']);
    }
    

    public static function list( $page, $limit)
    {
        return self::withJoin(['getCourseName'])
            ->page($page, $limit)
            ->select();
    }

    public static function getNameAttr($value,$data)
    {
        return Course::where('id',$data['course_id'])->value('name')??'';
    }
}