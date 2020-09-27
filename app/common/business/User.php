<?php

namespace app\common\business;

use app\common\lib\Str;
use app\common\lib\Aliyunsms as libsms;
use app\common\model\Teacher as TeacherModel;
use app\common\model\Users as UsersModel;
use app\common\model\UsersIntegral;

class User
{
    /**
     * 前台用户注册
     *
     * @param array $data
     * @return void
     */
    public static function redister($data)
    {
        $user_id = UsersModel::getUserIdByWhere('phone', $data['phone']);
        if (!empty($user_id)) {
            throw new \Exception("该手机号已被注册");
        }
        if (empty($data['code'])) {
            throw new \Exception("验证码不能为空");
        }
        $code = cache($data['phone'] . '_code');
        if ($code != $data['code']) {
            throw new \Exception("验证码不正确");
        }
        $data['password'] = md5($data['password']);
        $saveUser = UsersModel::saveUser($data);
        if (empty($saveUser)) {
            throw new \Exception("注册失败");
        }
        return true;
    }

    /**
     * 前台用户登录
     *
     * @param int $phone
     * @param int $code
     * @param string $password
     * @return array
     */
    public static function login($phone, $code = '', $password = '', $type)
    {
        // 1学生登录
        if ($type == 1) {
            $user = UsersModel::getUserByWhere('phone', $phone);
        } else {
            $user = TeacherModel::getTeacherByWhere('phone', $phone);
        }
        if (empty($user)) {
            throw new \Exception('手机号有误');
        }
        if (!empty($code)) {
            $phone_code = cache($user->phone . '_code');
            if ($code != $phone_code) {
                throw new \Exception("验证码不正确");
            }
        }
        if (!empty($password)) {
            if ($user->password != md5($password)) {
                throw new \Exception("密码不正确");
            }
        }
        if ($type == 1) {
            if ($user->audit == 0) {
                throw new \Exception("账号待审核,请联系管理员");
            }
        }
        $token = Str::token($user->phone);    //生成token
        if ($type == 1) {
            $userList = UsersModel::getUserByWhere('phone', $user['phone'], ['password'])->toArray();
            $userList['is_teach'] = 2;
        } else {
            $userList = TeacherModel::getTeacherByWhere('phone', $user['phone'], ['password'])->toArray();
            $userList['is_teach'] = 1;
        }
        cache('api_token_' . $token, $userList, 42000);  //缓存用户信息
        return [
            'header' => [
                'Access-Control-Expose-Headers' => "Access-Token",
                'Access-Token' => $token,
            ],
            'info' => $userList,
        ];
    }


    /**
     *  发送验证码
     *
     * @param  $phone
     * @return void
     */
    public static function sendCode($phone, $smscode)
    {
        // 随机验证码
        $code = rand(100000, 999999);
        Cache($phone . '_code', $code, 200);
        libsms::send( $phone, $smscode, $code);
        return $code;
    }

    /**
     * 用户列表
     * @return 
     */
    public static function UsersList($where, $page, $limit)
    {
        $data = [];
        if (!empty($where['phone'])) {
            $data[] = ['phone', '=', $where['phone']];
        }
        if (isset($where['audit'])) {
            $data[] = ['audit', '=', $where['audit']];
        }
        $whereList = UsersModel::getUserByWhereSelect($data, $page, $limit, $hidden = ['password']);
        $count = UsersModel::getUserByWhereCount($data);
        if(empty($whereList)){
            throw new \Exception('数据为空');
        }else{
            $data = $whereList->toArray();
        }
        return [
            'count' => $count,
            'data' => $whereList
        ];
    }

    /**
     * 删除用户
     */
    public static function Deluser($id)
    {
        if (empty($id)) {
            throw new \Exception('数据为空');
        }
        $user = new UsersModel();
        return $user->destroy($id, true);
    }


    /**
     * 用户审核
     * @param  $status 1 通过 2 拒绝
     * @param  $data
     * @return void
     */
    public static function AuditUser($status, $data)
    {
        if (empty($data)) {
            throw new \Exception('数据为空');
        }
        $user = new UsersModel();
        if ($status == 1) {
            return $user->saveAll($data);
        } else {
            return $user->destroy($data, true);
        }
    }

