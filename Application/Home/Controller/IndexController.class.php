<?php
/**
 * User: huangfuhui
 * Date: 2017/9/15
 * Email: huangfuhui@outlook.com
 */

namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    /**
     * 首页
     */
    public function index()
    {
        // 获取点击榜前20
        $topClicks = D('StoryTopClick')->getTopClick(0, 20);

        $this->assign('topClicks', $topClicks);
        $this->display();
    }

    /**
     * 搜索引擎
     */
    public function search()
    {
        $key = I('get.key');

        // 调用Ant
        $ant = A('Ant');
        $result = $ant->getSearch($key);

        $this->assign('result', $result);
        $this->display();
    }

    /**
     * 展示小说章节目录
     */
    public function storyChapters()
    {
        $storyMd5 = I('id');
        if (empty($storyMd5)) {
            redirect(U('Home/Index/index'));
        }
        $requestPage = empty(I('page')) ? 1 : I('page');
        (!empty(I('order')) && I('order') == 1) ? $orderTag = 1 : $orderTag = -1;

        $storyId = D('StoryInfo')->getStoryIdByMd5($storyMd5);

        // 小说未收录
        if (empty($storyId)) {
            // TODO: 策略爬取收录
            redirect(U('Home/Index/index'));
        }


        $isInit = D('StoryInfo')->isInit($storyId);
        // 初始化小说
        if (!$isInit) {
            A('Ant')->initStory($storyId);
        }

        $storyInfo = D('StoryInfo')->getStoryInfoByMd5($storyMd5);
        $storyInfo['id'] = $storyMd5;
        $storyInfo['type'] = D('StoryType')->getTagName($storyInfo['type']);
        $storyInfo['is_end'] = getStatus($storyInfo['is_end']);

        // 爬取章节列表
        $chapterList = A('Ant')->getStoryChaptersSync($storyId, $requestPage, $orderTag);

        // 更新章节页码
        $storyInfo['total_page'] = A('Ant')->updatePageInfo($storyId, $storyInfo['menu_url']);

        $this->assign('introduction', $storyInfo);
        $this->assign('chapterList', $chapterList);
        $this->assign('totalPage', $storyInfo['total_page']);
        $this->assign('currentPage', $requestPage);
        $this->assign('orderTag', $orderTag);
        $this->display();
    }

    /**
     * 展示小说内容
     */
    public function storyContent()
    {
        $storyId = I('storyId');
        $chapterUrl = I('link');

        if (empty($chapterUrl) || 1 == substr_count($chapterUrl, '1')) {
            redirect(U('Home/Index/storyChapters', array('id' => $storyId)));
        }

        $contentInfo = A('Ant')->getStoryContent($chapterUrl);

        // 小说简介
        $storyIntroduction = D('storyInfo')->getStoryInfoByMd5($storyId);

        $this->assign('introduction', $storyIntroduction);
        $this->assign('name', $contentInfo['chapterName']);
        $this->assign('content', $contentInfo['content']);
        $this->assign('lastChapter', $contentInfo['lastChapter']);
        $this->assign('nextChapter', $contentInfo['nextChapter']);
        $this->display();
    }

}