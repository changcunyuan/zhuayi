<?php
/**
 * zredis.class.php     Zhuayi redis 
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
class zredis extends zhuayi
{
    private static $_instance;

    public  static $use_cache = true;

    function __construct()
    {
        self::$_instance = self::getInstance();
    }

    //单例方法,用于访问实例的公共的静态方法
    public static function getInstance()
    {
        if(!is_object(self::$_instance))
        {
            $db_conf = zhuayi::get_conf('redis');
            try
            {
                self::$_instance = new Redis();
            }
            catch (Exception $e)
            {
                self::$use_cache = false;
                return true;
            }
            
            $db_conf = explode(',',$db_conf['host']);
          
            foreach ($db_conf as $key=>$val)
            {
                $val = explode(':',$val);
                self::$_instance->pconnect($val[0],$val[1]);
            }
        }

        return self::$_instance;
    }

    private function __clone() {}


    public function set($key,$value,$expire = 0)
    {
        if (self::$use_cache === false)
        {
            return false;
        }

        if ($expire > 0)
        {
            return self::$_instance->set(md5($key), $value,$expire);
        }

        return self::$_instance->setex(md5($key), $value);
    }


    public function get($key)
    {
        $func = is_array($key) ? 'mGet' : 'get';
        return self::$_instance->$func(md5($key));
    }

    function __call($key,$val)
    {
        return call_user_func_array(array(self::$_instance,$key), $val);
    }
}