<?php
/**
 * cookie.class.php     Zhuayi COOKIE操作类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ			 2179942
 * 
 * ------------------------------------------------
 * $this->load_class('http',true);
 * 
 * // 设 置 来 路
 * $this->http->referer = 'http://www.baidu.com';
 * 
 * // 设 置 COOKIE 
 * $this->http->cookie = $_COOKIE;
 * 
 * // 设 置 POST 提 交 ,
 * $this->http->post(url,参 数);
 * 
 * // 设 置 POST 提 交 并 上 传 文 件,
 * $this->http->post(url,array('参 数'=>' 参 数 1 值',filename'=>'@$val'));
 * 
 * // 设 置 GET
 * $this->http->get(url,array('参 数'=>' 参 数 1 值'...));
 * -------------------------------------------------
 */
class cookie
{
	private static $_mc_instance;

 	/** 
	 * 设置COOKIE
	 *
	 * @param string $key COOKIE键值
	 * @param string $val 该键值对应的值，可以是数组，但会被序列化
	 */
	
	 function set_cookie($key,$val,$time=86400,$cookie_domain = '')
	 {
		if ($time > 0)
		{
			$time = time()+$time;
		}
		if (is_array($val))
		{
			foreach ($val as $keys=>$val2)
			{
				if (!is_array($val2))
				{
					$val[$keys] = urlencode($val2);
				}
				else
				{
					foreach ($val2 as $key3=>$val3)
					{
						$val2[$key3] = urlencode($val3);
					}
					$val[$keys] = $val2;
				}
			}
			$val = json_encode($val);
		}

		/* 放置iframe不能设置cookie*/ 
		header('P3P: CP="ALL ADM DEV PSAi COM OUR OTRo STP IND ONL"');


		$_SESSION['zhuayi_'.$key] = $val;
	}

	/** 
	 * 返回COOKIE
	 *
	 * @param string $key 键值可以是数组，但会被序列化
	 */
	
	function ret_cookie($key)
	{
			
		$string = stripslashes(htmlspecialchars_decode($_SESSION['zhuayi_'.$key]));

		$json = json_decode($string,true);
			
		/* 判断是否JSON对象 */
		if (is_array($json))
		{
			foreach ($json as $key=>$val2)
			{
				$key = trim($key);
				if (!is_array($val2))
				{
					$json[$key] = urldecode($val2);
				}
				else
				{
					foreach ($val2 as $key3=>$val3)
					{
						$val2[$key3] = urldecode($val3);
					}
					$json[$key] = $val2;
				}
			}				
			$string = $json;
		}
		return $string;	
	}
 }