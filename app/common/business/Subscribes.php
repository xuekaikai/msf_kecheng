<?php

namespace app\common\business;

use app\common\model\Subscribe as ModelSubscribe;

class Subscribes 
{
    /**
     * 创建预约
     * @param  $data
     * @return void

     */
    public static function createSubscribes($data)
    {
        if(empty($data)){
            throw new \Exception('数据为空');
        }
        $order = new ModelSubscribe();
        $data['order'] = date('Ymds').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $data['order'];
        return $order->save($data);
    }

    public static function SubscribeList($page,$limit)
    {
        // $order = new ModelSubscribe();
        // $data = $order->list($page,$limit)->toArray();
        // return $data;
        $data = ModelSubscribe::with(['getCourseName' ])->page($page,$limit)->select()->toArray();
        $count = ModelSubscribe::count();
        return [
            'count' => $count,
            'data' =>$data
        ];
    }

    public static function userorder($id)
    {
        $data = ModelSubscribe::withJoin(['getCourseName' ])->where('uid',$id)->select()->toArray();
        return $data;
    }

    public static function delOrder($id)
    {
        return  ModelSubscribe::destroy($id,true);
    }

    public static function OrderDeatail($id)
    {
        $data = ModelSubscribe::withJoin(['getCourseName' ])->select([$id])->toArray();
        return $data;
    }

    // public static function findList($where,$page,$limit)
    // {
        // $data = ModelSubscribe::hasWhere('getCourseName', function($query)use($where) {
        //     $query->where('name', 'like', '%'.$where.'%');
        // })->page($page,$limit)->append(['getCourseName.name'])->select()->toArray();
        // return $data;
    // }
}