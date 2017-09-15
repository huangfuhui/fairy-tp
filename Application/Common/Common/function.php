<?php
/**
 * User: huangfuhui
 * Date: 2017/9/15
 * Email: huangfuhui@outlook.com
 */

/**
 * 使用get方法请求目标URL，获取应答内容
 * @param $url
 * @return mixed
 */
function curl_get($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, trim($url));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}