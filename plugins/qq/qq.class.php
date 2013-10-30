<?php
/*
 * qq.php     Zhuayi 淘宝客
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */

class qq extends oauth2
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

	function run($api_url,$array = array(),$method = 'get')
	{
		$cache_key = "qq-".md5(json_encode($array));
		$reset = $this->cache->get($cache_key);
		if ($reset === false)
		{
			if ($method == 'get')
			{
				$reset = $this->get($api_url,$array);
			}
			else
			{
				$this->post($api_url,$array);
			}

			if ($this->status > 0)
			{
				throw new Exception($this->error, -1);
			}
			if (strpos($this->results, 'callback') !== false)
			{
				preg_match_all('/callback\((.*?)\)/i',$this->results,$this->results);
				$this->results = trim($this->results[1][0]);
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

	function get_openid($access_token)
	{
		if (empty($access_token))
		{
			throw new Exception("参数错误!", 1);
		}
		$arr['access_token'] = $access_token;
		return $this->run('https://graph.qq.com/oauth2.0/me',$arr);
	}

	function get_user_info_by_token($access_token)
	{
		$openid = $this->get_openid($access_token);

		$access_token = mysql_escape_string($access_token);

		if (empty($openid) || empty($access_token))
		{
			throw new Exception("参数错误!", 1);
		}
		$arr['access_token'] = $access_token;
		$arr['openid'] = $openid['openid'];
		$arr['oauth_consumer_key'] = $openid['client_id'];
		$arr['format'] = 'json';
		$reset = $this->run('https://graph.qq.com/user/get_user_info',$arr);
		$reset['access_token'] = $access_token;
		return $reset;
	}

}
 
?>