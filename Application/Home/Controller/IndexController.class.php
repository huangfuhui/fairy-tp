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
    public function index()
    {
        $this->display();
    }

    /**
     * 展示小说章节目录
     */
    public function storyChapters()
    {
        $storyId = empty(I('storyId')) ? 1 : I('storyId');
        $requestPage = empty(I('page')) ? 1 : I('page');
        $order = (!empty(I('order')) && I('order') == 1) ? $order = 'id asc' : $order = 'id desc';
        $orderTag = (!empty(I('order')) && I('order') == 1) ? $orderInfo = 1 : $orderInfo = -1;

        $chapterCount = D('StoryChapter')->getChapterCount($storyId);
        $totalPage = ceil($chapterCount / 20);
        // 处理分页请求的合法性
        if ($requestPage < 1) {
            $requestPage = 1;
        } elseif ($requestPage > $totalPage) {
            $requestPage = $totalPage;
        }

        // 获取小说简介
        $storyIntroduction = D('StoryInfo')->getStoryInfo($storyId);

        // 获取小说章节列表
        $start = ($requestPage - 1) * 20;
        $chapterList = D('StoryChapter')->getChapterList($storyId, $start, 20, $order);

        $this->assign('introduction', $storyIntroduction);
        $this->assign('chapterList', $chapterList);
        $this->assign('totalPage', $totalPage);
        $this->assign('currentPage', $requestPage);
        $this->assign('orderTag', $orderTag);
        $this->display();
    }

    /**
     * 展示小说内容
     */
    public function storyContent()
    {
        $chapterId = I('chapterId');
        $chapterDetail = D('StoryChapter')->getChapterDetail($chapterId);

        // 章节信息获取错误
        if (empty($chapterDetail)) {
            redirect(U('Index/storyChapters'));
        }

        // 获取小说简介
        $storyIntroduction = D('StoryInfo')->getStoryInfo($chapterDetail['story_id']);

        $contentId = $chapterDetail['content_id'];

        // 无法获取章节对应的小说内容，则策略拉取并本地落库保存
        if (null == $contentId) {
            $ant = A('Ant');
            $content = $ant->getStoryContent($chapterDetail['chapter_url']);
        } else {
            $content = D('StoryContent')->getContent($contentId);
        }

        // 获取上一章节和下一章节ID
        $lastChapter = D('StoryChapter')->getLastChapter($chapterId);
        $nextChapter = D('StoryChapter')->getNextChapter($chapterId);

        $this->assign('introduction', $storyIntroduction);
        $this->assign('chapterDetail', $chapterDetail);
        $this->assign('content', $content);
        $this->assign('lastChapter', $lastChapter);
        $this->assign('nextChapter', $nextChapter);
        $this->display();
    }

}