<?php
namespace app\admin\controller;

use app\common\business\Notices as BusNotice;
use app\common\lib\AjaxResult;

class AdminNotice extends AdminBase
{
    
    // /**
    //  * 获取公告列表
    //  * @return array
    //  * @Description
    //  */
    // public function NoticeList($page)
    // {
    //     try{
    //         $data = BusNotice::List($page);
    //     } catch (\Exception $th) {
    //         return AjaxResult::abort($th);
    //     }
    //     return AjaxResult::success('获取成功',$data);
    // }

    /**
     * 显示公告内容
     * @param $id
     * @return array
    
     */
    public function Noticecont($id)
    {
        try{
            $data = BusNotice::Noticecont($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }
    
    /**
     * 添加公告
     * @return void
     */
    public function editor($data)
    {
        try{
           BusNotice::editorNotice($data);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('保存成功',200);
    }

    /**
     * 编辑公告
     * @param  $id
     * @param  $data
     * @return void
     */
    public function  updateNotice($id,$data)
    {
        try{
            BusNotice::updateNotice($id,$data);
         } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
         }
         return AjaxResult::msg('保存成功',200);
    }

    public function findNotice($title=0,$type=0,$page)
    {
        try{
            $data = BusNotice::findNotice($title,$type,$page);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }

    /**
     * 删除公告
     * @param $id
     */
    public function delNotice($id)
    {
        try{
            BusNotice::delNotice($id);
         } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
         }
         return AjaxResult::msg('删除成功',200);
    }

}