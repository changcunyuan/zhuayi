<?php
/*
 * weibo.php     Zhuayi 淘宝客
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */

class weibo extends http
{
	/**
	 * 构 造 函 数
	 *
	 * @author zhuayi
	 */
	function __construct()
	{
		global $cache;
		parent::__construct();

		$this->cache = &$cache;
	}

	function run($ap_url,$array = array(),$method = 'get')
	{
		$cache_key = "weibo-".md5(json_encode($array));
		$reset = $this->cache->get($cache_key);
		if ($reset === false)
		{
			if ($method == 'get')
			{
				$reset = $this->get($ap_url,$array);
			}
			else
			{
				$this->post($ap_url,$array);
			}

			if ($this->status > 0)
			{
				throw new Exception($this->error, -1);
			}
			
			
			$this->results = str_replace('	','',$this->results);
			$reset = str_replace("\n","",$this->results);
			$reset = json_decode($reset,true);
			if (!is_array($reset))
			{
				throw new Exception("接口返回数据异常!", -1);
			}

			/* 判断是否调用失败 */
			if (isset($reset['error']))
			{
				throw new Exception($reset['error'].":{$reset['error_code']}", -1);
			}
			else
			{
				$this->cache->set($cache_key,$reset,$this->cache_outtime);
			}
			
		}
		
		return $reset;

	}

	function get_user_info_by_token($access_token,$uid)
	{
		$uid = intval($uid);
		$access_token = mysql_escape_string($access_token);

		if (empty($uid) || empty($access_token))
		{
			throw new Exception("参数错误!", 1);
		}
		$arr['access_token'] = $access_token;
		$arr['uid'] = $uid;
		return $this->run('https://api.weibo.com/2/users/show.json',$arr);
	}

}
 
?>