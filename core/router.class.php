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
            $_SERVER['REQUEST_URI'] = str_replace('.json', '', $_SERVER['REQUEST_URI']);
            $this->url = parse_url($_SERVER['REQUEST_URI']);
        }
    }

    /* 格式化URL */
    public function parse_url()
    {
        $request_url = preg_replace('/(.*?)\?/', "", $_SERVER['REQUEST_URI']);
        parse_str($request_url,$_GET);
        $this->url['path'] = str_replace(".php",'',$this->url['path']);
        $list = array_filter(explode('/',$this->url['path']));

        if (count($list) == 0)
        {
            $list = array($this->default_modle,$this->default_action);
        }
        else if (count($list) == 1)
        {
            //array_push($list,$this->default_action); array_push没有直接[]效率高
            $list[] = $this->default_action;
        }
        $path = $list;
        array_shift($path);
        $this->modle = reset($list);
        $this->action = implode("_",$path);

        //unset($list[0]);
        // exit;
        // $this->modle = (empty($list[1])) ? $this->default_modle : $list[1];
        // $this->action = (empty($list[2])) ? $this->default_action : $list[2];
        // unset($list[1]);unset($list[2]);
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