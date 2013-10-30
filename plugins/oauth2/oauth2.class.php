<?php
/*
 * oauth2.php     Zhuayi oauth2通用登录类
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */

class oauth2 extends http
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

		$this->post_urlencode = true;
		$this->cache = &$cache;
	}

	function init($app)
	{
		$cache_key = "OAUTH-{$app}";
		$config = $this->cache->get($cache_key);

		if ($config === false)
		{
			$app = mysql_escape_string($app);
			$app_config = dirname(__FILE__)."/config/{$app}.config.php";

			if (!file_exists($app_config))
			{
				throw new Exception("初始{$app}失败!", -1);
			}

			$config = require_once $app_config;

			$this->cache->set($cache_key,$config);
		}
		
		$this->app = $app;
		foreach ($config as $key=>$val)
		{
			$this->$key = $val;
		}

		$this->redirect_uri = $_SERVER['HTTP_HOST'].$this->redirect_uri;
		$this->redirect_uri = "http://".str_replace('//','/',$this->redirect_uri);

		return $this;
	}

	/* 登录 */
	function app_login($callback)
	{
		$arr['client_id'] = $this->client_id;
		$arr['response_type'] = $this->response_type;
		$arr['redirect_uri'] = $this->redirect_uri.$callback;
		output::url($this->authorize_url."?".http_build_query($arr));
	}

	function authorize($code)
	{
		$arr['client_id'] = $this->client_id;
		$arr['grant_type'] = $this->grant_type;
		$arr['client_secret'] = $this->client_secret;
		$arr['code'] = $code;
		$arr['redirect_uri'] = $this->redirect_uri;
		$this->post($this->token_url,$arr);

		if ($this->app == 'qq')
		{
			return $this->authorize_by_qq();
		}

		$reset = json_decode($this->results,true);

		if (!is_array($reset) || isset($reset['error']))
		{
			throw new Exception($reset['error'], -1);
		}
		return $reset;
	}

	function authorize_by_qq()
	{
		if (strpos($this->results, 'callback') !== false)
		{
			preg_match_all('/callback\((.*?)\)/i',$this->results,$this->results);

			$this->results = trim($this->results[1][0]);
			$reset = json_decode($this->results,true);
			throw new Exception($reset['error_description'].":".$reset['error'], -1);
		}
		//获取token
		preg_match('/access_token=(.*?)&/i',$this->results,$token);
		return $token[1];
	}
}
 
?>