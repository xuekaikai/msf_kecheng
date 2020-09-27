<?php

namespace app\common\model;

use think\Model;

class UsersIntegral extends Model
{
    /**
     * 关联老师
     *
     * @return void
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, 'id', 'teacher_id')->bind(['teacher_name' => 'name']);
    }
    /**
     * 关联学生
     *
     * @return void
     */
    public function getUsers()
    {
        return $this->hasOne(Users::class, 'id', 'user_id')->bind(['user_name' => 'name', 'user_integral' => 'integral']);
    }

    /**
     * 积分列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return object
     */
    public static function listIntegral($where, $page, $limit)
    {
        return self::with(['getTeacher', 'getUsers'])->where($where)->order('create_time', 'desc')->page($page, $limit)->select();  
    }
}
