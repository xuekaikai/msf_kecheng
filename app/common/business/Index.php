<?php

namespace app\common\business;

use app\common\model\Users as ModelUser;
use app\common\model\Course as ModelCourse;
use app\common\model\Teacher as ModelTeacher;
use app\common\model\Subscribe as ModelSubscribe;
use app\common\model\CourseOrder as ModelOrder;

class Index 
{
    public static function index()
    {
        $student = ModelUser::count();
        $course = ModelCourse::count();
        $teacher = ModelTeacher::count();
        $order =  ModelOrder::where('status',1)->sum('pay_price');

        $yestime = strtotime(date('Y-m-d',time()))-86400;
        $nowtime = strtotime(date('Y-m-d',time()));
        $subcount = ModelSubscribe::whereBetween('create_time',[$yestime,$nowtime])->count();
        $subdata = ModelSubscribe::whereBetween('create_time',[$yestime,$nowtime])->select();
        $ordercount = ModelOrder::where([['create_time','between',[$yestime,$nowtime]],['status', '=', 1]])->count();
        $orderdata = ModelOrder:: listOrder([['create_time','between',[$yestime,$nowtime]],['status', '=', 1]]);
        if(empty($subdata)){
            $sdata = [];
        }else{
            $sdata = $subdata -> toArray();
        }
        if(empty($orderdata)){
            $orddata = [];
        }else{
            $orddata = $orderdata -> toArray();
        }
        return [
            'student' => $student,
            'course'  => $course,
            'teacher' => $teacher,
            'order'   => $order,
            'subdata' => [
                'count' => $subcount,
                'data'  => $sdata
            ],
            'orddata' => [
                'count' => $ordercount,
                'data'  => $orddata
            ]
        ];
    }
}