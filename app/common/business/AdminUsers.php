<?php

namespace app\common\business;

use app\common\lib\Str;
use app\common\model\AdminUser as AdminUserModel;
use app\common\model\Instructions as ModelExplain;

class AdminUsers 
{ 
    /**
     * 后台登录
     * @param  $username
     * @param  $pwd
     * @return array
     */
    public static function Login($username,$pwd)
    {
        if(empty($username) && empty($pwd)){
            throw new \Exception('内容为空');
        }
        $user = new AdminUserModel();
        $Opwd = $user->where('username',$username)->value('password');
        if(empty($Opwd)){
            throw new \Exception('用户不存在');
        }elseif($Opwd == md5($pwd)){
            $token = Str::token($user->where('username',$username)->value('username'));
            $userList =  $user->where('username',$username)->select()->toArray();
            $user->where('username',$username)->save(['login_time'=>time()]);
            cache('admin_token_' . $token, $userList, 42000);
            return [
                'header' => [
                    'Access-Control-Expose-Headers' => "Access-Token",
                    'Access-Token' => $token,
                ],
                'info' => $userList,
            ];
        }else{
            throw new \Exception('密码错误');
        }
    }

    /**
     * 修改密码
     * @param  $username
     * @param  $pwd
     * @return void
     */
    public static function Changepwd($username,$pwd)
    {
        if(empty($username) && empty($pwd)){
            throw new \Exception('内容为空');
        }
        $user = new AdminUserModel();
        if(empty($user->where('username',$username)->find()))
        {
            throw new \Exception('用户名未找到');
        }else{
            return $user->where('username',$username)->save(['password' => md5($pwd)]);
        }
    }

    /**
     * 创建预约说明 
     * @param  $data
     * @return array
     */
    public static function Creasteexplain($id,$data)
    {
        if(empty($data)){
            throw new \Exception('数据为空');
        }
        $text = new ModelExplain();
        if(empty($id)){
            return $text->save($data);
        }else{
            return $text->where('id',$id)->save($data);
        }
    }

    /**
     * 获取
     * @return array
     */
    public static function getExplain()
    {
        $text = new ModelExplain();
        $data = $text->find(2);
        if (empty($data)) {
            $data = [
                'msg' => '没有数据'
            ];
        }else {
            $data = $data->toArray();
        }
        return $data;
    }
}