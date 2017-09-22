<?php
return array(
    //'配置项'=>'配置值'
    'URL_MODEL' => '1',
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => 'localhost', // 服务器地址
    'DB_NAME' => 'xiangsu', // 数据库名
    'DB_USER' => 'xiangsu_user', // 用户名
    'DB_PWD' => 'hqc203', // 密码
    'DB_PORT' => 3306, // 端口
    'DB_PREFIX' => 'xs_', // 数据库表前缀
    'DB_CHARSET' => 'utf8', // 字符集
    'DB_DEBUG' => TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    'SHOW_PAGE_TRACE' => 1,
    'DEFAULT_THEME' => 'default',//制定默认前台模板，便于以后切换
    'APP_SUB_DOMAIN_DEPLOY' => 1, // 开启子域名或者IP配置
    'APP_SUB_DOMAIN_RULES' => array(
        'w' => 'Home',
        '*' => array('/Website/'), // 泛解析，所有域名指向Home分组下的member模块
    ),

);