    /**
     * 编辑用户
     * @param $id
     * @param $data
     * @return void
     */
    public static function  editorUser($id, $data)
    {
        if (empty($data)) {
            throw new \Exception('数据为空');
        }
        $user = new UsersModel();
        return $user->where('id', $id)->save($data);
    }


    /**
     * 单个用户信息
     * @param $id
     * @return array
     */
    public static function userMes($id)
    {
        if (empty($id)) {
            throw new \Exception('数据为空');
        }
        $user = UsersModel::getUserByWhere('id', $id, ['audit', 'password'])->toArray();
        return $user;
    }

    /**
     * 修改密码
     *
     * @param int $id
     * @param string $password
     * @param string $newPassword
     * @return void
     */
    public static function updatePassword($id, $password, $newPassword, $type)
    {
        if ($type == 1) {
            $user = TeacherModel::find($id);
        } else {
            $user = UsersModel::find($id);
        }
        if ($user->password != md5($password)) {
            throw new \Exception("旧密码不正确");
        }
        $user->password = md5($newPassword);
        $user->save();
        if ($user != true) {
            throw new \Exception("修改密码失败");
        }
        return $user;
    }

    /**
     * 忘记密码
     *
     * @param int $phone
     * @param int $code
     * @param int $type 1老师2学生
     * @param string $password
     * @return void
     */
    public static function forgetPassword($phone, $code, $password, $type)
    {
        if ($type == 1) {
            $id = TeacherModel::where('phone', $phone)->value('id');
        } else {
            $id = UsersModel::getUserIdByWhere('phone', $phone);
        }
        if (empty($id)) {
            throw new \Exception("该手机号不存在");
        }
        if (empty($code)) {
            throw new \Exception("验证码不能为空");
        }
        $phone_code = cache($phone . '_code');
        if ($code != $phone_code) {
            throw new \Exception("验证码不正确");
        }
        if ($type == 1) {
            $user = TeacherModel::find($id);
        } else {
            $user = UsersModel::find($id);
        }
        $user->password = md5($password);
        $user->save();
        if ($user != true) {
            throw new \Exception("找回密码失败");
        }
        return $user;
    }

    /**
     * 给学生加积分
     *
     * @param int $integral
     * @param int $user_id
     * @return void
     */
    public static function addIntegral($data)
    {
        $user = UsersModel::find($data['user_id']);
        if ($data['type'] == 0) {
            $user->integral = $user->integral + intval($data['integral']);
        } else {
            $user->integral = $user->integral - intval($data['integral']);
        }
        $user->save();
        if ($user != true) {
            throw new \Exception("更新学生积分失败");
        }
        $integral = new UsersIntegral();
        $integral->save($data);
        if ($integral != true) {
            throw new \Exception("添加积分失败");
        }
        return $integral;
    }

    /**
     * 积分列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function listIntegral($where, $page, $limit)
    {
        $whereSql = [];
        if (!empty($where['user_id'])) {
            $whereSql[] = ['user_id', '=', $where['user_id']];
            $Integral = UsersModel::where('id', $where['user_id'])->value('integral');
        }
        $data = UsersIntegral::listIntegral($whereSql, $page, $limit)->toArray();
        if (empty($data)) {
            throw new \Exception("获取失败");
        }
        $count = UsersIntegral::where($whereSql)->count();
        if(!empty($integral)){
            return ['data' => $data, 'count' => $count, 'integral' => $Integral];
        }
        return ['data' => $data, 'count' => $count];
    }

    public static function welogin($code)
    {
        $appid = "wxe67700fb7c1d4da4";
        $secret = "b08a9fe99de8088756dfbc37c7734b6c";
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";
        $res = json_decode(file_get_contents($url), true);
        if (!isset($res['openid'])) {
            throw new \Exception($res['errmsg'], $res['errcode']);
        }
        return $res;
    }
}
