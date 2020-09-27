<?php

namespace app\common\business;

use  app\common\model\Teacher as TeacherModel;

class Teachers
{
    /**
     * 创建教师
     * @param $data
     * @return void
     */
    public static function createTeacher($data)
    {
        if(empty($data)){
            throw new \Exception('数据为空');
        }
        $teacher = new TeacherModel();
        $name = $teacher->where('phone',$data['phone'])->find();
        if(empty($name)){
            $data['password'] = md5($data['password']);
            return $teacher->save($data);
        }else{
            throw new \Exception('用户已存在');
        }
    }

    /**
     * 教师列表
     * @param $phone
     * @param $page
     * @return array
     */
    public static function Teacherlist($phone,$page,$limit)
    {
        $teacher = new TeacherModel();
        if(empty($phone)){
            $count =  $teacher->count();
            $data =  $teacher->page($page,$limit)->select()->append(['score']);
            if(empty($data)){
                throw new \Exception('数据为空');
            }else{
                $data = $data->toArray();
            }
        }else{
            $count = $teacher->where('phone',$phone)->count();
            $data =  $teacher->where('phone',$phone)->page($page,$limit)->select()->append(['score'])->toArray();
            if(empty($data)){
                throw new \Exception('数据为空');
            }else{
                $data = $data->toArray();
            }
        }
        return [
            'count' =>$count,
            'data' => $data
        ];
    }

    /**
     * 删除用户
     * @param $id
     * @return void
     */
    public static function delTeacher($id)
    {
        if(empty($id)){
            throw new \Exception('数据为空');
        }
        return TeacherModel:: destroy($id);   
    }

    /**
     * 修改信息
     * @param $id
     * @param $data
     * @return void
     */
    public static function updateTeacher($id,$data)
    {
        if(empty($id) && empty($data)){
            throw new \Exception('数据为空');
        }
        if( !empty($data['phone'])){
            $phone =  TeacherModel::where('phone',$data['phone'])->find();
            if( !empty($phone)){
                throw new \Exception('手机号已存在');
            }else{
                return TeacherModel::where('id',$id)->save($data);
            }
        }
        if( !empty($data['password'])){
            $data['password'] = md5($data['password']);
            return TeacherModel::where('id',$id)->save($data);
        }
    }

    public static function TearcherMes($id)
    {
        if(empty($id)){
            throw new \Exception('数据为空');
        }
        $teacher = new TeacherModel();
        $data = $teacher->where('id',$id)->hidden(['password'])->find()->append(['temperature']);
        if(empty($data)){
            throw new \Exception('数据为空');
        }else{
            $data = $data->toArray();
        }
        return  $data;
    }

    public static function editorTeacher($id,$data)
    {
        if(empty($id) && empty($data)){
            throw new \Exception('数据为空');
        }
        return TeacherModel::where('id',$id)->save($data);
    }
}