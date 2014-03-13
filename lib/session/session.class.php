<?php
/**
 * session.class.php     Zhuayi session类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 * ------------------------------------------------
 * 写入session $this->session->insert('user_info',array('user_name'=>'zhuayi','passport'=>'198698'))
 * 更新session某一个KEY的值 $this->session->update('user_info',array('user_name'=>'linadaelian'));
 * 删除session $this->session->delete('user_info','passport');;
 * 取所有session $this->session->get;
 * 取某个session $this->session->get['abc'];
 */
class session extends zhuayi
{

    function __construct()
    {
        /* 取session 配置 */
        $session_conf = parent::get_conf('session');

        /* 设置session cookie域 */
        if (empty($session_conf['session_cookie_domain']))
        {
            $session_conf['session_cookie_domain'] = explode(':',$session_conf['session_cookie_domain']);
            $session_conf['session_cookie_domain'] = $session_conf['session_cookie_domain'][0];
        }

        /* session存储方式,如果是memcache则直接存储在cache配置里 */
        if (empty($session_conf['session_save_handler']))
        {
            $session_conf['session_save_handler'] = 'file';
        }

        if ($session_conf['session_save_handler'] == 'memcache')
        {
            $cache_conf = parent::get_conf('cache');
            ini_set('session.save_path', "tcp://".$cache_conf['memcache_host']);
        }
        else
        {
            if (!empty($session_conf['session_save_path']))
            {
                ini_set('session.save_path', $session_conf['session_save_path']);
            }
        }
        if (empty($session_conf['session_cookie_domain']))
        {
            $session_conf['session_cookie_domain'] = $_SERVER['HTTP_HOST'];
        }
  
        ini_set('session.save_handler', $session_conf['session_save_handler']);
        ini_set('session.name', $session_conf['session_cookie_name']);
        session_set_cookie_params($session_conf['session_expiration'],'/',$session_conf['session_cookie_domain']);
        session_start();
    }

    function __get($name = '')
    {
        if ($name == 'get')
        {
            return $this->get = $this->get();
        }
        elseif ($name == 'cache')
        {
            return $this->cache = new cache();
        }
    }

    /* 写入session */
    public function insert($key,$value)
    {
        $_SESSION[$key] = $value;
    }

    /* 修改session数组里的某个值 */
    public function update($key,$value)
    {
        if (!is_array($value))
        {
            return false;
        }
        $_SESSION[$key] = array_merge($_SESSION[$key],$value);
    }

    /* 删除session */
    public function delete($key,$key2)
    {
        if (empty($key2))
        {
            $_SESSION[$key] = '';
        }
        else
        {
            $_SESSION[$key][$key2] = '';
        }
    }

    /* 获取session */
    function get()
    {
        return $_SESSION;
    }

    function __destruct()
    {
        session_write_close();
    }
   
}