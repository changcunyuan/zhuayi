<?php
/**
 * url.class.php     Zhuayi URL路由类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ			 2179942
 */

class z_url
{
	
	public $url_config;

	public $url_domain = false; 

	/**
	 * 构造函数
	 *
	 */
	function __construct($url)
	{
		$this->url = $url;
	}

	/**
	 * 将当前URL进行路由映射
	 *
	 */
	function url($url_config)
	{
		global $config;

		$weburl = str_replace('http://','',$config['web']['weburl']);
		if ($this->url_domain && $_SERVER['HTTP_HOST']!= $weburl)
		{
			$domain = explode('.', $_SERVER['HTTP_HOST']);

			if ($domain[0] != 'www' && count($domain) > 3)
			{
				$this->url = explode('/',$this->url);
				
				if ($this->url[1] == 'api')
				{
					$this->url[1] = 'api/'.$domain[0];
					$this->url = implode('/',$this->url);
				}
				else
				{
					$this->url = implode('/',$this->url);
					$this->url = "/".$domain[0].$this->url;
				}
			}
		}

		$this->url_config = $url_config;

		if (!isset($this->url_config['default']))
		{
			$this->url_config['default'] = 'index';
		}

		$this->replace_debug()->routing()->xxs();


		/* 根据"/"把URL转换成数组 */
		$url = explode('/',$this->url);
		unset($url[0]);
		/* 管理平台 */
		if ($url[1] == 'admin')
		{
			$controller['admin'] = true;

			if ($url[2] == 'api')
			{
				$controller['modle'] = "api";
				$controller['finder'] = $url[3];
				$controller['action'] = $url[4];
				
				unset($url[4]);
			}
			else
			{
				$controller['modle'] = $url[2];
				$controller['action'] = $url[3];
			}
			if (empty($controller['modle']))
			{
				$controller['modle'] = "admin";
			}
			
			if (empty($controller['action']))
			{
				$controller['action'] = "index";
			}
			unset($url[1]);
			unset($url[2]);
			unset($url[3]);
		}
		else
		{
			$controller['admin'] = false;
			if ($url[1] == 'api')
			{
				$controller['modle'] = "api";
				$controller['finder'] = $url[2];
				$controller['action'] = $url[3];
				
				unset($url[3]);
			}
			else
			{
				$controller['modle'] = $url[1];
				$controller['action'] = $url[2];
			}
			
			if (empty($controller['modle']))
			{
				$controller['modle'] = $this->url_config['default'];
			}
			
			if (empty($controller['action']))
			{
				$controller['action'] = "index";
			}
			unset($url[1]);
			unset($url[2]);
		}

		$controller['fileds'] = $url;

		$controller['get'] = $this->get;
		return $controller;
	}


	/**
	 * 去掉URL中的GET参数,?之后的所有数据
	 *
	 */
	function replace_debug()
	{
		preg_match('/(.*?)\?(.*)/i',$this->url,$list);

		if (isset($list[1]))
		{
			$this->url = $list[1];
		}
		if (isset($list[1]))
		{
			$this->get = $list[2];
		}
		else
		{
			$this->get = '';
		}
		

		return $this;
	}

	/**
	 * 正则匹配URL地址
	 *
	 */
	function routing()
	{
		if (isset($this->url_config['routing']))
		{
			foreach ($this->url_config['routing'] as $key=>$val)
			{
				$this->url = preg_replace('/'.$key.'/i',$val,$this->url);
			}
		}
		preg_replace('/'.$key.'/i',$val,$this->url);
		
		return $this;
	}

	/**
	 * 过滤XXS
	 *
	 */
	function xxs()
	{
		/* 过滤XSS 攻击脚本 */
		foreach ($_GET as $key=>$val)
		{
			$_GET[$key] = addslashes(preg_replace('#<script>(.*)<\/script>#','',$val));
		}
	}
}


?>