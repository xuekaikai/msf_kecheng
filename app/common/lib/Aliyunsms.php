<?php

namespace app\common\lib;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Aliyunsms 
{
    public static function send($phone, $smscode,$code)
    {
        AlibabaCloud::accessKeyClient('LTAI4GCTgNmdvF7nWGKh4LMV', 'w7mCkjVU3uu7v6JTHG5FGy79c5Fr59')
                        ->regionId('cn-hangzhou')
                        ->asDefaultClient();

        try {
             AlibabaCloud::rpc()
                    ->product('Dysmsapi')
                    // ->scheme('https') // https | http
                    ->version('2017-05-25')
                    ->action('SendSms')
                    ->method('POST')
                    ->host('dysmsapi.aliyuncs.com')
                    ->options([
                                'query' => [
                                    'RegionId' => "cn-hangzhou",
                                    'PhoneNumbers' => $phone,
                                    'SignName' => "凯泷培训",
                                    'TemplateCode' => $smscode,
                                    'TemplateParam' => "{'code':$code}",
                                ],
                            ])
                    ->request();
            // print_r($result->toArray());
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }
}