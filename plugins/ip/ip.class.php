<?php
/**
 * ip.class.php     Zhuayi  IP 类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ			 2179942
 * 
 * ------------------------------------------------
 * 
 * // 获取IP地理位置
 * ip::get_address('127.0.0.1') ||ip::get_address();
 * 
 * // 获取当前IP,如果为true则返回整数类型
 * ip::get_ip(); || ip::get_ip(true);
 * 
 * // 检查IP是否合法,如果合法返回整形数字
 * ip::check($ip);
 * 
 * // 将整形数字转换为IP地址
 * long2ip(int);
 * -------------------------------------------------
 */

class ip 
{

	function __construct()
	{
		
	}

	/** 检查IP是否合法,合法返回int **/
	function check($ip)
	{
		$ip = ip2long($ip);

		if ( $ip == '-1')
		{
			return false;
		}
		else
		{
			return $ip;
		}
	}


	/**
	 * getip 获取IP
	 *
	 * @param string getip  true 则转换未整数 
	 * @return void
	 * @author zhuayi
	 */
	static function get_ip($check = false)
	{
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		{
			$ip = getenv("HTTP_CLIENT_IP");
		}
		else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		{
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		{
			$ip = getenv("REMOTE_ADDR");
		}
		else if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		else
		{
			$ip = "unknown";
		}

		//在SAE等其他平台,获取的ID是用","隔开的,故这里只去第一个
		$ip = explode(',',$ip);
		$ip = trim($ip[0]);
		if ($check)
		{
			$ip = ip2long($ip);
		}
		return $ip;

	}

	/**
	 * get_address 根据IP获取地理位置
	 *
	 * @param string getip  true 则转换未整数 
	 * @return void
	 * @author zhuayi
	 */
	 function get_address($ip = '')
	 {

	 	if (empty($ip))
	 	{
	 		$ip = self::get_ip();
	 	}
	 	$reset = file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$ip);
	 	
	 	$reset = json_decode($reset,true);
	 	
	 	return $reset;
	 }

}
