<?php

namespace app\common\business;

use app\common\lib\Wepay as Libwepay;
use app\common\model\CourseOrder as ModelOrder;

class Wepay 
{
    public static function minipay($id,$body,$total_fee,$openid)
    {
        ModelOrder::where('order_sn',$id)->save(['status'=>1,'pay_time'=>time()]);
        $data = Libwepay::pay($body,$total_fee,$openid);
        if(empty($data)){

            throw new \Exception("数据为空");
        }
        return $data;
    }

}