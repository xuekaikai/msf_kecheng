<?php

namespace app\common\lib;

/**
 * 通用化API数据Json输出
 * 
 * @license [0] 提示消息
 * @license [200] OK
 * @license [205] 成功刷新
 * @license [302] 跳转
 * @license [400] 其他错误
 * @license [401] 身份认证
 * @license [403] 参数错误
 * @license [404] 访问错误
 * @license [500] 严重报错
 * 
 */
class AjaxResult
{
    /**
     * 提示消息
     * 
     * @param string $message 提示信息
     * @param integer $status 业务状态码
     * @param integer $httpStatus 服务器状态码
     * @return \think\response\Json
     */
    public static function msg(string $message, int $status = 0, int $httpStatus = 200)
    {
        $result = [
            'status' => $status,
            'message' => $message
        ];
        return json($result, $httpStatus);
    }

    /**
     * 成功格式
     * 
     * @param string $message 提示信息
     * @param array $data 数据
     * @param integer $status 业务状态码
     * @param integer $httpStatus 服务器状态码
     * @return \think\response\Json
     */
    public static function success(string $message, array $data, int $status = 200, int $httpStatus = 200)
    {
        $result = [
            'status' => $status,
            'message' => $message,
            'result' => $data
        ];
        return json($result, $httpStatus);
    }

    /**
     * 失败格式
     *
     * @param string $message 提示信息
     * @param integer $status 业务状态码
     * @param integer $httpStatus 服务器状态码
     * @return \think\response\Json
     */
    public static function error(string $message, int $status = 400, int $httpStatus = 200)
    {
        return self::msg($message, $status, $httpStatus);
    }

    /**
     * 抛出异常
     *
     * @param \Exception $e 异常对象
     */
    public static function abort($e)
    {
        $message = $e->getMessage();
        $status = $e->getCode() ?? 401;

        if (!env('error_debug', false)) {
            trace('[' . request()->ip() . '] ' . $message, 'error');
            $message = "非法访问";
        }

        if (request()->isAjax() || request()->isPost()) {
            exit(json_encode([
                'status' => $status,
                'message' => $message
            ]));
        }
    }
}
