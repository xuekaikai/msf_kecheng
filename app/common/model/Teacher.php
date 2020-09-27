<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Teacher extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    /**
     * 根据条件获取老师信息
     *
     * @param string $field
     * @param string $value
     * @param array $hidden
     * @return object
     */
    public static function getTeacherByWhere($field, $value, $hidden = [])
    {
        return self::where($field, $value)->hidden($hidden)->find();
    }

    public static function getScoreAttr($value,$data)
    {
        $num = number_format(Comments::where('tid',$data['id'])->avg('score')??0,1);
        return $num;
    }

    public static function getTemperatureAttr($value,$data)
    {
        
        return Temperatures::where('tid',$data['id'])->value('temperature')??'';
    }

    /**
     * 添加/编辑 老师信息
     *
     * @param array $data
     * @param int $id
     * @return void
     */
    public static function saveUser($data, $id = '')
    {
        if (!empty($id)) {
            self::find($id);
            return self::update($data, ['id' => $id]);
        }
        $user = self::create($data);
        return $user->id;
    }
}
