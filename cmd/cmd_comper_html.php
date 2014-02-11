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



	/* 构造函数 */
	function __construct()
	{
		parent::__construct();
		
		$this->run_start_time = time();
		parent::$admin = true;
	}

	function run()
	{
		
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