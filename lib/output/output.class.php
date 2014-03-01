<?php
/**
 * output.php     Zhuayi 消息输出类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ			 2179942
 */
class output
{
	static $level = array(
							'debug' => 'DEBUG',
							'trace' => 'TRACE',
							'notice' => 'NOTICE',
							'warning' => 'WARING',
							'fatal' => 'FATAL',
						  );

	static function _log_string()
	{
		$this->level[$debug_array['function']].": ".date("Y-m-d H:i:s")." {$config['web']['appname']} {$this->uniqid} [local_ip={$_SERVER['REMOTE_ADDR']} client_ip= ".ip::get_ip()." module={$debug_array['module']} action={$debug_array['action']} line={$debug_array['line']} run_time={$run_time}s {$filename} errno={$errno} msg={$msg}] \n";
	}

	/**
	 * 错误页面,
	 *
	 * @param string $title 错误页面提示性文字
	 */
	static function error($error)
	{
		//print_r($error);

		echo "[error]: ".date("Y-m-d H:i:s").$error->getMessage();
	}
}
?>
