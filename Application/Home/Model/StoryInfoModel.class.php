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
     * 判断小说是否初始化了，是则返回1，否则返回0
     * @param $storyId
     * @return mixed
     */
    public function isInit($storyId)
    {
        $res = $this->where(array('id' => $storyId))->field('init_tag')->find();
        return $res['init_tag'];
    }

    /**
     * 更新小说简介
     * @param $info
     */
    public function updateInfo($info)
    {
        $this->where(array('md5' => $info['md5']))->save($info);
    }

    /**
     * 新增小说简介
     * @param $info
     * @return mixed
     */
    public function saveInfo($info)
    {
        return $this->add($info);
    }

    /**
     * 获取小说简介
     * @param $storyId
     * @return mixed
     */
    public function getStoryInfo($storyId)
    {
        return $this->where(array('id' => $storyId))->find();
    }

    /**
     * 获取小说总页数
     * @param $storyId
     * @return mixed
     */
    public function getTotalPage($storyId)
    {
        $pageInfo = $this->where(array('id' => $storyId))->field('total_page')->find();
        return $pageInfo['total_page'];
    }

    /**
     * 更新小说总页数
     * @param $storyId
     * @param $totalPage
     */
    public function updatePageInfo($storyId, $totalPage)
    {
        $this->where(array('id' => $storyId))->save(array('total_page' => $totalPage));
    }

    /**
     * 获取小说目录URL
     * @param $storyId
     * @return mixed
     */
    public function getMenuUrl($storyId)
    {
        $menuInfo = $this->where(array('id' => $storyId))->field('menu_url')->find();
        return $menuInfo['menu_url'];
    }

    /**
     * 根据MD5获取小说简介
     * @param $storyMd5
     * @return mixed
     */
    public function getStoryInfoByMd5($storyMd5)
    {
        return $this->where(array('md5' => $storyMd5))->find();
    }

    /**
     * 根据MD5获取小说ID
     * @param $storyMd5
     * @return mixed
     */
    public function getStoryIdByMd5($storyMd5)
    {
        $res = $this->where(array('md5' => $storyMd5))->field('id')->find();
        return $res['id'];
    }
}