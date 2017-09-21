<?php
/**
 * User: huangfuhui
 * Date: 2017/9/15
 * Email: huangfuhui@outlook.com
 */
return array(
    /**
     * 目标URL
     */
    'ANT_BASE_URL' => 'i.biquge5200.com',                                       // 首页
    'ANT_TOP_CLICK_URL' => 'i.biquge5200.com/top-monthvisit-',                  // 点击榜
    'ANT_SEARCH_URL' => 'i.biquge5200.com/modules/article/waps.php?keyword=',   // 搜索

    'ANT_TARGET_ENCODING' => 'gbk',         // 目标编码格式
    'ANT_EXPECT_ENCODING' => 'utf-8',       // 期望编码格式

    /**
     * 正则表达式
     */
    // 搜索结果列表
    'ANT_SEARCH_LIST_PATTERN' => '[<p class="line"><a href=".*">.*</a><a href="(.*)" class="blue">(.*)</a>/<a href="/author/.*">(.*)</a></p>]',
    // 点击榜列表
    'ANT_TOP_CLICK_LIST_PATTERN' => '[<p class="line"><a href=".*">.*</a><a href="(.*)" class="blue">(.*)</a>/<a href=".*">(.*)</a></p>]',
    // 点击榜总页数
    'ANT_TOP_CLICK_TOTAL_PAGE_PATTERN' => '[<div class="page">输入页数<input id="pageinput" size="4" /><input type="button" value="跳转" onclick="page\(\)" /> <br/>\(第1/(.*)页\)当前20条/页</div>]',
    // 小说封面
    'ANT_STORY_COVER_URL_PATTERN' => '[<div class="block_img2"><img src="(.*)" border]',
    // 小说名字
    'ANT_STORY_NAME_PATTERN' => '[<p><a href="/info-.*/"><h2>(.*)</h2></a></P>]',
    // 小说作者
    'ANT_STORY_AUTHOR_PATTERN' => '[<p>作者：<a href="/author/.*">(.*)</a></p>]',
    // 小说分类
    'ANT_STORY_type_PATTERN' => '[<p>分类：<a href="/.*/">(.*)小说</a></p>]',
    // 小说状态
    'ANT_STORY_status_PATTERN' => '[<p>状态：(.*?)</p>]',
    // 小说简介
    'ANT_STORY_introduction_PATTERN' => '[<div class="intro_info">(.*\s)</div>]',
    // 小说目录
    'ANT_STORY_MENU_PATTERN' => '[<span><a href="(.*)">查看目录</a></span>]',
    // 小说章节
    'ANT_CHAPTER_LIST_PATTERN' => '[<li><a href=\'/(.*?)/\'>(.*?)<span></span></a></li>]',
    // 小说章节总页码
    'ANT_CHAPTER_LIST_PAGE_PATTERN' => '[\(第.*/(.*)页\)当前.*条/页]',
    // 小说内容
    'ANT_CONTENT_PATTERN' => '[<div class="text">(.*)</div>.*</div>.*<script type="text/javascript">try{cnt2\(\);}catch\(err\){}</script>]s',
    // 小说内容的标题
    'ANT_CONTENT_TITLE_PATTERN' => '[<div class="title">(.*)</div><script type="text/javascript">try{restore\(\);}catch\(err\){}</script>]',
    // 章节上一页
    'ANT_LAST_CHAPTER_PATTERN' => '[继续阅读：<a href="/(.*)/">上一页]',
    // 章节下一页
    'ANT_NEXT_CHAPTER_PATTERN' => '[加书签</a><span>\|</span><a href="/(.*)/">下一章</a>]',
);