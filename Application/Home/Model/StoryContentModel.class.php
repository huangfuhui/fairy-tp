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
     * @param $chapterMd5
     * @return mixed
     */
    public function getContent($chapterMd5)
    {
        $content = $this->where(array('chapter_md5' => $chapterMd5))->field('content')->find();
        empty($content['content']) ? $content = '' : $content = $content['content'];
        return $content;
    }

    /**
     * 保存小说内容
     * @param string $chapterMd5
     * @param string $chapterName
     * @param mixed|string $content
     * @return mixed
     */
    public function saveContent($chapterMd5, $chapterName, $content)
    {
        $id = $this->add(array('chapter_md5' => $chapterMd5, 'chapter_name' => $chapterName, 'content' => $content));
        return $id;
    }
}