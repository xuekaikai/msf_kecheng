<?php

namespace app\api\controller;


class Index extends AuthBase
{
    public function echo()
    {
        $a = date("w",time());
        if($a =="0" || $a=="6"){

            echo "是周末";
        }else{
        
            echo "不是周末";
        }
    }
}
