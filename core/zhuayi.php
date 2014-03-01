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
define('APP_NAME',substr(strrchr(APP_ROOT,'/'),1,100));
require ZHUAYI_ROOT.'/core/zhuayi.class.php';
require ZHUAYI_ROOT.'/core/router.class.php';
require ZHUAYI_ROOT.'/core/action.class.php';
require ZHUAYI_ROOT.'/core/log.class.php';

/*  输出页面字符集 */
header('Content-type: text/html; charset=utf-8');

/* -----设置时区  */
date_default_timezone_set('Asia/Shanghai');

//默认实例化类
spl_autoload_register(array('zhuayi', '_load_class'));

set_exception_handler(array('log','exception'));

/* 将INI配置赋值到SERVER变量中 */

$router = new router();
$router->routing()->parse_url()->parse_cgi()->app();
