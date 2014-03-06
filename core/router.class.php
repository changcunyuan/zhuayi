<?php
/**
 * router.class.php     Zhuayi URL路由类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */

class router extends zhuayi
{
    /* 模块名 */
    public $modle;

    /* 方法名 */
    public $action;

    public $parameter;

    public $url;

    static $appname;
    
    /**
     * 构造函数
     *
     */
    function __construct()
    {
        /* 将INI配置赋值到SERVER变量中 */
        $_SERVER['APP'] = zhuayi::get_conf();
        /* 兼容cli */
        if (php_sapi_name() === 'cli')
        {
            require dirname(__FILE__)."/cli.class.php";
            $this->url['path'] = cli::init();
        }
        else
        {
            $this->url = parse_url($_SERVER['REQUEST_URI']);
            $this->query = $this->url['query'];
        }
    }

    /* 格式化URL */
    public function parse_url()
    {
        $list = explode('/',$this->url['path']);
        unset($list[0]);

        $this->modle = (empty($list[1])) ? 'index' : $list[1];
        $this->action = (empty($list[2])) ? 'index' : $list[2];
        unset($list[1]);unset($list[2]);
        $this->parameter = $list;
        return $this;
    }

    /**
     * 正则匹配URL地址
     *
     */
    function routing()
    {
        $rewrite = zhuayi::get_conf('rewrite');

        if ($rewrite !== false)
        {
            foreach ($rewrite as $key=>$val)
            {
                $this->url['path'] = preg_replace('/'.$val.'/i',$key,$this->url['path']);
            }
        }
        return $this;
    }

    function __destruct()
    {
        /* 写入LOG */
        log::_write_log();
        zhuayi::perf_info();
    }
}