<?php

namespace app\api\controller;

use app\common\lib\AjaxResult;
use app\common\business\Notices as BusNotice;

class Notice extends AuthBase
{
    /**
     * 首页获取公告
     * @return array
     */
    public function getList()
    {
        try{
            $data = BusNotice::getMesList();
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);;
        }
        return AjaxResult::success('获取成功',$data);
    }

     /**
     * 公告详情
     * @return array
     */
    public function NoticeDetail($id)
    {
        try{
            $data = BusNotice::NoticeDetail($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }

    /**
     * 全部公告
     * @param  $type
     * @return array
     */
    public function NoticeType($type,$page)
    {
        try{
            $data = BusNotice::NoticeType($type,$page);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    } 
}