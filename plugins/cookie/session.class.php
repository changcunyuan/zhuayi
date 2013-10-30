<?php
/**
 * session.class.php     Zhuayi 自定义session类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ			 2179942
 * 
 */
class session
{
	private static $_mc_instance;

	//连接MC
	public static function _get_mc($host)
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
        self::unserializesession(self::$_mc_instance->get($sess_id));
        return true;
    }

    static function sess_set($sid, $data)
    {
    	global $config;
    	self::_get_mc($config['cookie']['cookiepath']);
        self::$_mc_instance->set($sid,$data,MEMCACHE_COMPRESSED,$_SERVER['COOKIE_TIMEOUT']); 
        return true;
    }

    static function sess_destroy($sess_id)
    {
        return true;
    }

    static function sess_gc($maxlifetime)
    {
    	return true;
    }

    /* 格式化session 数据 */
    static function unserializesession($data)
    {
    	if( strlen( $data) == 0)
		{
			return array();
		}
		preg_match_all('/(^|;|\})([a-zA-Z0-9_]+)\|/i', $data, $matchesarray, PREG_OFFSET_CAPTURE);
		$returnArray = array();
		$lastOffset = null;
		$currentKey = '';
		foreach ($matchesarray[2] as $value)
		{
			$offset = $value[1];
			if(!is_null( $lastOffset))
			{
				$valueText = substr($data, $lastOffset, $offset - $lastOffset );
				$returnArray[$currentKey] = unserialize($valueText);
			}
			
			$currentKey = $value[0];
			$lastOffset = $offset + strlen( $currentKey )+1;
		}

		$valueText = substr($data, $lastOffset );
		$returnArray[$currentKey] = unserialize($valueText);

		return $returnArray;
    }
 }