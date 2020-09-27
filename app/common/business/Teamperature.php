<?php

namespace app\common\business;

use app\common\model\Temperatures as Modeltempera;
use app\common\model\Teacher as ModelTeacher;

class  Teamperature 
{

    /**
     * 判断体温是否更新
     * @param  $tid
     * 1 未更新 2 已更新
     */
    public static function JudgeTeamp($tid)
    {
        $teacher = new ModelTeacher();
        $tempera = new Modeltempera();
        // $ttime = $teacher->where('id',$tid)->value('login_time');
        $temtime = $tempera->where('tid',$tid)->value('update_time');
        if(date('Y-md',time()) !=  date('Y-md',$temtime)){
            return [
                'code' => 1
            ];
        }else{
            return [
                'code' => 2
            ];
        }
    }

    /**
     * 添加体温
     * @param  $data
     * @return void
     */
    public static function Create($data)
    {
        if(empty($data)){
            throw new \Exception('数据为空');
        }
        $temperature = new Modeltempera();
        if(empty($temperature->where('tid',$data['tid'])->find())){
            $data['update_time'] = time();
            return $temperature->save($data);
        }else{
            $id = $temperature->where('tid',$data['tid'])->value('id');
            $data['update_time'] = time();
            return $temperature->where('id',$id)->save($data);
        }
    }
}