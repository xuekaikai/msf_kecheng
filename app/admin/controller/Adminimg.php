<?php
namespace app\admin\controller;

use app\common\business\Slideshows as Busslideshow;
use app\common\lib\AjaxResult;

class Adminimg extends AdminBase
{
    /**
     * 创建轮播图
     * @param $data
     * 
     */
    public function createSlideshows($data)
    {
        try{
            Busslideshow::createSlideshow($data);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('保存成功',200);
    }

    /**
     * 轮播图表
     * @return array
     */
    public function ListSlide()
    {
        try{
            $data = Busslideshow::Slideshow();
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }

    /**
     * 删除轮播图
     * @param $id
     * 
     */
    public function DelShow($id)
    {
        try{
            Busslideshow::DelShow($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('删除成功',200);
    }

    /**
     * 创建教学风采
     * @param $data
     * @return void
     */
    public function createNew($id,$data)
    {
        try{
            Busslideshow::createSchoolnew($id,$data);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('保存成功',200);
    }

    /**
     * 教学风采列表
     * @return array
     */
    public function NewList()
    {
        try{
            $data = Busslideshow::newList();
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }

    public function DelNew($id)
    {
        try{
            Busslideshow::Delnew($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::msg('删除成功',200);
    }

    public function adminNew($id)
    {
        try{
            $data = Busslideshow::newDetail($id);
        } catch (\Exception $e) {
            return AjaxResult::error($e->getMessage(),400);
        }
        return AjaxResult::success('获取成功',$data);
    }
}