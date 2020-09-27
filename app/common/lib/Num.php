<?php

declare(strict_types=1);

namespace app\common\lib;

class Num
{
    /**
     * 获取随机数
     *
     * @param integer $length 长度 [1~32]
     * @return integer 随机数
     */
    public static function random(int $length): int
    {
        return rand(pow(10, $length - 1), intval(substr('99999999999999999999999999999999', 0, $length)));
    }
}
