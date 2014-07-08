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
abstract class zhuayi
{
    /* 模块名 */
    protected $modle;

    /* 方法名 */
    protected $action;

    protected $parameter;

    protected $url;

    public static $perf_include_count;

    public static $conf_cache = array();
    

    abstract function __construct();
   
    public static function init()
    {
        $cmd = new router();
        return $cmd->routing()->parse_url()->bootstrap()->app();
    }

    public static function cil()
    {
        $cmd = new router();
        $cmd->parse_url()->app();
    }


    function __get($name)
    {
        if (!empty($name))
        {
            return $this->$name = new $name();
        }
    }

    /* 载入应用 */
    public function app()
    {
        $path = $this->parameter;
        $file = array_pop($path);
        if (php_sapi_name() === 'cli')
        {
            $actions = 'script';
        }
        else
        {
            $actions = 'actions';
        }
        $filename = APP_ROOT."/{$actions}/".implode("/",$path)."/".implode("_",$this->parameter).".php";

        define('FILENAME',$filename);
        
        /* 加载模块文件 */
        if (!self::_includes($filename))
        {
            throw new Exception("require({$filename}): failed to open stream: No such file or directory");
        }
  
        $class = implode("_",$this->parameter);
        $app = new $class;

        /* 合并对象 */
        foreach ($this as $key=>$val)
        {
            $app->$key = $val;
        }

        /* cli 模式下允许循环执行,知道返回结果不为false 为止 */
        if (php_sapi_name() === 'cli')
        {
            if (!function_exists('pcntl_fork'))
            {
                die("pcntl_fork not existing");
            }
            
            $_GET['-loop'] = intval($_GET['-loop']);
            $_GET['-loop'] = empty($_GET['-loop']) ? 0 : $_GET['-loop'];
            $loop = 0;
            do
            {
                $pid = pcntl_fork();

                if ($pid == 0)
                {
                    echo "* Loop {$loop}/{$_GET['-loop']} Process {$pid} was created, and Executed:\n";
                    call_user_func_array(array($app,'run'),$this->parameter);
                    //exit;
                }
                else
                {
                    $pid = pcntl_wait($status, WUNTRACED);
                    if (pcntl_wifexited($status))
                    {
                        //echo "\n* Sub process: {$pid} exited with {$status}\n";
                    }
                }
                $loop++;
            }
            while ($loop < $_GET['-loop']);
        }
        else
        {
            call_user_func_array(array($app,'run'),$this->parameter);
        }

    }

    /* 取配置 */
    public static function get_conf($confname = 'app')
    {
        if (!isset(self::$conf_cache[$confname]))
        {
            $filename = realpath(ZHUAYI_ROOT."/conf/".APP_NAME."/{$confname}.conf");
            /* 性能分析 */
            self::perf_include_count($filename);
            
            /* 加载文件 */
            self::$conf_cache[$confname] = parse_ini_file($filename,true);
        }
        return self::$conf_cache[$confname];
    }

    /**
     * 加载文件，失败返回false
     *
     * @param string $filename 文件路径
     */
    static function _includes($filename)
    {
        $filename = realpath($filename);
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
        if (preg_match('/Smarty_/i',$class))
        {
            return smartyAutoload($class);
        }

        $_class = explode('_',$class);
        /* 判断是本地类还是全局类 */
        if (count($_class) > 1)
        {
            //$path = "{$_class[0]}/{$_class[1]}";
            $filename = APP_ROOT."/".implode('/',$_class)."/".implode('_',$_class).".class.php";
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
    /* 加载bootstrap类 */
    public function bootstrap()
    {
        $filename = APP_ROOT."/bootstrap.php";
        if (self::_includes($filename) === false)
        {
            throw new Exception("Class bootstrap not found", -1);
        }
    
        $class_vals = get_class_methods('bootstrap');

        foreach($class_vals as $value)
        { 
            if (substr($value,0,2) == '__')
            {
                call_user_func_array(array('bootstrap',$value),array($this));
            }
        }
        return $this;
    }

    /* 性能分析 */
    static function perf_include_count($filename)
    {
        if (isset($_SERVER['APP']['debug']) && $_SERVER['APP']['debug'] && isset($_GET['debug']))
        {
            self::$perf_include_count[] = $filename;
        }
    }
}