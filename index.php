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
//xhprof_lib在下载的包里存在这个目录,记得将目录包含到运行的php代码中
// include_once "/data/www/xhprof/xhprof_lib/utils/xhprof_lib.php";  
// include_once "/data/www/xhprof/xhprof_lib/utils/xhprof_runs.php";  
//xhprof_enable(XHPROF_FLAGS_MEMORY);
try
{
	/* error debug */
	$pagestartime = microtime();
	if (isset($_GET['error_debug']))
	{
		ini_set( "display_errors",true);
		error_reporting(E_ALL);
	}
	else
	{
		error_reporting(E_ALL^E_NOTICE^E_WARNING);
	}
	define('ZHUAYI_ROOT', str_replace("\\", '/', dirname(__FILE__)));
	require dirname(__FILE__)."/config/config.inc.php";
	$zhuayi = new zhuayi();
	$zhuayi->app();
} 
catch (ZException $e){}

// $xhprof_data = xhprof_disable(); 
// $xhprof_runs = new XHProfRuns_Default(); 
// $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo"); 
// echo "性能报告地址===="."<a href=http://xhprof/index.php?run=$run_id&source=xhprof_foo>点击查看报告</a>"; 
?>
