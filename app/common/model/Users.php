<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;


class Users extends Model
{
    /**
     * 根据字段查询ID
     *
     * @param string $field 字段名
     * @param string $value 字段值
     * @return array
     */
    public static function getUserIdByWhere($field, $value)
    {
        return self::where($field, $value)->value('id');
    }

    /**
     * 传入筛选字段，获取用户数据
     *
     * @param string $field
     * @param string $value
     * @param array $hidden
     * @return object
     */
    public static function getUserByWhere($field, $value, $hidden = [])
    {
        return self::where($field, $value)->hidden($hidden)->find();
    }

    /**
     * 传入筛选字段，获取用户数据
     *
     * @param string $field
     * @param string $value
     * @param array $hidden
     * @return object
     */
    public static function getUserByWhereSelect($field, $page, $limit, $hidden = [])
    {
        return self::where($field)->page($page, $limit)->hidden($hidden)->select();
    }
    public static function getUserByWhereCount($field)
    {
        return self::where($field)->count();
    }

    /**
     * 添加/编辑 用户信息
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
        } else {
            $user = new Users();
            $user->save($data);
            return $user;
        }
    }
}
