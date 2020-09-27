<?php

declare(strict_types=1);

namespace app\api\controller;

use app\common\lib\AjaxResult;

class AuthBase extends ApiBase
{
    /**
     * 密钥
     *
     * @var string
     */
    public static $AccessToken = '';

    /**
     * 后台用户信息
     *
     * @var array
     */
    public static $UserInfo = [];

    /**
     * 初始化
     *
     * @return static
     */
    public function initialize()
    {
        parent::initialize();
        try {
            self::isLogin();
        } catch (\Exception $th) {
            AjaxResult::abort($th);
        }
    }

    /**
     * 判断是否登录
     *
     * @return static 缓存用户信息
     */
    public static function isLogin()
    {
        self::$AccessToken = request()->header('Access-Token');
        if (empty(self::$AccessToken)) {
            throw new \Exception('非法请求', 401);
        }

        $userInfo = cache('api_token_' . self::$AccessToken);
        if (empty($userInfo)) {
            throw new \Exception('令牌过期，请重新登录', 401);
        }
        self::$UserInfo = $userInfo;
    }
}
