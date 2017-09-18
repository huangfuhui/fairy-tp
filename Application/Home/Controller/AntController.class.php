<?php
/**
 * User: huangfuhui
 * Date: 2017/9/15
 * Email: huangfuhui@outlook.com
 */

namespace Home\Controller;

class AntController
{

    /**
     * 爬取点击榜
     */
    public function getTopClick()
    {
        // 获取点击榜总页数
        preg_match(C('ANT_TOP_CLICK_TOTAL_PAGE_PATTERN'), curl_get(C('ANT_TOP_CLICK_URL') . '1/'), $match);

        // 爬取异常
        if (empty($match)) {
            return;
        }

        // 清除旧的点击榜数据
//        D('StoryTopClick')->cleanTopClick();

        $totalPage = $match[1];
        $data = array();
        $res = array();
        $temp = array();
        for ($i = 191; $i <= 200; $i++) {
            unset($temp);
            preg_match_all(C('ANT_TOP_CLICK_LIST_PATTERN'), curl_get(C('ANT_TOP_CLICK_URL') . $i . '/'), $temp);
            foreach ($temp[3] as $k => $v) {
                $res['author'] = trim($v);
                $res['story_name'] = trim($temp[2][$k]);
                $res['story_url'] = trim(C('ANT_BASE_URL') . $temp[1][$k]);
                array_push($data, $res);
            }
        }

//        D('StoryTopClick')->updateTopClick($data);
    }

    /**
     * 爬取小说简介，落库保存
     */
    public function getStoryInfo()
    {
        $res = curl_get(C('ANT_URL'));
        preg_match_all(C('ANT_PATTERN'), $res, $data);

        dump($data);
    }

    /**
     * 爬取更新数据库章节信息
     */
    public function getStoryChapters()
    {
        // TODO: 待优化

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

    /**
     * 爬取小说内容，落库保存
     * @param $url
     * @return array
     */
    public function getStoryContent($url)
    {
        // 获取页面数据
        $res = curl_get($url);
        preg_match_all(C('ANT_CONTENT_PATTERN'), $res, $data);
        $content = $data[1][0];

        // 保存小说内容
        $content_id = D('StoryContent')->saveContent($content);

        return array('id' => $content_id, 'content' => $content);
    }
}