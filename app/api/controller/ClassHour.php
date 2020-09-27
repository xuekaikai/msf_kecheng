<?php

namespace app\api\controller;

use app\common\business\ClassHour as BusinessClassHour;
use app\common\business\ClassHourSign;
use app\common\lib\AjaxResult;

class ClassHour extends AuthBase
{
    /**
     * 获取班级打卡列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return json
     */
    public function listClassHourSign($where, $page = 0, $limit = 100)
    {
        try {
            $data = BusinessClassHour::listClassHourSign($where, $page, $limit);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    /**
     * 班级列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return json
     */
    public function listClassHourSignUp($where, $page, $limit)
    {
        try {
            $data = ClassHourSign::listClassHourSignUp($where, $page, $limit);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    /**
     * 我的课程
     *
     * @param int $user_id
     * @param int $type
     * @return json
     */
    public function listMeClassHour($page, $limit,$user_id,$type)
    {
        try {
            $data = ClassHourSign::listMeClassHour($page, $limit,$user_id,$type);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    /**
     * 打卡
     *
     * @param int $sign_id
     * @return json
     */
    public function isSignIn($sign_id, $is_sign_in)
    {
        try {
            ClassHourSign::isSignIn($sign_id, $is_sign_in);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('打卡成功', 200);
    }
}
