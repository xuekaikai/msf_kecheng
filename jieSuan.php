<?php

ignore_user_abort();
header("Content-Type: text/html;charset=utf-8");
set_time_limit(0);
$con = mysqli_connect("132.232.90.45", "licai", "mX7zcLmTbmMRj2ZS", "licai");
mysqli_set_charset($con, "utf8");
//获取结算开关
$system = mysqli_query($con, "SELECT `value` from `licai_system` where `key`='accountSwitchValue'");
$row = $system->fetch_assoc();
$isSystem = $row['value'];
if ($isSystem == 1) {
    //获取短信开关
    $noteApi = mysqli_query($con, "SELECT `value` from `licai_system` where `key`='messageSwitchValue'");
    $row = $noteApi->fetch_assoc();
    $isNoteApi = $row['value'];

    while (1) {

        //查询需要返款的数据
        $result = mysqli_query($con, "SELECT * FROM `licai_projects_repayment` where `status`=0 and practical_time=0 and predict_price>0 and practical_time>=" . time() . " order by practical_time");
        $list = array();
        while ($row = $result->fetch_assoc()) {
            $list[] = $row;
        }

        //返款流程
        if (!empty($list)) {

            $insertInfo = "";
            foreach ($list as $v) {

                //查询用户
                $user = mysqli_query($con, "SELECT id,delete_time,money from `licai_user` where id=" . $v['user_id']);
                $user = $user->fetch_assoc();
                if (empty($user) || $user['delete_time'] > 0) {
                    continue;
                }
                $orderSn = 'FX' . str_shuffle(time() . (time() - rand(100, 999))); //订单号
                $insertInfo .= "('" . $orderSn . "'," . $v['user_id'] . ",1,11," . $v['predict_price'] . "," . $user['money'] . ",'" . $v['projects_name'] . "返息" . $v['predict_price'] . "元'," . time() . "," . time() . "),"; //财务记录数据
                mysqli_query($con, "update `licai_user` set money=" . ($user['money'] + $v['predict_price']) . " where id=" . $v['user_id']); //更改用户金额
                mysqli_query($con, "update `licai_projects_repayment` set practical_price=" . $v['predict_price'] . ",practical_time='" . date('Y-m-d H:i:s') . "',status=1 where id=" . $v['id']); //更改返款状态

                //短信通知
                if ($isNoteApi == 1) {
                    //获取商户签名
                    $keyRes = mysqli_query($con, "SELECT `value` from `licai_system` where `key`='ypMessageSignatures'");
                    $row = $keyRes->fetch_assoc();
                    $key = '【' . $row['value'] . '】';

                    //获取短信api签名
                    $apikeyRes = mysqli_query($con, "SELECT `value` from `licai_system` where `key`='ypNotificationSmaAPIKEY'");
                    $row = $apikeyRes->fetch_assoc();
                    $apikey = $row['value'];

                    //获取短信模板
                    $noteSmsRes = mysqli_query($con, "SELECT `desc` from `licai_note_sms` where `type`=3");
                    $row = $noteSmsRes->fetch_assoc();
                    $noteSms = $row['desc'];
                    $text = str_replace('###', $v['predict_price'], $key . $noteSms);

                    $mobile = $v['user_phone'];
                    $mobile = '17610960218';

                    $ch = curl_init();
                    /* 设置验证方式 */
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Accept:text/plain;charset=utf-8',
                        'Content-Type:application/x-www-form-urlencoded', 'charset=utf-8'
                    ));
                    /* 设置返回结果为流 */
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    /* 设置超时时间*/
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

                    /* 设置通信方式 */
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $url = 'https://sms.yunpian.com/v2/sms/single_send.json';
                    $data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
                    curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    $result = curl_exec($ch);
                    mysqli_query($con, "insert into licai_aa(aa) values ('" . $result . "----" . $text . "')");
                }
            }
            //添加财务记录
            if ($insertInfo != "") {
                $insertInfo = "insert into `licai_user_fund_log`(ordersn,user_id,fun,scene,save_money,money,note,create_time,update_time) values " . $insertInfo;
                $insertInfo = substr($insertInfo, 0, -1);
                mysqli_query($con, $insertInfo);
            }

            //判断更改项目状态
            $projectRes = mysqli_query($con, "SELECT id from `licai_projects_cast` where repayment_plan=2");
            $projectArr = array();
            while ($row = $projectRes->fetch_assoc()) {
                $projectArr[] = $row['id'];
            }
            if (!empty($projectArr)) {
                $repaymentStatus0Res = mysqli_query($con, "SELECT distinct(cast_id) from `licai_projects_repayment` where cast_id in(" . implode(',', $projectArr) . ") and status=0");
                $castId = array();
                while ($row = $repaymentStatus0Res->fetch_assoc()) {
                    $castId[] = $row['cast_id'];
                }
                $castId1Res = mysqli_query($con, "SELECT distinct(cast_id) from `licai_projects_repayment` where cast_id not in(" . implode(',', $castId) . ")");
                $castId = array();
                while ($row = $castId1Res->fetch_assoc()) {
                    $castId[] = $row['cast_id'];
                }
                if (!empty($castId)) {
                    mysqli_query($con, "update `licai_projects_cast` set repayment_plan=1 where id in(" . implode(',', $castId) . ")");
                }
            }
        } else {
            echo date('Y-m-d H:i:s');
        }
        echo date('Y-m-d H:i:s');
        mysqli_query($con, "insert into licai_aa(aa) values ('" . date('Y-m-d H:i:s') . "')");
        sleep(3);
    }
}
