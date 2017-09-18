<?php
/**
 * User: huangfuhui
 * Date: 2017/9/15
 * Email: huangfuhui@outlook.com
 */

/**
 * 使用get方法请求目标URL，获取应答内容
 * @param $url
 * @param $timeOut
 * @return mixed
 */
function curl_get($url, $timeOut = 0)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, trim($url));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    empty($timeOut) ? null : curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);

    $res = curl_exec($ch);
    curl_close($ch);

    // 编码装换
    if (C('ANT_EXPECT_ENCODING') != C('ANT_TARGET_ENCODING')) {
        $res = mb_convert_encoding($res, C('ANT_EXPECT_ENCODING'), C('ANT_TARGET_ENCODING'));
    }

    return $res;
}

/**
 * 多线程抓取，get方法请求
 * @param $chs
 * @return mixed
 */
function curl_multi_get($chs)
{
    $mh = curl_multi_init();

    foreach ($chs as $ch) {
        curl_multi_add_handle($mh, $ch);
    }

    // 执行批处理句柄
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);

    // 关闭全部句柄
    $res = array();
    foreach ($chs as $ch) {
        $temp = curl_multi_getcontent($ch);
        // 编码装换
        if (C('ANT_EXPECT_ENCODING') != C('ANT_TARGET_ENCODING')) {
            $temp = mb_convert_encoding($temp, C('ANT_EXPECT_ENCODING'), C('ANT_TARGET_ENCODING'));
        }
        array_push($res, $temp);

        curl_multi_remove_handle($mh, $ch);
    }
    curl_multi_close($mh);

    return $res;
}