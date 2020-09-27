<?php

namespace app\admin\controller;

use app\common\business\AdminUsers as BusAdminUser;
use app\common\business\Subscribes as BusSubscribes;
use app\common\business\User as BusUser;
use app\common\BaseController;
use app\common\lib\AjaxResult;


class AdminUser
{

    /**
     * 用户列表
     * @param $where
     * @param $page
     * @return array
     */
    public function getUserList($where = [], $page, $limit)
    {
        try {
            $data = BusUser::UsersList($where, $page, $limit);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功', $data);
    }

    /**
     * 管理员登录
     * @return array
     * 
     */
    public function AdminLogin($username, $password)
    {
        if (!request()->isPost() || empty($username) || empty($password)) {
            return AjaxResult::error('非法访问', 403);
        }
        try {
            $data = BusAdminUser::Login($username, $password);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(), 400);
        }
        return AjaxResult::success('登录成功', $data['info'])->header($data['header']);
    }

    /**
     * 修改密码
     * @param $username
     * @param $pwd
     * @return void
     */
    public function Changepwd($username, $password)
    {
        try {
            BusAdminUser::Changepwd($username, $password);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('修改成功', 200);
    }

    /**
     * 退出登录
     */
    public function loginOut()
    {
        $token = request()->header('access_token');
        if ($token) {
            cache('admin_token_' . $token, null);
        }
        return AjaxResult::msg('退出成功', 200);
    }

    /**
     *  删除用户
     * @param  $id
     * @return void
     */
    public function DelUser($id)
    {
        try {
            BusUser::Deluser($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(), 400);
        }
        return AjaxResult::msg('删除成功', 200);
    }

    /**
     * 用户审核
     */
    public function AuditUser($status, $data)
    {
        try {
            BusUser::AuditUser($status, $data);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(), 400);
        }
        return AjaxResult::msg('更改成功', 200);
    }

    /**
     * 编辑用户
     * @param $id
     * @param $data
     * @return void
     */
    public function editorUser($id, $data)
    {
        try {
            BusUser::editorUser($id, $data);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(), 400);
        }
        return AjaxResult::msg('更改成功', 200);
    }

    /**
     * 获取用户信息
     * @param $id
     * @return array

     */
    public function userMes($id)
    {
        try {
            $data = BusUser::userMes($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功', $data);
    }

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
            BusUser::addIntegral($data);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('添加积分成功', 200);
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
            $data = BusUser::listIntegral($where, $page, $limit);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }
    
    /**
     * 创建说明
     * @return json
     */
    public function createExplain($id='',$data)
    {
        try {
             BusAdminUser::Creasteexplain($id,$data);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('保存成功',200);
    }

    /**
     * 获取
     * @return array
     */
    public function getExplain()
    {
        try {
            $data = BusAdminUser::getExplain();
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }
    
    /**
     * 预约列表
     * @param $page
     * @param $limit
     * @return array
     */
    public function subscribeList($page,$limit)
    {
        try{
            $data = BusSubscribes::SubscribeList($page,$limit);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }

    /**
     * 删除预约
     * @param $id
     * 
     */
    public function delOrder($id)
    {
        try{
            BusSubscribes::delOrder($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('删除成功',200);
    }
}
