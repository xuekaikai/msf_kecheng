<?php

namespace app\api\controller;

use app\common\business\Wepay as Buswepay;

use app\common\lib\AjaxResult;


class Weapppay  extends AuthBase
{
    /** 
     * 微信支付
     */
    public function wepay($id,$body,$total_fee,$openid)
    {
        try {
            $data =  Buswepay::minipay($id,$body,$total_fee,$openid);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(), 400);
        }
        return AjaxResult::success('获取成功',$data,200) ;
    }
}



