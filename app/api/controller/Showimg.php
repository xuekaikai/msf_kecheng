<?php

namespace app\api\controller;

use app\common\business\Slideshows as BusSlideshows;
use app\common\lib\AjaxResult;

class Showimg  extends AuthBase
{
    /**
     * 获取列表
     * @return array
     */
    public function ShowList()
    {
        try{
            $data = BusSlideshows::List();
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }
    
    /**
     * 获取教学风采详情
     * @param $id
     * @return array
     */
    public function new($id)
    {
        try{
            $data = BusSlideshows::newDetail($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }

    /**
     * 轮播图详情
     * @param  $id
     * @return array
     */
    public function imgdetail($id)
    {
        try{
            $data = BusSlideshows::ImgDetail($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    } 
}