<?php

namespace app\api\controller;

use app\common\BaseController;
use app\common\lib\AjaxResult;
use think\facade\Filesystem;

class Upload extends BaseController
{
    /**
     * 上传单张图片
     *
     * @return json
     */
    public function image()
    {
        try {
            $files = request()->file('file');
            $data['src'] = str_replace("\\","/",'/storage/' . Filesystem::disk('public')->putFile('admin', $files));
            return AjaxResult::success('上传成功', $data);
        } catch (\Exception $e) {
            return AjaxResult::abort($e);
        }
    }

    /**
     * 上传多张图片
     *
     * @return json
     */
    public function images()
    {
        // 获取表单上传文件
        $files = request()->file('image');
        $savename = [];
        foreach ($files as $file) {
            $savename[] = str_replace("\\","/",'/storage/' . Filesystem::putFile('topic', $file));
        }
        return AjaxResult::success('上传成功', ['srcs' => $savename]);
    }

    /**
     * 生成订单号
     *
     * @param string $str
     * @return void
     */
    static public function createOrderSn($str = '')
    {
        //随机时间
        $time = date('YmdHis') . rand(1000000, 9999999);
        $timeLen = strlen($time);
        $sum = 0;
        for ($i = 0; $i < $timeLen; $i++) {
            $sum += intval(substr($time, $i, 1));
        }

        $osn = $str . $time . str_pad((100 - $sum % 100) % 100, 2, '0', STR_PAD_LEFT);
        return $osn;
    }
}
