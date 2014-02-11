<?php
/**
 * cache.class.php     Zhuayi MC缓存类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ			 2179942
 **/
class mem_cache
{
	
	var $debug;

	/* MC执行次数 */
	var $cache_maxnum = 0;

	/* 命中次数 */
	var $cache_hitnum = 0;

	var $flag = false;

	private static $_instance;

	public $use_cache = true;

	/**
	 * 构 造 函 数
	 *
	 * @author zhuayi
	 */
	function __construct()
	{
		if (isset($_GET['cache_debug']))
		{
			/* 是否开启debug */
			$this->debug = true;
		}
		self::getInstance();
	}

	//单例方法,用于访问实例的公共的静态方法
	public static function getInstance()
	{
		if (!isset($_SERVER['BAIDU_CMS_MEMCACHE']))
		{
			throw new Exception("memcache 配置错误!", -1);
		}

		if(!(self::$_instance instanceof self))
		{
			self::$_instance = new Memcache;
			$memcache_config = explode(',',$_SERVER['BAIDU_CMS_MEMCACHE']);
			foreach ($memcache_config as $key=>$val)
			{
				$val = explode(':',$val);
				self::$_instance->addServer($val[0], $val[1]);
			}
		}

		return self::$_instance;
	}


	function select_connection()
	{
		self::getInstance();
		return $this;
	}

	/* 设置缓存组 */
	function group($group)
	{
		return self::$_instance->get('group_'.$group); 
	}

	/**
	 * set 设 置 缓 存
	 *
	 * @author zhuayi
	 */
	function set($key,$value,$expire='',$flag='',$group='')
	{
		if ($this->use_cache == false)
		{
			return false;
		}

		if (is_array($value))
		{
			$value = json_encode($value);
		}

		if ($expire === '')
		{
			$expire = $_SERVER['BAIDU_CMS_EXPIRE'];
		}
		if ($flag === '')
		{
			$flag = $this->flag;
		}

		if (!empty($group))
		{
			$key = $this->group($group).'_'.$key;
		}
		
		$key = $_SERVER['BAIDU_MEMCACHED_KEY'].'-'.$key;
		if ($this->debug)
		{
			echo "<!--\n cache: {$_SERVER['BAIDU_MEMCACHED_KEY']} set({$key}, ".print_r($value,true).", {$flag}, {$expire}) \n-->\n";
		}
		return self::$_instance->set(md5($key),$value,$flag,$expire);
	}
	
	/**
	 * increment 进行加法
	 *
	 * @param string $key 
	 * @param string $value 
	 * @return void
	 * @author zhuayi
	 */
	function increment($key,$value,$group='')
	{
		if ($this->use_cache == false)
		{
			return false;
		}
		if (!empty($group))
		{
			$key = $this->group($group).'_'.$key;
		}

		$key = $_SERVER['BAIDU_MEMCACHED_KEY'].'-'.$key;;
		if ($this->debug)
		{
			echo "<!-- cache::set({$key}, {$value}, {$flag}, {$expire}) -->\n";
		}


		return self::$_instance->increment(md5($key),$value);
	}
	
	/**
	 * decremen 进行减法
	 *
	 * @param string $key 
	 * @param string $value 
	 * @return void
	 * @author zhuayi
	 */
	function decremen($key,$value,$group='')
	{
		if ($this->use_cache == false)
		{
			return false;
		}
		if (!empty($group))
		{
			$key = $this->group($group).'_'.$key;
		}
		$key = $_SERVER['BAIDU_MEMCACHED_KEY'].'-'.$key;;
		if ($this->debug)
		{
			echo "<!-- cache_set: ({$key}, ".print_r($value,true).", {$flag}, {$expire}) -->\n";
		}
		return self::$_instance->decremen(md5($key),$value);
	}
	
	/**
	 * get 获 取 缓 存
	 *
	 * @author zhuayi
	 */
	function get($key,$type = false,$group='')
	{
		if ($this->use_cache == false)
		{
			return false;
		}
		if (!empty($group))
		{
			$key = $this->group($group).'_'.$key;
		}
		
		$this->cache_maxnum++;

		/* 重置缓存 */
		if (isset($_GET['recache']))
		{
			return false;
		}

		$debug_key = $key;

		if (is_array($key))
		{
			foreach ($key as $val)
			{
				$key_list[] = md5($_SERVER['BAIDU_MEMCACHED_KEY'].'-'.$val);
			}
			$key = $key_list;
		}
		else
		{
			//$key = md5(SAE_MEMCACHED_KEY.$key);
			$debug_key = $_SERVER['BAIDU_MEMCACHED_KEY'].'-'.$key;
			$key = md5($debug_key);
		}
		
		$reset = self::$_instance->get($key);
		
		if ($this->debug)
		{
			echo "<!--\n cache_get: {$_SERVER['BAIDU_MEMCACHED_KEY']}  ".print_r($debug_key,true)." ";
			var_dump(print_r($reset,true));
			echo " \n-->\n";
		}
		
		$this->cache_hitnum++;

		if ($type === true)
		{
			return $reset;
		}

		$json = json_decode($reset,true);

		if (is_array($json))
		{
			$reset =  $json;
		}

		return $reset;
		
	}
	
	/**
	 * delete 删 除 缓 存 
	 *
	 * @author zhuayi
	 */
	function delete($key,$group='')
	{
		if ($this->use_cache == false)
		{
			return false;
		}
		if (!empty($group))
		{
			$key = $this->group($group).'_'.$key;
		}

		$key = $_SERVER['BAIDU_MEMCACHED_KEY'].'-'.$key;

		if ($this->debug)
		{
			echo "<!--\n cache: delete({$key}) \n-->\n";
		}

		return self::$_instance->delete(md5($key));
	}

	/**
	 * 批量删除缓存组 
	 *
	 * @author zhuayi
	 */
	function flush($group)
	{
		if ($this->use_cache == false)
		{
			return false;
		}
		$reset = $this->group($group);
		$reset++;
        $reset = self::$_instance->set('group_'.$group, $reset);
	}

	/* 插入一个数组进入目标缓存 */
	function append_array($cache_key,$value = array())
	{
		if ($this->use_cache == false)
		{
			return false;
		}
		$reset = $this->get($cache_key);

		/* 检查是否已存在配置 */
		if (isset($reset[key($value)]))
		{
			return true;
		}
		if (!is_array($value))
		{
			throw new Exception("目标缓存不是一个数组,插入失败", -1);
		}

		$reset = array_merge($reset,$value);

		return $this->set($cache_key,$reset);
	}
}

?>
