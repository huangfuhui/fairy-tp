<?php
/**
 * User: huangfuhui
 * Date: 2017/9/19
 * Email: huangfuhui@outlook.com
 */

namespace Home\Model;

use Think\Model;

class StoryTypeModel extends Model
{

    /**
     * 获取小说类型tag值
     * @param $type
     * @return mixed
     */
    public function getTag($type)
    {
        $res = $this->where(array('type' => $type))->find();
        return $res['tag'];
    }

    /**
     * 获取小说类型名称
     * @param $tag
     * @return mixed
     */
    public function getTagName($tag)
    {
        $res = $this->where(array('tag' => $tag))->field('type')->find();
        return $res['type'];
    }
}