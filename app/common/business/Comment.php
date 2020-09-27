<?php

namespace app\common\business;

use app\common\model\Comments as ModelComments;
use app\common\model\Teacher as ModelTeachers;

class Comment
{

    /**
     * 写评论
     * @param $data
     * @return void
     */
    public static function createComments($data)
    {
       if(empty($data)){
        throw new \Exception("数据为空");
       }
        $comment =  new ModelComments();
        return $comment->save($data);
    }
    
    /**
     * 获取教师评价
     * @param $tid
     * @return array
     */
    public static function  findComment($tid)
    {
        if(empty($tid)){
            throw new \Exception("数据为空");
           }

        $comment = new ModelComments();
        $teacher = ModelTeachers::where('id',$tid)->visible(['name','head'])->find();
        $score = number_format($comment->where(['tid'=>$tid,'status'=>2])->avg('score'),1) ;
        $count = $comment->where(['tid'=>$tid,'status'=>2])->count();
        $data = $comment->where(['tid'=>$tid,'status'=>2])->select()->append(['name','head']);
        if(empty($teacher)){

            throw new \Exception("数据为空");
        }else{
            $teacher = $teacher->toArray();
        }
        if(empty($data)){

            throw new \Exception("数据为空");
        }else{
            $data = $data->toArray();
        }
        return [
        'teacher' =>$teacher,
        'scores' =>$score,
        'count' => $count,
        'list' =>$data
    ];
    }
    
    /**
     * 评价列表
     * @return array
     */
    public static function commentList($page,$limit)
    {
        $comment = new ModelComments();
        $count = $comment->where('status',1)->count();
        $data = $comment->where('status',1)->page($page,$limit)->select()->append(['name','teacher_name']);
        if(empty($data)){

            throw new \Exception("数据为空");
        }else{
            $data = $data->toArray();
        }
        
        return [
            'count'=>$count,
            'data' => $data
        ];
    } 

    /**
     * 教师评价
     * @param $id
     * @param $score
     * @return array
     */
    public static function TeacherComm($id,$score,$page,$limit)
    {
        $comment = new ModelComments();
        if(!empty($score)){
            $count = $comment ->where(['tid'=>$id,'status'=>2])->whereBetween('score',$score)->count();
            $data = $comment ->where(['tid'=>$id,'status'=>2])->whereBetween('score',$score)->hidden(['uid','tid','delete_time','status'])->page($page,$limit)->select()->append(['name']);
            if(empty($data)){

                throw new \Exception("数据为空");
            }else{
                $data = $data->toArray();
            }
        }else{
            $count =  $comment->where(['tid'=>$id,'status'=>2])->count();
            $data = $comment->where(['tid'=>$id,'status'=>2])->hidden(['uid','tid','delete_time','status'])->select()->append(['name']);
            if(empty($data)){

                throw new \Exception("数据为空");
            }else{
                $data = $data->toArray();
            }
        }
        if (empty($data)) {
            throw new \Exception("获取数据失败");
        }
        return [
            'count' =>$count,
            'data' =>$data
        ];
    }

    /**
     * 我的评论
     * @param $id
     * @param $page
     * @return array
     */
    public static function MyComment($id,$page)
    {
        $comment = new ModelComments();
        $count =  $comment->where('uid',$id)->count();
        $data = $comment->where('uid',$id)->page($page,10)->hidden(['uid','tid','delete_time'])->select()->append(['teacher_name','teacher_head']);
        if(empty($data)){

            throw new \Exception("数据为空");
        }else{
            $data = $data->toArray();
        }

        return [
            'count' => $count,
            'data' => $data
        ];
    }

    /**
     * 评价详情
     * @param $id
     * @return array
     */
    public static function CommentDetail($id)
    {
        $comment = new ModelComments();
        $data =  $comment->where('id',$id)->hidden(['uid','tid','delete_time'])->select()->append(['teacher_name','teacher_head']);
        if(empty($data)){

            throw new \Exception("数据为空");
        }else{
            $data = $data->toArray();
        }
        return $data;
    }

    public static  function Judge($uid,$tid)
    {
        $comment = new ModelComments();
        $data =  $comment->where(['uid'=>$uid,'tid'=>$tid])->find();
        if(!empty($data)){
            return 1;
        }else{
            return 2;
        }
    }

   /**
    * 评价审核
    * @param  $status
    * @param  $data
    * @return void
    */
    public static function DelComment($status,$data)
    {
        $comment = new ModelComments();
        if(empty($data)){
            throw new \Exception('数据为空');
        }
        if($status ==1){
            return $comment->saveAll($data);
        }else{
            return $comment->destroy($data,true);
        }
    }

}