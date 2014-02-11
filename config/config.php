<?php
/**
 * --------------------------------
 * Zhuayi 网站名称
 * --------------------------------
 */ 
$config['web']['webname'] = '0611玄幻小说网';
$config['web']['appname'] = 'zhuayi';

/**
 * --------------------------------
 * Zhuayi 网站地址
 * --------------------------------
 */
$config['web']['weburl'] = 'http://0611.cc';
$config['web']['error_url'] = 'http://'.$_SERVER['HTTP_HOST'];

/**
 * --------------------------------
 * Zhuayi CDN地址 
 * --------------------------------
 */

/**
 * --------------------------------
 * Zhuayi 是否debug模式 
 * --------------------------------
 */
$config['debug'] = ($_SERVER['DEBUG'] == "true");

/**
 * --------------------------------
 * Zhuayi URL路由 默认控制器
 * --------------------------------
 */
$config['url_config']['default'] = 'book';

/**
 * --------------------------------
 * Zhuayi URL路由,键值支持正则
 * --------------------------------
 */

/* 是否开启二级域名支持 */
$config['url_domain'] = false;

/* 后台rewrite规则 */
$config['url_config']['routing']['^\/admin(.*)'] = '/error/$1';
$config['url_config']['routing']['^\/zpadmin(.*)'] = '/admin$1';


$config['url_config']['routing']['^\/detail\/(.*?)\/([0-9]+?)$'] = '/book/detail/$1/$2/';


?>
