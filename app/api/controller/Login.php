<?php

namespace app\api\controller;

use app\common\business\User as UserBis;
use app\common\lib\AjaxResult;

class Login extends ApiBase
{
    /**
     * 前台学生注册
     *
     * @param  array $data
     * @return json
     */
    public function redister($data)
    {
        try {
            UserBis::redister($data);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('注册成功,请等待管理员审核', 200);
    }

    /**
     * 发送短信验证码
     *
     * @param int $phone
     * @return json
     */
    public function sendCode($phone,$smscode)
    {
        try {
            UserBis::sendCode($phone,$smscode);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('发送成功', 200);
    }

    /**
     * 前台登录
     *
     * @param int $phone
     * @param int $code
     * @param string $password
     * @return json
     */
    public function login($phone, $code = '', $password = '', $type)
    {
        try {
            $data = UserBis::login($phone, $code, $password, $type);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('登录成功', $data['info'])->header($data['header']);
    }

    /**
     * 退出登录
     *
     * @return json
     */
    public function loginOut()
    {
        $token = request()->header('access_token');
        if ($token) {
            cache('api_token_' . $token, null);
        }
        return AjaxResult::msg('退出成功', 200);
    }

    /**
     * 修改密码
     *
     * @param int $id
     * @param string $password
     * @param string $newPassword
     * @param int $type 1老师2学生
     * @return json
     */
    public function updatePassword($id, $password, $newPassword, $type)
    {
        try {
            UserBis::updatePassword($id, $password, $newPassword, $type);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('修改成功', 200);
    }

    /**
     * 忘记密码
     *
     * @param int $phone
     * @param int $code
     * @param string $password
     * * @param int $type 1老师2学生
     * @return json
     */
    public function forgetPassword($phone, $code, $password, $type)
    {
        try {
            UserBis::forgetPassword($phone, $code, $password, $type);
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
        return AjaxResult::msg('找回密码成功', 200);
    }
}
