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


	//连接MC
	public static function _get_mc($host,$port)
	{
		if(!(self::$_mc_instance instanceof self))
		{
			if (strpos($host,'//') !== false)
			{
				$cookiepath = explode('//',$host);
				$cookiepath = explode(':', $cookiepath[1]);
				$host = $cookiepath[0];
				$port = $cookiepath[1];
			}
			self::$_mc_instance = new Memcache;
			self::$_mc_instance->connect($host, $port);
		}
		return self::$_mc_instance;
	}

	static function sess_open($sess_path, $sess_name)
	{
           return true;
    }

    static function sess_close()
    {
            return true;
    }

    static function sess_get($sess_id)
    {
    	global $config;
    	self::_get_mc($config['cookie']['cookiepath']);
        return self::unserializesession(self::$_mc_instance->get($sess_id));
    }

    static function sess_set($sid, $data)
    {
    	global $config;
    	self::_get_mc($config['cookie']['cookiepath']);
        return self::$_mc_instance->set($sid,$data,MEMCACHE_COMPRESSED,self::$maxtime); 
    }

    static function sess_destroy($sess_id)
    {
        return self::$_mc_instance->delete($sess_id);
    }

    static function sess_gc($sess_maxlifetime)
    {
    	return true;
    }

    /* 格式化session 数据 */
    static function unserializesession($data)
    {
        $vars = preg_split(
        					'/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff^|]*)\|/',
                 			 $data,-1,
                 			 PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
                 		   );
        for($i=0; $vars[$i]; $i++)
        {
        	$result[$vars[$i++]] = unserialize($vars[$i]);
        }
        return $result;
    }

 }