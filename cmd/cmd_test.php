#!/usr/bin/php
<?php
/*
 * cmd.php    

 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */
include dirname(dirname(__FILE__))."/cron/cron.inc.php";
class cmd extends zhuayi
{

	/* 脚本最大执行时间 */
	public $timeout = 1200;

	public $run_start_time;

	static $shell = "./cmd/cmd_test_2.php -c /data/vhosts/zhuayi.conf -d #page#";


	/* 构造函数 */
	function __construct()
	{
		parent::__construct();
		
		$this->run_start_time = time();
		parent::$admin = true;

	}

	function run()
	{
		global $argv_array;

		$p_number = self::check_processes_number("./cmd/cmd_test_2.php -c /data/vhosts/zhuayi.conf -d ",80);
		
		for ($i=0;$i<=$p_number;$i++)
		{
			pclose(popen('./cmd/cmd_test_2.php -c /data/vhosts/zhuayi.conf -d '.$i.'  >> ~/11.log 2>>~/11.log &',"r"));
		}
		
		return false;
	}

	//$limit 允许推到后台的最大进程数
	//$shell shell 可执行路径  
	function check_processes_number($shell,$limit)
	{
		while ($p_number <= 0)
		{
			$cmd = popen("ps -ef | grep \"{$shell}\" | grep -v grep | wc -l", "r");  
			$line = fread($cmd, 512);
			pclose($cmd);
			$p_number = $limit - $line;
			if ($p_number <= 0)
			{
				sleep(1);
			}
		}
		return $p_number;
	}

}

$cron = new cmd();
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