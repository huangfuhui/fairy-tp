<?php
/**
 * User: huangfuhui
 * Date: 2017/9/15
 * Email: huangfuhui@outlook.com
 */

namespace Home\Model;

use Think\Model;

class StoryContentModel extends Model
{
    /**
     * 获取小说内容
     * @param $contentId
     * @return mixed
     */
    public function getContent($contentId)
    {
        $content = $this->where(array('content_id' => $contentId))->field('content')->find();
        empty($content) ? $content = '' : $content = $content['content'];
        return $content;
    }

    /**
     * 保存小说内容
     * @param mixed|string $content
     * @return mixed
     */
    public function saveContent($content)
    {
        $id = $this->add(array('content' => $content));
        return $id;
    }
}