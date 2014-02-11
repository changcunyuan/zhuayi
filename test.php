<?php
/**
* index.php     Zhuayi 入口文件
*
* @copyright    (C) 2005 - 2010  Zhuayi
* @licenes      http://www.zhuayi.net
* @lastmodify   2010-10-27
* @author       zhuayi
* @QQ			2179942
*/
ini_set( "display_errors",true);
error_reporting(E_ALL);
try
{
	/* error debug */
	$pagestartime = microtime();
	if (isset($_GET['error_debug']) && $_SERVER['DEBUG'] == "true")
	{
		ini_set( "display_errors",true);
		error_reporting(E_ALL);
	}
	else
	{
		error_reporting(E_ALL^E_NOTICE^E_WARNING);
	}
	define('ZHUAYI_ROOT', getcwd());
	require ZHUAYI_ROOT."/config/config.inc.php";
	$zhuayi = new zhuayi();
	$zhuayi->app();
} 
catch (ZException $e){}