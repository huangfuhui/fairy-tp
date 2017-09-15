<?php
/**
 * User: huangfuhui
 * Date: 2017/9/15
 * Email: huangfuhui@outlook.com
 */

namespace Home\Controller;

class AntController
{

    public function index()
    {
    }

    public function getStoryInfo()
    {
        $res = curl_get(C('ANT_URL'));
        preg_match_all(C('ANT_PATTERN'), $res, $data);

        dump($data);
    }

    public function getStoryChapters()
    {
        // 获取页面数据
        $res = curl_get(C('ANT_URL'));
        preg_match_all(C('ANT_CHAPTER_PATTERN'), $res, $data);

        // 拼接章节信息
        $chapters = array();
        foreach ($data[1] as $k => $v) {
            $chapters[$k]['story_id'] = 1;
            $chapters[$k]['chapter_url'] = C('ANT_URL') . $v;
            $chapters[$k]['chapter_name'] = $data[2][$k];
        }
        $chapters = array_slice($chapters, 9);

        // 更新数据库章节信息
        D('StoryChapter')->updateChapters($chapters);

        echo '成功更新数据库小说的章节信息...';
    }

    public function getStoryContent($url)
    {
        // 获取页面数据
        $res = curl_get($url);
        preg_match_all(C('ANT_CONTENT_PATTERN'), $res, $data);
        $content = $data[1][0];

        // 保存小说内容
        D('StoryContent')->saveContent($content);

        return $content;
    }
}