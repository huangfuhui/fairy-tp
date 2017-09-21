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
     * 调用目标搜索引擎
     * @param $key
     * @return array
     */
    public function getSearch($key)
    {
        $res = curl_get(C('ANT_SEARCH_URL') . urlencode(trim($key)));
        preg_match_all(C('ANT_SEARCH_LIST_PATTERN'), $res, $result);

        // 拼接数据
        $list = array();
        foreach ($result[3] as $k => $v) {
            $list[$k]['author'] = $v;
            $list[$k]['storyName'] = $result[2][$k];
            $list[$k]['storyUrl'] = $result[1][$k];
            $list[$k]['md5'] = md5($list[$k]['storyName'] . '|' . $v);
        }

        return $list;
    }

    /**
     * 初始化小说
     * @param $storyId
     */
    public function initStory($storyId)
    {
        $storyInfo = D('StoryInfo')->getStoryInfo($storyId);

        $data = array();
        $res = curl_get($storyInfo['base_url']);;

        // 解析小说名字
        preg_match(C('ANT_STORY_NAME_PATTERN'), $res, $storyName);
        $data['name'] = $storyName[1];

        // 解析小说作者
        preg_match(C('ANT_STORY_AUTHOR_PATTERN'), $res, $storyAuthor);
        $data['author'] = $storyAuthor[1];

        // 解析小说分类
        preg_match(C('ANT_STORY_TYPE_PATTERN'), $res, $storyType);
        $data['type'] = D('StoryType')->getTag($storyType[1]);

        // 解析小说状态
        preg_match(C('ANT_STORY_status_PATTERN'), $res, $storyStatus);
        substr_count($storyStatus[1], '连载') > 0 ? $data['is_end'] = 0 : $data['is_end'] = 1;

        // 解析小说封面
        preg_match(C('ANT_STORY_COVER_URL_PATTERN'), $res, $coverURL);
        $data['cover_url'] = $coverURL[1];

        // 解析小说简介
        preg_match(C('ANT_STORY_introduction_PATTERN'), $res, $storyIntroduction);
        $data['introduction'] = $storyIntroduction[1];

        // 解析小说目录
        preg_match(C('ANT_STORY_MENU_PATTERN'), $res, $menu);
        $data['menu_url'] = C('ANT_BASE_URL') . $menu[1];

        // 解析小说页码
        $totalPageRes = curl_get($data['menu_url']);
        preg_match(C('ANT_CHAPTER_LIST_PAGE_PATTERN'), $totalPageRes, $pageInfo);
        $data['total_page'] = $pageInfo[1];

        $data['md5'] = md5($data['name'] . '|' . $data['author']);
        $data['init_tag'] = 1;
        $data['update_time'] = time();

        // 小说信息落库
        D('StoryInfo')->updateInfo($data);

        // TODO:异步爬取章节列表
    }

    /**
     * 更新小说页码
     * @param $storyId
     * @param $menuUrl
     * @return int
     */
    public function updatePageInfo($storyId, $menuUrl)
    {
        $totalPageRes = curl_get($menuUrl);
        preg_match(C('ANT_CHAPTER_LIST_PAGE_PATTERN'), $totalPageRes, $pageInfo);

        D('StoryInfo')->updatePageInfo($storyId, $pageInfo[1]);
        return $pageInfo[1];
    }

    /**
     * 同步拉取小说章节列表
     * @param $storyId
     * @param int $requestPage
     * @param int $orderTag
     * @return array
     */
    public function getStoryChaptersSync($storyId, $requestPage = 1, $orderTag = -1)
    {
        if (empty($storyId)) {
            return;
        }

        // 拼接URL
        $url = D('StoryInfo')->getMenuUrl($storyId);
        $url = substr($url, 0, strlen($url) - 1);
        if (-1 == $orderTag) {
            $orderTag = '_1/';
        } else {
            $orderTag = '/';
        }
        $url .= '_' . $requestPage . $orderTag;

        // 获取页面数据
        $res = curl_get($url);

        // 解析章节列表
        preg_match_all(C('ANT_CHAPTER_LIST_PATTERN'), $res, $temp);

        // 拼接章节信息
        $chapters = array();
        foreach ($temp[1] as $k => $v) {
            $chapters[$k]['chapter_url'] = $v;
            $chapters[$k]['chapter_name'] = $temp[2][$k];
            $chapters[$k]['chapter_md5'] = md5($chapters[$k]['chapter_name']);
        }

        return $chapters;
    }

    /**
     * 爬取小说内容
     * @param $url
     * @return array
     */
    public function getStoryContent($url)
    {
        $contentInfo = array();

        // 获取页面数据
        $res = curl_get(C('ANT_BASE_URL') . '/' . $url . '/');
        preg_match(C('ANT_CONTENT_PATTERN'), $res, $content);
        $contentInfo['content'] = $content[1];
        preg_match(C('ANT_CONTENT_TITLE_PATTERN'), $res, $title);
        $contentInfo['chapterName'] = $title[1];
        preg_match(C('ANT_LAST_CHAPTER_PATTERN'), $res, $lastPage);
        $contentInfo['lastChapter'] = $lastPage[1];
        preg_match(C('ANT_NEXT_CHAPTER_PATTERN'), $res, $nextPage);
        $contentInfo['nextChapter'] = $nextPage[1];

        return $contentInfo;
    }

    /**
     * 爬取小说简介，落库保存
     * @param $url
     * @return mixed
     */
    public function getStoryInfo($url)
    {
        $data['base_url'] = C('ANT_BASE_URL') . "/$url/";
        $res = curl_get($data['base_url']);

        // 解析小说名字
        preg_match(C('ANT_STORY_NAME_PATTERN'), $res, $storyName);
        $data['name'] = $storyName[1];

        // 解析小说作者
        preg_match(C('ANT_STORY_AUTHOR_PATTERN'), $res, $storyAuthor);
        $data['author'] = $storyAuthor[1];

        // 解析小说分类
        preg_match(C('ANT_STORY_TYPE_PATTERN'), $res, $storyType);
        $data['type'] = D('StoryType')->getTag($storyType[1]);

        // 解析小说状态
        preg_match(C('ANT_STORY_status_PATTERN'), $res, $storyStatus);
        substr_count($storyStatus[1], '连载') > 0 ? $data['is_end'] = 0 : $data['is_end'] = 1;

        // 解析小说封面
        preg_match(C('ANT_STORY_COVER_URL_PATTERN'), $res, $coverURL);
        $data['cover_url'] = $coverURL[1];

        // 解析小说简介
        preg_match(C('ANT_STORY_introduction_PATTERN'), $res, $storyIntroduction);
        $data['introduction'] = $storyIntroduction[1];

        // 解析小说目录
        preg_match(C('ANT_STORY_MENU_PATTERN'), $res, $menu);
        $data['menu_url'] = C('ANT_BASE_URL') . $menu[1];

        // 解析小说页码
        $totalPageRes = curl_get($data['menu_url']);
        preg_match(C('ANT_CHAPTER_LIST_PAGE_PATTERN'), $totalPageRes, $pageInfo);
        $data['total_page'] = $pageInfo[1];

        $data['md5'] = md5($data['name'] . '|' . $data['author']);
        $data['init_tag'] = 1;
        $data['update_time'] = time();

        // 小说信息落库
        return D('StoryInfo')->saveInfo($data);
    }

    /**
     * 异步爬取更新数据库章节信息
     * @param $storyId
     * @param $url
     * @param $initChapters
     */
    // TODO:待实现
    public function getStoryChaptersAsync($storyId, $url, $initChapters = false)
    {
        if (empty($url) || empty($storyId)) {
            return;
        }

        // 获取页面数据
        $res = curl_get($url);
        $url = substr($url, 0, strlen($url) - 1);

        // 解析章节页码
        preg_match(C('ANT_CHAPTER_LIST_PAGE_PATTERN'), $res, $pageInfo);
        if (empty($pageInfo[1])) {
            return;
        }
        $pageCount = $pageInfo[1];

        $chapters = array();
        $temp = array();
        for ($i = 1; $i <= $pageCount; $i++) {
            do {
                $tempUrl = $url . '_' . $i . '_1/';
                $lists = curl_get($tempUrl);

                unset($chapters);
                unset($temp);

                // 解析章节列表
                $result = preg_match_all(C('ANT_CHAPTER_LIST_PATTERN'), $lists, $temp);
            } while (!$result);

            // 拼接章节信息
            foreach ($temp[1] as $k => $v) {
                $chapters[$k]['story_id'] = $storyId;
                $chapters[$k]['chapter_url'] = C('ANT_BASE_URL') . $v;
                $chapters[$k]['chapter_name'] = $temp[2][$k];
            }

            if ($initChapters) {
                // 初始化章节信息
                D('StoryChapter')->initChapters($chapters);
            } else {
                // 更新章节信息
                D('StoryChapter')->updateChapters($chapters);
            }

        }
    }

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
}