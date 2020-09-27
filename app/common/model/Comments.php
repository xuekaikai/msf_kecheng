<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Comments extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getNameAttr($value,$data)
    {
        return Users::where('id',$data['uid'])->value('name')??'';
    }
    public function getHeadAttr($value,$data)
    {
        return Users::where('id',$data['uid'])->value('head')??'';
    }

    public function getTeacherNameAttr($value,$data)
    {
        return Teacher::where('id',$data['tid'])->value('name')??'';
    }

    public function getTeacherHeadAttr($value,$data)
    {
        return Teacher::where('id',$data['tid'])->value('head')??'';
    }

}