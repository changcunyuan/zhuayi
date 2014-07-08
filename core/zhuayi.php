<?php
/**
 * zhuayi.php     Zhuayi 框架初始化文件
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
$pagestartime = microtime();
define('ZHUAYI_ROOT', dirname(dirname(__FILE__)));
if (!defined('APP_NAME'))
{
    define('APP_NAME',substr(strrchr(APP_ROOT,DIRECTORY_SEPARATOR),1,100));  
}

define('UID', md5(time() . mt_rand(1,1000000)));

/*  输出页面字符集 */
header('Content-type: text/html; charset=utf-8');

if (isset($_SERVER['HTTP_X_REWRITE_URL']))
{
    $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
}
//ISAPI_Rewrite 2.x w/ HTTPD.INI configuration
else if (isset($_SERVER['HTTP_REQUEST_URI']))
{
    $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_REQUEST_URI'];
}

if (isset($_GET['error_debug']))
{
    ini_set( "display_errors",true);
    error_reporting(E_ALL);
}

/* -----设置时区----  */
date_default_timezone_set('Asia/Shanghai');

/* 加载框架主文件 */
require ZHUAYI_ROOT.'/core/zhuayi.class.php';
require ZHUAYI_ROOT.'/core/router.class.php';
require ZHUAYI_ROOT.'/core/action.class.php';
require ZHUAYI_ROOT.'/core/modle.class.php';
require ZHUAYI_ROOT.'/core/log.class.php';

//默认实例化类
spl_autoload_register(array('zhuayi', '_load_class'));
set_exception_handler(array('log','exception'));
set_error_handler(array('log','error'));
