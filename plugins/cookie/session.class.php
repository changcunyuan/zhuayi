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
class session implements SessionHandlerInterface
{
	private static $_mc_instance;

	private static $_instance;

	//创建__clone方法防止对象被复制克隆
	public function __clone()
	{
		trigger_error('Clone is not allow!',E_USER_ERROR);
	}

	//单例方法,用于访问实例的公共的静态方法
	public static function getInstance()
	{
		if(!(self::$_instance instanceof self))
		{
			self::$_instance = new self();
			self::$_mc_instance = new Memcache;

			$cookiepath = explode(',',$_SERVER['COOKIE_PATH']);
			$cookiepath = explode(':', $cookiepath[0]);
			$host = $cookiepath[0];
			$port = $cookiepath[1];

			self::$_mc_instance->connect($host, $port);
		}
		return self::$_instance;
	}

	function open($sess_path, $sess_name)
	{
		return true;
    }

    function close()
    {
		return true;
    }

    function read($sess_id)
    {
    	return self::$_mc_instance->get($sess_id);
    }

    function write($sid, $data)
    {
        return self::$_mc_instance->set($sid,$data,0,$_SERVER['COOKIE_TIMEOUT']);
    }

    function destroy($sess_id)
    {
        return true;
    }

    function gc($maxlifetime)
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