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

    public $default_modle = 'index';

    public $default_action = 'index';

    
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
            $this->url = cli::init();
        }
        else
        {
            $this->url = parse_url($_SERVER['REQUEST_URI']);
        }
    }

    /* 格式化URL */
    public function parse_url()
    {
        $request_url = preg_replace('/(.*?)\?/', "", $_SERVER['REQUEST_URI']);
        parse_str($request_url,$_GET);
        $this->url['path'] = str_replace(".php",'',$this->url['path']);
        $list = explode('/',$this->url['path']);
        unset($list[0]);

        $this->modle = (empty($list[1])) ? $this->default_modle : $list[1];
        $this->action = (empty($list[2])) ? $this->default_action : $list[2];
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
        $rewrite = $_SERVER['APP']['rewire'];
        if ($rewrite !== false)
        {
            foreach ($rewrite as $key=>$val)
            {
                $this->url['path'] = preg_replace('/'.$val.'/i',$key,$this->url['path']);
            }
        }
        return $this;
    }
}