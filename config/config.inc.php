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

/* 设置cookie 域 */
$cookiedomain = explode(':',$config['cookie']['cookiedomain']);
ini_set('session.cookie_domain', $cookiedomain[0]);
if ($config['cookie']['save_handler'] == 'memcached')
{
	ini_set('session.save_handler', $config['cookie']['save_handler']);
	ini_set('session.save_path', $config['cookie']['cookiepath']);

	require PLUGINS_ROOT.'/cookie/session.class.php';
	$session = new session();
	session_set_save_handler(
								array($session,'sess_open'),
								array($session,'sess_close'),
								array($session,'sess_get'),
								array($session,'sess_set'),
								array($session,'sess_destroy'),
								array($session,'sess_gc')
							);
}

session_start();

require ZHUAYI_ROOT.'/plugins/core/zhuayi.php';

?>
