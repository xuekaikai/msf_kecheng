<?php

namespace app\api\controller;

use app\common\business\Subscribes as BusSubscribes;
use app\common\business\AdminUsers as BusAdminUser;
use app\common\lib\AjaxResult;

class Subscribe extends AuthBase
{
    /**
     * 创建
     * @param  $data
     * @return void
     */
    public function  CreateOrder($data)
    {
        try{
            $data = BusSubscribes::createSubscribes($data);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('保存成功',200);
    }

    /**
     * 预约列表
     * @param $page
     * @param $limit
     * @return array
     */
    public function subscribeList($page,$limit)
    {
        try{
            $data = BusSubscribes::SubscribeList($page,$limit);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }

    /**
     * 用户预约
     * @param  $id 用户id
     * @return array
     */
    public function useOrder($id)
    {
        try{
            $data = BusSubscribes::userorder($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }

    /**
     *  删除用户
     * @param  $id
     * @return void
     */
    public function delOrder($id)
    {
        try{
            BusSubscribes::delOrder($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('删除成功',200);
    }


    public function  orderDetail($id)
    {
        try{
            $data = BusSubscribes::OrderDeatail($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }


    // public function  List($where,$page,$limit)
    // {
    //     try{
    //         $data = BusSubscribes::findList($where,$page,$limit);
    //     } catch (\Exception $e) {
    //         return AjaxResult::error($e->getMessage(),400);
    //     }
    //     return AjaxResult::success('获取成功',$data);
    // }

    public function getExplain()
    {
        try {
            $data = BusAdminUser::getExplain();
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功', $data, 200);
    }
}