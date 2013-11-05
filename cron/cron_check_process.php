#!/usr/bin/php
<?php
/*
 * cron_check_process.php     URL监控 

 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */
include dirname(__FILE__)."/cron.inc.php";
class cron_check_process extends zhuayi
{

	/* 脚本最大执行时间 */
	public $timeout = 1200;

	public $run_start_time;


	/* 构造函数 */
	function __construct()
	{
		parent::__construct();
		
		$this->run_start_time = time();
		parent::$admin = true;

		$this->process_config_file = dirname(__FILE__)."/process.conf.php";
		if (!file_exists($this->process_config_file))
		{
			throw new Exception("进程配置文件不存在!", -1);
		}
	}

	function run()
	{
		global $argv_array;
		
		$process_list = require $this->process_config_file;

		print_r($process_list);
		exit;

	}
}

$cron = new cron_check_process();
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