<?php
return array(
    /**
     * 数据库配置
     */
    'DB_TYPE' => 'mysql',           // 数据库类型
    'DB_HOST' => 'localhost',       // 服务器地址
    'DB_NAME' => 'fairy',           // 数据库名
    'DB_USER' => 'root',            // 用户名
    'DB_PWD' => '',                 // 密码
    'DB_PORT' => '3306',            // 端口
    'DB_PREFIX' => 'fairy_',        // 数据库表前缀
    'DB_CHARSET' => 'utf8',         // 数据库编码
    'DB_DEBUG' => TRUE,             // 数据库调试模式 开启后可以记录SQL日志

    /**
     * URL配置
     */
    'URL_CASE_INSENSITIVE' => true, // URL大小写不敏感
    'URL_MODEL' => 2,               // URL支持REWRITE模式
    'URL_HTML_SUFFIX' => ''         // URL伪静态后缀
);