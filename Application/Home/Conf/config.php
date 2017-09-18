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

    /**
     * 正则表达式
     */
    // 搜索结果列表
    'ANT_SEARCH_LIST_PATTERN' => '[<p class="line"><a href=".*">.*</a><a href="(.*)" class="blue">(.*)</a>/<a href="/author/.*">(.*)</a></p>]',
    // 点击榜列表
    'ANT_TOP_CLICK_LIST_PATTERN' => '[<p class="line"><a href=".*">.*</a><a href="(.*)" class="blue">(.*)</a>/<a href=".*">(.*)</a></p>]',
    // 点击榜总页数
    'ANT_TOP_CLICK_TOTAL_PAGE_PATTERN' => '[<div class="page">输入页数<input id="pageinput" size="4" /><input type="button" value="跳转" onclick="page\(\)" /> <br/>\(第1/(.*)页\)当前20条/页</div>]',
    'ANT_CHAPTER_PATTERN' => '[<dd> <a href="/16_16431/(.*)">(.*)</a></dd>]',
    'ANT_CONTENT_PATTERN' => '[<div id="content"><script>readx\(\);</script>(.*)<script>chaptererror\(\);</script>]',

    'ANT_TARGET_ENCODING' => 'gbk',         // 目标编码格式
    'ANT_EXPECT_ENCODING' => 'utf-8',       // 期望编码格式
);