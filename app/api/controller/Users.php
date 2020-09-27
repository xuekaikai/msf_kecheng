<?php

namespace app\api\controller;

use app\common\business\User as BusUsers;
use app\common\business\Teachers as BusTeacher;
use app\common\business\Templatemsg as BusMsg;

use app\common\lib\AjaxResult;

class Users
{
    /**
     * 给学生加积分
     *
     * @param int $integral
     * @param int $user_id
     * @return json
     */
    public function addIntegral($data)
    {
        try {
            BusUsers::addIntegral($data);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(), 400);
        }
        return AjaxResult::msg('添加积分成功', 200);
    }

    /**
     *  用户个人信息
     * $type 2 用户个人信息 1 教师个人信息
     * @param  $id
     * @return array
     */
    public function UsersMes($type, $id)
    {
        if ($type == 2) {
            try {
                $data = BusUsers::userMes($id);
            } catch (\Exception $e) {
                return AjaxResult::error($e->getMessage(), 400);
            }
            return AjaxResult::success('获取成功', $data);
        } else {
            try {
                $data = BusTeacher::TearcherMes($id);
            } catch (\Exception $e) {
                return AjaxResult::error($e->getMessage(), 400);
            }
            return AjaxResult::success('获取成功', $data);
        }
    }

    /**
     * 编辑用户信息
     * @param $id
     * @param $data
     * @return void
     */
    public function EditorUsers($type, $id, $data)
    {

        if ($type == 2) {
            try {
                BusUsers::editorUser($id, $data);
            } catch (\Exception $e) {
                return AjaxResult::error($e->getMessage(), 400);
            }
            return AjaxResult::msg('更改成功', 200);
        } else {
            try {
                BusTeacher::editorTeacher($id, $data);
            } catch (\Exception $e) {
                return AjaxResult::error($e->getMessage(), 400);
            }
            return AjaxResult::msg('更改成功', 200);
        }
    }

    /**
     * 积分列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return json
     */
    public function listIntegral($where = [], $page, $limit)
    {
        try {
            $data = BusUsers::listIntegral($where, $page, $limit);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(), 400);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    public  function gettoken()
    {
        $data = BusMsg::getApptoken();
        return AjaxResult::success('获取成功', $data, 200);
    }

    public function welogin($code)
    {
        try {
            $data =  BusUsers::welogin($code);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(), $e->getCode());
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

}
