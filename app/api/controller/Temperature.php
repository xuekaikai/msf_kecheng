<?php

namespace app\api\controller;

use app\common\business\Teamperature as BusTeamp;
use app\common\lib\AjaxResult;

class Temperature extends AuthBase
{
    
    /**
     * 判断今天有无体温
     * @param  $tid
     * @return void
     */
    public function Judge($tid)
    {
        try{
            $data = BusTeamp::JudgeTeamp($tid);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }

    /**
     * 添加
     * @param  $data
     * @return void
     */
    public function Create($data)
    {
        try{
            $data = BusTeamp::Create($data);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('保存成功',200);
    }

}