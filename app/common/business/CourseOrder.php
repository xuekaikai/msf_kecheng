<?php

namespace app\common\business;

use app\api\controller\Upload;
use app\common\model\CourseOrder as ModelCourseOrder;

class CourseOrder
{
    /**
     * 创建订单
     *
     * @param array $data
     * @return array
     */
    public static function createOrder($data)
    {
        $data['order_sn'] = Upload::createOrderSn('KC');
        $data['pay_price'] = floatval($data['pay_price']);
        $create = new ModelCourseOrder();
        $create->save($data);
        if (empty($create)) {
            throw new \Exception("创建订单失败");
        }
        return [ 'orderid'=>$data['order_sn']] ;
    }

    /**
     * 订单列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function listCourseOrder($where, $page, $limit)
    {
        $whereSql = [];
        // 用户ID
        if (!empty($where['user_id'])) {
            $whereSql[] = ['user_id', '=', $where['user_id']];
        }
        // 是否支付
        if (!empty($where['status'])) {
            $whereSql[] = ['status', '=', $where['status']];
        }
        // 课程ID
        if (!empty($where['course_id'])) {
            $whereSql[] = ['course_id', '=', $where['course_id']];
        }
        // 课程ID
        if (!empty($where['id'])) {
            $whereSql[] = ['id', '=', $where['id']];
        }
        $data = ModelCourseOrder::listCourseOrder($whereSql, $page, $limit);
        if (empty($data)) {
            throw new \Exception("获取订单失败");
        }
        $count = ModelCourseOrder::where($whereSql)->count();
        return  ['data' => $data, 'count' => $count];
    }

    /**
     * 删除订单
     *
     * @param int $id
     * @return void
     */
    public static function delCourse($id)
    {
        $del = ModelCourseOrder::destroy($id);
        if(empty($del)){
            throw new \Exception("订单删除失败");
        }
        return $del;
    }
}
