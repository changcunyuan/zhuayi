<?php
/**
 * log.class.php     Zhuayi log类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
class log
{
    public static $log_file;

    public static $log_time = 3600;

    public static $log_data = array();
    public static $log_exception = array();

    public static $is_have_log = false;

    function __construct()
    {

    }
    public static function exception(Exception $e)
    {
        header("HTTP/1.0 500 server error");
        $strings = 'Uncaught '.get_class($e).',file: '.$e->getFile().' code: ' . $e->getCode() . "<br />Message: " . htmlentities($e->getMessage())."\n";
        error_log($strings,3,self::_get_log_path().".error-log");
        if ($_SERVER['APP']['debug'])
        {
            exit(print_r($e,true));
        }
    }

    public static function notice($strings)
    {
        $strings = "[LOG ".date("Y-m-d H:i:s")." ".self::get_debugback()."] ".$strings;
        return self::_set_log_data($strings);
    }

    public static function error($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno))
        {
            return;
        }

        switch ($errno) {
        case E_USER_ERROR:
            $strings = "[PHP_ERROR $errno] $errstr<br />\n";
            $strings .= "  Fatal error on line $errline in file $errfile";
            $strings .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />";
            $strings .= "Aborting...<br />\n";
            exit(1);
            break;

        case E_USER_WARNING:
            $strings = "[PHP_WARNING $errno] $errstr";
            break;

        case E_USER_NOTICE:
            $strings = "[PHP_NOTICE $errno] $errstr";
            break;

        default:
            //$strings = "Unknown error type: [$errno] $errstr {$errfile} {$errline}\n";
            return ;
            break;
        }
        return self::_set_log_data($strings,'exception');

    }

    static function get_debugback()
    {
        $debug_info = debug_backtrace();
        return "{$debug_info[2]['class']}->{$debug_info[2]['function']} line:{$debug_info[1]['line']}";
    }

    public static function _set_log_data($strings)
    {
        self::$log_data[] = $strings;
        self::$is_have_log = true;
        return true;
    }

    public static function _create_uniqid()
    {
        global $pagestartime;

        $star_time = explode(" ", $pagestartime);
        if (php_sapi_name() !== 'cli')
        {
            $log_start[] = "urlid=".crc32($_SERVER['HTTP_COOKIE'].$_SERVER['REQUEST_URI']);
            $log_start[] = "url=".$_SERVER['REQUEST_URI'];
            $log_start[] = "cookie=".$_SERVER['HTTP_COOKIE'];
        }
        $log_start[] = "date=".date("Y-m-d H:i:s",$star_time[1]);

        if (!empty($_SERVER['HTTP_REFERER']))
        {
            $log_start[] = "referer=".$_SERVER['HTTP_REFERER'];
        }
        return "<".implode(' ', $log_start).">\n";
    }

    /* level 1-致命错误,导致页面中断, 0-非致命错误 */
    public static function write_log()
    {
        if (self::$is_have_log)
        {
            var_dump(time());
            return error_log(implode("\n", self::$log_data),3,self::_get_log_path().".log");
        }
        
    }

    public static function _get_log_path()
    {
        $log_path = ZHUAYI_ROOT."/log/".APP_NAME."/".date("Ymd");
        if (!is_dir(dirname($log_path)))
        {
            $oldumask = umask(0);
            $reset = @mkdir(dirname($log_path),0777,true);
            if (!$reset)
            {
                die("mkdir: log_path: No such file or directory");
            }
            chmod($log_path, 0777);
        }
        return $log_path;
    }
}