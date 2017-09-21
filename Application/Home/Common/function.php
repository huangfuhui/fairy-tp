<?php
/**
 * User: huangfuhui
 * Date: 2017/9/21
 * Email: huangfuhui@outlook.com
 */

/**
 * 渲染小说状态
 * @param $status
 * @return string
 */
function getStatus($status)
{
    $status == 0 ? $res = '连载中' : $res = '已完结';
    return $res;
}