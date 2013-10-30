<?php
/*
 * cron_available_monitor.php     URL监控 

 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 * php cron_test.php -c /data/vhosts/baidu_soft.conf -soft_id  ~/Downloads/soft_id  -out ~/Downloads/hao123.xml
 */
include_once "../cron/cron.inc.php";
class cron_test extends zhuayi
{

	/* 脚本最大执行时间 */
	public $timeout = 1200;

	public $run_start_time;


	/* 构造函数 */
	function __construct()
	{
		parent::__construct();
		
		$this->load_class('db',false);

		parent::$admin = true;


	}

	function run()
	{
		global $argv_array;
		print_r($argv_array);
		print_r($_SERVER);

	}
}

$cron = new cron_test();
try
{

	$reset = false;
	do
	{
		$reset = $cron->run($argv);
	}
	while ($reset===false);
	
} 
catch (ZException $e){}

?>