<?php
/**
 * User: huangfuhui
 * Date: 2017/9/15
 * Email: huangfuhui@outlook.com
 */

namespace Home\Model;

use Think\Model;

class StoryChapterModel extends Model
{
    /**
     * 查询小说章节列表
     * @param $storyId
     * @param int $start
     * @param int $offset
     * @param string $order
     * @return mixed
     */
    public function getChapterList($storyId, $start = 0, $offset = 20, $order = 'id desc')
    {
        return $this->where(array('story_id' => $storyId))->limit($start, $offset)->order($order)->select();
    }

    /**
     * 获取小说总章数
     * @param $storyId
     * @return mixed
     */
    public function getChapterCount($storyId)
    {
        return $this->where(array('story_id' => $storyId))->count();
    }

    /**
     * 更新小说章节信息
     * @param $chapters
     */
    public function updateChapters($chapters)
    {
        $latestChapter = $this->getLatestChapter();

        // 去除已经落库的章节信息
        $search = array(
            'story_id' => $latestChapter['story_id'],
            'chapter_name' => $latestChapter['chapter_name'],
            'chapter_url' => $latestChapter['chapter_url']
        );
        $index = array_search($search, $chapters);
        $newChapters = array_slice($chapters, $index + 1);

        // 新增小说新的章节信息
        $this->addAll($newChapters);
    }

    /**
     * 获取最新的章节
     * @param $latestChapters
     * @return mixed
     */
    public function getLatestChapter($latestChapters = 1)
    {
        $newChapterInfo = $this->where(array('story_id' => 1))
            ->order('id desc')
            ->limit($latestChapters)
            ->select();
        return $newChapterInfo[0];
    }

    /**
     * 获取某一章节的详细信息
     * @param $chapterId
     * @return mixed|null
     */
    public function getChapterDetail($chapterId)
    {
        if (empty($chapterId)) {
            return null;
        }

        return $this->where(array('id' => $chapterId))->find();
    }

    /**
     * 获取上一章节ID
     * @param $chapterId
     * @return int
     */
    public function getLastChapter($chapterId)
    {
        $storyId = $this->getStoryByChapterId($chapterId);
        $res = $this->where(array('story_id' => $storyId, 'id' => array('lt', $chapterId)))
            ->field('id')
            ->order('id desc')
            ->limit(1)
            ->find();

        if (empty($res)) {
            return 0;
        } else {
            return $res['id'];
        }
    }

    /**
     * 获取下一章节ID
     * @param $chapterId
     * @return int
     */
    public function getNextChapter($chapterId)
    {
        $storyId = $this->getStoryByChapterId($chapterId);
        $res = $this->where(array('story_id' => $storyId, 'id' => array('gt', $chapterId)))
            ->field('id')
            ->order('id asc')
            ->limit(1)
            ->find();

        if (empty($res)) {
            return 0;
        } else {
            return $res['id'];
        }
    }

    /**
     * 根据章节ID获取小说ID
     * @param $chapterId
     * @return mixed
     */
    private function getStoryByChapterId($chapterId)
    {
        $res = $this->where(array('id' => $chapterId))->field('story_id')->find();
        return $res['story_id'];
    }
}