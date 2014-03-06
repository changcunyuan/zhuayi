<?php
/**
 * cache.class.php     Zhuayi memcache cache 
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
class cache
{
    public $expire = 300;

    public $flag = false;

    private static $_instance;

    public  static $use_cache;

    function __construct()
    {
       self::$_instance = self::getInstance();
    }

    //单例方法,用于访问实例的公共的静态方法
    public static function getInstance()
    {
        if(!(self::$_instance instanceof self))
        {
            $conf = zhuayi::get_conf('cache');

            if ($conf['use_cache'] === 'on')
            {
                self::$use_cache = false;
                return true;
            }
            self::$_instance = new Memcache;

            $memcache_config = explode(',',$conf['memcache_host']);
            foreach ($memcache_config as $key=>$val)
            {
                $val = explode(':',$val);
                self::$_instance->addServer($val[0], $val[1],true);
            }
        }

        return self::$_instance;
    }

    private function __clone() {}

    /**
     * set 设 置 缓 存
     *
     * @author zhuayi
     */
    function set($key,$value,$expire='',$flag='')
    {
        if (self::$use_cache === false)
        {
            return false;
        }

        if (is_array($value))
        {
            $value = json_encode($value);
        }

        if ($expire === '')
        {
            $expire = $this->expire;
        }
        if ($flag === '')
        {
            $flag = $this->flag;
        }
        
        $key = APP_NAME.'-'.$key;
        if ($_SERVER['APP']['debug'] && isset($_GET['cache_debug']))
        {
            echo "<!--\n cache: set({$key}, ".print_r($value,true).", {$flag}, {$expire}) \n-->\n";
        }
        return self::$_instance->set(md5($key),$value,$flag,$expire);
    }


    function get($key)
    {
        if (self::$use_cache === false)
        {
            return false;
        }

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
                $key_list[] = md5(APP_NAME.'-'.$val);
            }
            $key = $key_list;
        }
        else
        {
            $debug_key = APP_NAME.'-'.$key;
            $key = md5($debug_key);
        }
        
        $reset = self::$_instance->get($key);
        
        if ($_SERVER['APP']['debug'] && isset($_GET['cache_debug']))
        {
            echo "<!--\n cache_get: ".print_r($debug_key,true)." ";
            var_dump(print_r($reset,true));
            echo " \n-->\n";
        }

        $json = json_decode($reset,true);

        if (is_array($json))
        {
            $reset =  $json;
        }

        return $reset;
    }
}