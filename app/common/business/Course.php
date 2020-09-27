<?php

namespace app\common\business;

use app\common\lib\AjaxResult;
use app\common\model\Course as ModelCourse;
use app\common\model\CourseClass;
use app\common\model\CourseClassType;
use EmptyIterator;
use Symfony\Component\VarDumper\Command\Descriptor\DumpDescriptorInterface;
use Symfony\Component\VarDumper\Server\DumpServer;

class Course
{
    /**
     * 课程分类列表
     *
     * @return array
     */
    public static function listCourseClassAndType()
    {
        $list = CourseClass::getWhereClassIdList();
        if (empty($list)) {
            throw new \Exception("没有数据", 400);
        }
        return $list->toArray();
    }

    /**
     * 课程一级分类列表
     *
     * @return array
     */
    public static function listCourseClass()
    {
        $type = CourseClass::select();
        if (empty($type)) {
            throw new \Exception("没有数据");
        }
        return $type->toArray();
    }

    /**
     * 课程二级分类列表
     *
     * @param int $class_id
     * @return array
     */
    public static function listCourseClassType($class_id)
    {
        $type = CourseClassType::where('class_id', $class_id)->select();
        if (empty($type)) {
            throw new \Exception("没有数据", 400);
        }
        return $type->toArray();
    }

    /**
     * 删除分类
     *
     * @param int $id
     * @return void
     */
    public static function delCourseClassOrType($id, $type = '')
    {
        if ($type == 1) {
            // 删除一级分类
            $del = CourseClass::destroy($id);
            if (empty($del)) {
                throw new \Exception("一级目录删除失败");
            }
            $emptyType = CourseClassType::getClassTypeList($id)->toArray();
            if (!empty($emptyType)) {
                $typeDel = CourseClassType::where('class_id', $id)->delete();
                if (empty($typeDel)) {
                    throw new \Exception("二级目录删除失败");
                }
            }
        } else {
            // 删除二级分类
            $del = CourseClassType::destroy($id);
            if (empty($del)) {
                throw new \Exception("删除二级目录失败");
            }
        }
        return true;
    }

    /**
     * 添加分类
     *
     * @param string $label
     * @return void
     */
    public static function addCourseClassOrType($label, $class_id = '', $type = '')
    {
        // 添加一级分类
        if ($type == 1) {
            $CourseClass = new CourseClass();
            $CourseClass->label = $label;
            $CourseClass->save();
        } else {
            // 添加二级分类
            $CourseClass  = new CourseClassType();
            $CourseClass->label = $label;
            $CourseClass->class_id = $class_id;
            $CourseClass->save();
        }

        if (empty($CourseClass)) {
            throw new \Exception("添加失败");
        }
        return $CourseClass;
    }

    /**
     * 添加课程
     *
     * @param array $data
     * @return void
     */
    public static function addOrUpdateCourse(array $data, int $id = null)
    {
        $data['class_id'] = intval($data['class_id']);
        $data['type_id'] = intval($data['type_id']);
        $data['type'] = intval($data['type']);
        $data['price'] = floatval($data['price']);
        $addOrUpdateCourse = ModelCourse::addOrUpdateCourse($data, $id);
        if ($addOrUpdateCourse != true) {
            throw new \Exception("操作失败");
        }
        return $addOrUpdateCourse;
    }

    /**
     * 课程列表
     *
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function listCourse($where = [], $page, $limit)
    {
        $whereSql = [];
        // 课程名称
        if (!empty($where['name'])) {
            $whereSql[] = ['name', 'like', '%' . $where['name'] . '%'];
        }
        if (!empty($where['id'])) {
            $whereSql[] = ['id', '=', $where['id']];
        }
        // 课程形式
        if (!empty($where['type'])) {
            $whereSql[] = ['type', '=', $where['type']];
        }
        // 课程一级分类
        if (!empty($where['class_id'])) {
            $whereSql[] = ['class_id', '=', $where['class_id']];
        }
        // 课程二级分类
        if (!empty($where['type_id'])) {
            $whereSql[] = ['type_id', '=', $where['type_id']];
        }
        $CourseLise = ModelCourse::listCourse($whereSql, $page, $limit)->toArray();
        if (empty($CourseLise)) {
            throw new \Exception("获取失败");
            
        }
        $count = ModelCourse::where($whereSql)->count();
        return ['list' => $CourseLise, 'count' => $count];
    }

    /**
     * 删除课程
     *
     * @param [type] $id
     * @return void
     */
    public static function delCourse($id)
    {
        $del = ModelCourse::destroy($id);
        if ($del != true) {
            throw new \Exception("删除失败");
        }
        return $del;
    }
}
