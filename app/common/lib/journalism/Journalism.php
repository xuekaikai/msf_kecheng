<?php

namespace app\common\lib\journalism;

use PDO;

class Journalism
{

    /**
     * 新闻数据列表
     *
     * @param [type] $page
     * @param [type] $limit
     * @param [type] $type
     * @return array
     */
    public static function getList($page, $limit, $type)
    {
        $con = mysqli_connect("127.0.0.1", "test_news", "KAydJya3fjMAXFM5", "test_news");
        if (mysqli_connect_errno($con)) {
            throw new \Exception("连接 MySQL 失败: " . mysqli_connect_error());
        }
        $where = " 1 ";
        if(intval($type) !== 0){
            $where .= " and typeid=".$type;
        }
        $order = " order by id desc";
        $limit = " limit ".($page-1)*$limit.",".$limit;
        $result = mysqli_query($con,"SELECT * FROM fx_article where ".$where.$order.$limit);
        $list = [];
        while($row=$result->fetch_assoc()){
            $list[] = $row;
        }
        if(empty($list)){
            throw new \think\exception\HttpException(0, '获取失败');
        }
        $count = mysqli_query($con,"select count('id') as count FROM fx_article where ".$where);
        $count = $count->fetch_assoc();
        foreach($list as &$v){
           
            $timediff = time()-$v['create_time'];      //计算时间差
            $days = intval($timediff/86400);        //计算天数
            //计算小时数
            $remain = $timediff%86400;
            $hours = intval($remain/3600);
            //计算分钟数
            $remain = $remain%3600;
            $mins = intval($remain/60);
            if($days > 0){
                $v['time'] = $days.'天前';
            }elseif($hours > 0){
                $v['time'] = $hours.'小时前';
            }elseif($mins > 0){
                $v['time'] = $mins.'分钟前';
            }else{
                $v['time'] = '刚刚';
            }
            $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']) ?? '';
            $v['update_time'] = date('Y-m-d H:i:s',$v['update_time']) ?? '';
        }
        mysqli_close($con);
        return ['list'=>$list,'total'=>$count['count']];
    }


    /**
     * 通过id获取新闻数据
     *
     * @param [type] $id
     * @return array
     */
    public static function getInfo($id)
    {
        $con = mysqli_connect("127.0.0.1", "test_news", "KAydJya3fjMAXFM5", "test_news");
        if (mysqli_connect_errno($con)) {
            throw new \Exception("连接 MySQL 失败: " . mysqli_connect_error());        }
        $where = " id=$id ";

        $result = mysqli_query($con,"SELECT * FROM fx_article where ".$where);
        $list = $result->fetch_assoc();

        if(empty($list)){
            throw new \think\exception\HttpException(0, '获取失败');
        }
        mysqli_close($con);
        return ['list'=>$list];
    }

    /**
     * 获取新闻类型列表
     *
     * @return array
     */
    public static function typeList()
    {
        $con = mysqli_connect("127.0.0.1", "test_news", "KAydJya3fjMAXFM5", "test_news");
        if (mysqli_connect_errno($con)) {
            throw new \Exception("连接 MySQL 失败: " . mysqli_connect_error());
        }
        
        $result = mysqli_query($con,"SELECT * FROM fx_typeid where id in(25,70,119,113,74,83,39,164,89,109)");
        $list = [];
        while($row=$result->fetch_assoc()){
            $list[] = $row;
        }
        if(empty($list)){
            throw new \think\exception\HttpException(0, '获取失败');
        }
        mysqli_close($con);
        return ['list'=>$list];
    }

}
