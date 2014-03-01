<?php
/**
 * zhuayi.class.php     Zhuayi 主框架文件
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
class zhuayi
{
    /* 模块名 */
    protected $modle;

    /* 方法名 */
    protected $action;

    protected $parameter;

    protected $get;

    protected $post;

    protected $url;

    static $perf_include_count;

    public static $appname;

    public static $conf_cache = array();
    
    function __get($name)
    {
        return $this->$name = new $name();
    }

    /* 取配置 */
    public function app()
    {

        if (php_sapi_name() === 'cli')
        {
            $dirname = 'cmd';
            
        }
        else
        {
            $dirname = 'actions';   
        }
        $filename = APP_ROOT."/{$dirname}/{$this->modle}/{$this->action}.php";
        /* 加载模块文件 */
        if (!self::_includes($filename))
        {
             throw new Exception("加载{$filename}失败!!");
        } 
        $class = "{$this->modle}_{$this->action}";


        $app = new $class;

        /* 合并对象 */
        foreach ($this as $key=>$val)
        {
            $app->$key = $val;
        }

        /* cli 模式下允许循环执行,知道返回结果不为false 为止 */
        if (php_sapi_name() === 'cli')
        {
            $reset = false;
            do
            {
                $reset = $app->run();
            }
            while ($reset===false);
        }
        else
        {
            call_user_func_array(array($app,'run'),$this->parameter);
        }

    }

    /* 取配置 */
    public static function get_conf($confname = '')
    {
        if (empty($confname))
        {
            $confname = "global";
        }
        $filename = ZHUAYI_ROOT."/conf/".APP_NAME."/{$confname}.conf";
        /* 性能分析 */
        self::perf_include_count($filename);
        
        /* 加载文件 */
        return self::$conf_cache[$confname] = parse_ini_file($filename,true);
    }

    /**
     * 加载文件，失败返回false
     *
     * @param string $filename 文件路径
     */
    static function _includes($filename)
    {
        self::perf_include_count($filename);
        if (file_exists($filename))
        {
            return require $filename;
        }
        else
        {
            return false;
        }
    }

    public static function _load_class($class)
    {
        
        // 为了兼容smarty,这里判断是否有smarty前缀,如果有,则调用smarty的加载方法
        if (preg_match('/Smarty/i',$class))
        {
            return smartyAutoload($class);
        }

        $_class = explode('_',$class);
        /* 判断是本地类还是全局类 */
        if (count($_class) > 1)
        {
            $filename = APP_ROOT."/".implode('/',$_class).".class.php";
        }
        else
        {
            $filename = ZHUAYI_ROOT."/lib/{$_class[0]}/".implode('_',$_class).".class.php";
        }
       
        /* 加载文件 */
        if (!self::_includes($filename))
        {
            throw new Exception("Class '{$class}' not found", -1);
        }
    }

    /* 性能分析 */
    static function perf_include_count($filename)
    {
        if (isset($_GET['debug']))
        {
            self::$perf_include_count[] = $filename;
        }
    }

    static function perf_info()
    {
        /* 包含文件数 */
        if (isset($_GET['include_debug']))
        {
            echo "\n";
            $include_list = self::$perf_include_count;

            foreach ($include_list as $val)
            {
                echo "<!-- include:{$val} -->\n";
            }
            unset($include_list);
        }
        if (isset($_GET['db_debug']))
        {
            //echo "\n";
            $db_list = mysql::$db_base_performance_sql_count;
            $db_num = 1;
            foreach ($db_list as $key=>$val)
            {
                echo "<!--\nsql_{$db_num}: db_name_conf_key: {$val['db_name']}\n";
                echo "SQL:{$val['sql']}\nexecute_time:{$val['execute_time']}\n-->\n";
                $db_num++;
            }
            unset($db_list);
        }

        /* 占用内存 */
        if (isset($_GET['debug']))
        {
            global $pagestartime;

            $db_ex_end_time = sprintf("%0.3f",self::getmicrotime() - self::getmicrotime($pagestartime));
            $include_count = count(self::$perf_include_count);
            $memory_get_usage = sprintf('%0.5f', memory_get_usage() / 1048576 );
            echo "<!--";
            echo "页面用时: {$db_ex_end_time} 秒 ";
            echo "文件加载数: {$include_count} 个 ";
            echo "内存占用: {$memory_get_usage} MB ";
            echo "-->";
        }
    }

    static function getmicrotime($microtime='')
    {
        if (empty($microtime))
        {
            $microtime = microtime();
        }
        list($usec, $sec) = explode(" ",$microtime);
        return ((float)$usec + (float)$sec);
    }
}