<?php

namespace app\common\business;


use app\common\model\Notice as NoticeModel;

class Notices
{
    
    /**
     * 首页获取公告
     * @return array
     * 
     */
    public static function getMesList(){
        $notice = new NoticeModel();
        $data = $notice->where('choose',1)->order('create_time','desc')->hidden(['content','image'])->select();
        if(empty($data)){
            throw new \Exception('数据为空');
        }else{
            $data = $data->toArray();
        }
        return $data;
    }

    /**
     * 公告详情
     * @param [type] $id
     * @return array
     */
    public static function NoticeDetail($id)
    {
        $notice = new NoticeModel();
        $data = $notice ->where('id',$id)->find();
        if(empty($data)){
            throw new \Exception('数据为空');
        }else{
            $data = $data->toArray();
        }
        return $data;
    }

    /**
     * 显示公告内容
     * @param  $id
     * @return array
     */
    public static function Noticecont($id)
    {
        $notice = new NoticeModel();
        $data = $notice ->where('id',$id)->find();
        if(empty($data)){
            throw new \Exception('数据为空');
        }else{
            $data = $data->toArray();
        }
        return $data;
    }

    /**
     * 添加公告
     * @param  $data
     * 
     */
    public static function editorNotice($data)
    {
        if(empty($data)){
            throw new \Exception('数据为空');
        }
        $notice = new NoticeModel();
        return $notice->save($data);
    }

    /**
     * 获取公告分类
     * @return array
     */
    public static function NoticeType($type,$page)
    {
        $notice = new NoticeModel();
        $Adata = $notice->where(
            ['type' =>$type,
              'status' => 2
            ]
            )->order('create_time','desc')->hidden(['content','image'])->select();
        if(empty($Adata)){
            throw new \Exception('数据为空');
        }else{
            $Adata = $Adata->toArray();
        }
        $data = $notice->where(
            ['type' =>$type,
              'status' => 1
            ]
            )->order('create_time','desc')->hidden(['content','image'])->page($page,10)->select();  
        
            if(empty($data)){
                throw new \Exception('数据为空');
            }else{
                $data = $data->toArray();
            }
        return [
            'top' =>$Adata,
            'ord' =>$data
        ];
    }

    // /**
    //  * 公告列表
    //  * @param  $page
    //  * @return array
    //  */
    // public static function List($page)
    // {
    //     $notice = new NoticeModel();
    //     $count = $notice->count();
    //     $data =  $notice->hidden(['content','image','title_img'])->page($page,8) ->select()->toArray();
    //     return [
    //         'count' =>$count,
    //         'data' => $data
    //     ];
    // }

   /**
    * 编辑公告
    * @param  $id
    * @param  $data
    * @return void
    */
    public static function updateNotice($id,$data)
    {
        $notice = new NoticeModel();
        return $notice->where('id',$id)->save($data);
    }

    /**
     * 查找公告
     * @param $title
     * @param  $type
     * @return array
     */
    public static function findNotice($title,$type,$page)
    {
        $notice = new NoticeModel();
        if($title != 0&& $type == 0)
        {
            $count = $notice->where('title','like' ,'%'.$title.'%')->count();
            $data = $notice->where('title','like' ,'%'.$title.'%')->page($page,8)->select();
            if(empty($data)){
                throw new \Exception('数据为空');
            }else{
                $data = $data->toArray();
            }
        }elseif($title == 0&& $type != 0){
            $count = $notice->where('type',$type)->count();
            $data = $notice->where('type',$type)->page($page,8)->select();
            if(empty($data)){
                throw new \Exception('数据为空');
            }else{
                $data = $data->toArray();
            }
        }elseif($title != 0&& $type != 0){
            $count = $notice->where('title','like' ,'%'.$title.'%')->where('type',$type)->count();
            $data = $notice->where('title','like' ,'%'.$title.'%')->where('type',$type)->page($page,8)->select();
            if(empty($data)){
                throw new \Exception('数据为空');
            }else{
                $data = $data->toArray();
            }
        }
        else{
            $count = $notice->count();
            $data = $notice->hidden(['content','image','title_img'])->page($page,8)->select();
            if(empty($data)){
                throw new \Exception('数据为空');
            }else{
                $data = $data->toArray();
            }
        }
        return [
            'count'=>$count,
            'data' => $data
        ];
    }

    /**
     *  删除公告
     * @param  $id
     * @return void
     */
    public static function delNotice($id)
    {
        if(empty($id)){
            throw new \Exception('数据为空');
        }
        return NoticeModel:: destroy($id);   
    }
}