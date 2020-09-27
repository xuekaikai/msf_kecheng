<?php

namespace app\admin\controller;

use app\common\business\Index as BusIndex;
use app\common\lib\AjaxResult;

class Adminindex  extends AdminBase
{
    /**
     * 首页
     * 
     */
    public function index()
    {
        try {
            $data = BusIndex::index();
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }

    

}