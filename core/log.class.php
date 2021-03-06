<?php
/**
 * log.class.php     Zhuayi log类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
class log extends zhuayi
{
    public static $log_file;

    public static $log_time = 3600;

    public static $log_data = array();
    public static $log_exception = array();

    public static $is_have_log = false;

    public static $json = false;

    function __construct()
    {

    }

    public static function exception(Exception $e)
    {
        $strings = '['.date("Y-m-d H:i:s").'] Uncaught '.get_class($e).',file: '.$e->getFile().' Line: ' . $e->getLine() . " Message: " . $e->getMessage()."\n";
        error_log($strings,3,self::_get_log_path()."error-log");
        if ($_SERVER['APP']['debug'])
        {
            if (strpos($_SERVER['HTTP_ACCEPT'],'json') !== false)
            {
                output::json($e->getCode(),$e->getMessage());
            }
            else
            {
                header("HTTP/1.0 500 server error");
                exit(print_r($e->getMessage(),true));
            }
        }
    }

    public static function notice($strings)
    {
        $strings = "[".date("Y-m-d H:i:s")." ".self::get_debugback()."] ".$strings;
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
        return self::_set_log_data($strings);

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

    /* level 1-致命错误,导致页面中断, 0-非致命错误 */
    public static function write_log()
    {
        if (self::$is_have_log)
        {
            return error_log(implode("\n", self::$log_data)."\n",3,self::_get_log_path().APP_NAME.".log");
        }
    }

    public static function _get_log_path()
    {
        $log_path = ZHUAYI_ROOT."/log/".APP_NAME."/";

        if (!is_dir($log_path))
        {
            $oldumask = umask(0);
            $reset = @mkdir($log_path,0777,true);
            if (!$reset)
            {
                die("mkdir: {$log_path}: No such file or directory");
            }
            chmod($log_path, 0777);
        }
        return $log_path;
    }
}