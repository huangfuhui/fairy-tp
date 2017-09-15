<?php
/**
 * User: huangfuhui
 * Date: 2017/9/15
 * Email: huangfuhui@outlook.com
 */

namespace Home\Model;

use Think\Model;

class StoryInfoModel extends Model
{
    /**
     * 获取小说简介
     * @param $storyId
     * @return mixed
     */
    public function getStoryInfo($storyId)
    {
        return $this->where(array('id' => $storyId))->find();
    }
}