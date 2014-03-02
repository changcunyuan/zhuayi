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

    public $get;

    public $post;

    public $url;

    static $appname;
    /**
     * 构造函数
     *
     */
    function __construct()
    {
        /* 兼容cli */
        if (php_sapi_name() === 'cli')
        {
            require dirname(__FILE__)."/cli.class.php";
            $this->url['path'] = cli::init();
        }
        else
        {
            $this->url = parse_url($_SERVER['REQUEST_URI']);
        }
    }


    /* 格式化URL */
    public function parse_url()
    {
        $list = explode('/',$this->url['path']);
        unset($list[0]);

        $this->modle = (empty($list[1])) ? $_SERVER['APP']['global']['default_module'] : $list[1];
        $this->action = (empty($list[2])) ? $_SERVER['APP']['global']['default_action'] : $list[2];
        unset($list[1]);unset($list[2]);
        $this->parameter = $list;
        return $this;
    }

    /* 格式化get post 参数 */
    public function parse_cgi()
    {
        ini_set("magic_quotes_runtime", 0);

        //处理被 get_magic_quotes_gpc 自动转义的数据,转换为HTML实体

        $this->get = $_GET;
        $this->post = $_POST;
        $in = array(& $this->get, & $this->post);
        while (list ($k, $v) = each($in))
        {
            foreach ($v as $key => $val)
            {
                if (! is_array($val))
                {
                    $in[$k][$key] = htmlspecialchars($val);
                    continue;
                }
                $in[] = & $in[$k][$key];
            }
        }
        unset($in);

        return $this;
    }

    /**
     * 正则匹配URL地址
     *
     */
    function routing()
    {
        if (isset($_SERVER['APP']['global']['rewrite']))
        {
            foreach ($_SERVER['APP']['global']['rewrite'] as $key=>$val)
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