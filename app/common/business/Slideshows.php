<?php

namespace app\common\business;

use app\common\model\Slideshow as ModelSlideshow;
use app\common\model\Schoolnew as ModelSchoolnew;

class Slideshows 
{
    /**
     * 保存轮播图
     * @param $data
     * @return void
     */
    public static function createSlideshow($data)
    {
        if(empty($data)){
            throw new \Exception('数据为空');
        }
        $slideshow = new ModelSlideshow();
        return $slideshow->save($data);
    }
    
    /**
     * 获取轮播图列表
     * @return array
     */
    public static function Slideshow()
    {
        $slideshow = new ModelSlideshow();
        $data = $slideshow->select();
        if(empty($data)){
            throw new \Exception('数据为空');
        }else{
            $data = $data->toArray();
        }
        return  $data;
    }

    /**
     * 删除轮播图
     * @param $id
     * @return void
     */
    public static function DelShow($id)
    {
        if(empty($id)){
            throw new \Exception('数据为空');
        }
        $slideshow = new ModelSlideshow();
        return $slideshow->destroy($id,true);
    }

    /**
     * 创建教学风采
     * @param $data
     * @return void
     */
    public static function createSchoolnew($id,$data)
    {   
        if(empty($data)){
            throw new \Exception('数据为空');
        }
        if(!empty($data['img'])){
            $data['img'] = json_encode($data['img']);
        }
        $schoolnew = new ModelSchoolnew();
        if(empty($id)){
            return $schoolnew->save($data);
        }else{
            return $schoolnew->where('id',$id)->save($data);
        }
        
    }

    /**
     * 教学风采列表
     * @return array
     */
    public static function newList()
    {
        $schoolnew = new ModelSchoolnew();
        $data = $schoolnew->select();
        if(empty($data)){
            throw new \Exception('数据为空');
        }else{
            $data = $data->toArray();
        }
        foreach($data as &$v)
        {
            $v['img'] = json_decode($v['img']);
        }
        return $data;
    }

    /**
     * 删除教学风采
     * @param $id
     * 
     */
    public static function Delnew($id)
    {
        if(empty($id)){
            throw new \Exception('数据为空');
        }
        $schoolnew = new ModelSchoolnew();
        return $schoolnew->destroy($id,true);
    }

    /**
     * 前台获取列表
     * @return array
     */
    public static function List()
    {
        $schoolnew = new ModelSchoolnew();
        $slideshow = new ModelSlideshow();
        $newlist = $schoolnew->visible(['id','title','img'])->select();
        $slidelist = $slideshow ->select();
        if(empty($newlist)){
            throw new \Exception('数据为空');
        }else{
            $newlist = $newlist->toArray();
        }
        if(empty($slidelist)){
            throw new \Exception('数据为空');
        }else{
            $slidelist = $slidelist->toArray();
        }  
        foreach($newlist as &$v )
        {
            $v['img'] = json_decode($v['img']);
        }
        return [
            'top' =>$slidelist,
            'mid' =>$newlist
        ];
    }

    /**
     * 教学风采详情
     * @param $id
     * @return array
     */
    public static function newDetail($id)
    {
        $schoolnew = new ModelSchoolnew();
        $data =  $schoolnew->where('id',$id)->find();
        if (empty($data)) {
            throw new \Exception('数据为空');
        }else {
            $data = $data->toArray();
        }
        if(!empty($data['img'])){
            $data['img'] = json_decode($data['img']);
        }
        return $data;
    }

    /**
     * 轮播图详情
     * @param $id
     * @return array
     */
    public static function ImgDetail($id)
    {
        $slideshow = new ModelSlideshow();
        $data = $slideshow ->where('id',$id)->select()->toArray();
        return $data;

    }
}


