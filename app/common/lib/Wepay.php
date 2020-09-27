<?php

namespace app\common\lib;

use Yansongda\Pay\Pay;
use Yansongda\Pay\Log as PayLog;

class Wepay 
{
    static $config = [
        'app_id' => 'wxe67700fb7c1d4da4', // 公众号 APPID
        'miniapp_id' => 'wxe67700fb7c1d4da4', // 小程序 APPID
        'mch_id' => '1602718875',
        'key' => 'Beihaikailongjiaoyu2020091715210',
        'notify_url' => '/notify.php',
        'cert_client' => '', // optional，退款等情况时用到
        'cert_key' => '.', // optional，退款等情况时用到
        'log' => [ // optional
            'file' => '../runtime/pay/wechat',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
        // 'mode' => 'dev', // optional, dev/hk;当为 `hk` 时，为香港 gateway。
    ];

    public static function pay($body,$total_fee,$openid)
    {
        $order = [
            'out_trade_no' => time(),
            'body' => $body,
            'total_fee' => $total_fee,
            'openid' => $openid,
        ];

        $result = Pay::wechat(self::$config)->miniapp($order);
        $res = [
            "appId" => $result->appId,
            "timeStamp" => $result->timeStamp,
            "nonceStr" => $result->nonceStr,
            "package" => $result->package,
            "signType" => $result->signType,
            "paySign" => $result->paySign,
        ];
        return $res;
    }
    
    
    public function notify()
    {
        $pay = Pay::wechat($this->config);

        try {
            $data = $pay->verify(); // 是的，验签就这么简单！

            PayLog::debug('Wechat notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $pay->success()->send(); // laravel 框架中请直接 `return $pay->success()`
    }
}