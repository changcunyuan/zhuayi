#!/usr/bin/php
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
include dirname(__FILE__)."/cron.inc.php";
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
		$this->run_start_time = time();
		parent::$admin = true;
	}

	function run()
	{
		global $argv_array;
		
		$file = file_get_contents($argv_array['-c']);
		$run_time = time() - $this->run_start_time;

		var_dump($run_time);
		var_dump(sprintf('%0.5f', memory_get_usage() / 1048576 ));
		if ($run_time < $this->timeout)
		{
			//sleep(1);
			//return false;
		}
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