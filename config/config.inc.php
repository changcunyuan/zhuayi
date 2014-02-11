<?php
/**
* config.inc.php     Zhuayi 入口文件
*
* @copyright    (C) 2005 - 2010  Zhuayi
* @licenes      http://www.zhuayi.net
* @lastmodify   2010-10-27
* @author       zhuayi
* @QQ			2179942
*/
/* error debug */
defined('ZHUAYI_ROOT') or define('ZHUAYI_ROOT', str_replace("\\", '/', substr(dirname(__FILE__),0,-6)));

/*  输出页面字符集 */
header('Content-type: text/html; charset=utf-8');

/* -----设置时区  */
date_default_timezone_set('Asia/Shanghai');

/* -----定义Zhuayi根目录路径  */
define('APP_ROOT', ZHUAYI_ROOT.'/zhuayi/');

define('PLUGINS_ROOT', ZHUAYI_ROOT.'/plugins/');

if (!isset($_SERVER['HTTP_HOST']))
{
	$_SERVER['HTTP_HOST'] = ZHUAYI_ROOT;
}

require ZHUAYI_ROOT.'/config/config.php';
/**
 * --------------------------------
 * Zhuayi cookie
 * --------------------------------
 */
//COOKIE_PATH is tmp or tcp://127.0.0.1 多台服务器共享使用多个 memcached server 时用逗号","隔开
/* 设置cookie 域 */
$cookiedomain = explode(':',$_SERVER['HTTP_HOST']);
ini_set('session.cookie_domain', $cookiedomain[0]);
ini_set('session.name', "ZHUAYISID");

if (isset($_SERVER['COOKIE_HANDLER']) && $_SERVER['COOKIE_HANDLER'] == 'memcache')
{
	ini_set('session.save_handler', $_SERVER['COOKIE_HANDLER']);
	ini_set('session.save_path', $_SERVER['COOKIE_PATH']);
	require PLUGINS_ROOT.'/cookie/session.class.php';
	$session = session::getInstance();
	session_set_save_handler(
							    array($session, 'open'),
							    array($session, 'close'),
							    array($session, 'read'),
							    array($session, 'write'),
							    array($session, 'destroy'),
							    array($session, 'gc')
							    );
}

session_start();
require ZHUAYI_ROOT.'/plugins/core/zhuayi.php';
