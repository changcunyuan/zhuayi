<?php
/**
 * --------------------------------
 * Zhuayi 网站名称
 * --------------------------------
 */ 
$config['web']['webname'] = 'Zhuayi';
$config['web']['appname'] = 'zhuayi';

/**
 * --------------------------------
 * Zhuayi 网站地址
 * --------------------------------
 */
$config['web']['weburl'] = 'http://'.$_SERVER['HTTP_HOST'];
$config['web']['error_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/zpadmin';

/**
 * --------------------------------
 * Zhuayi cookie
 * --------------------------------
 */
$config['cookie']['cookiedomain'] = $_SERVER['HTTP_HOST'];
$config['cookie']['save_handler'] = 'memcache'; //files oe memcache
$config['cookie']['outtime'] = 86400; //过期时间
$config['cookie']['cookiepath'] = $_SERVER['COOKIE_PATH']; ///tmp or tcp://127.0.0.1 多台服务器共享使用多个 memcached server 时用逗号","隔开

/**
 * --------------------------------
 * Zhuayi CDN地址 
 * --------------------------------
 */
$config['cdn']['url'] = 'http://img1sw.baidu.com';
$config['cdn']['css_url'] = $config['cdn']['url'].'/www/jiguang';
$config['cdn']['version'] = '1';

/**
 * --------------------------------
 * Zhuayi 是否debug模式 
 * --------------------------------
 */
$config['debug'] = $_SERVER['DEBUG'];

/**
 * --------------------------------
 * Zhuayi URL路由 默认控制器
 * --------------------------------
 */
$config['url_config']['default'] = 'index';

/**
 * --------------------------------
 * Zhuayi URL路由,键值支持正则
 * --------------------------------
 */

/* 是否开启二级域名支持 */
$config['url_domain'] = true;

/* 后台rewrite规则 */
$config['url_config']['routing']['^\/admin(.*)'] = '/error/$1';
$config['url_config']['routing']['^\/zpadmin(.*)'] = '/admin$1';


?>