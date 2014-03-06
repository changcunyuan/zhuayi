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

    public static function exception(Exception $e)
    {
        header("HTTP/1.0 500 server error");
        $strings = 'Uncaught '.get_class($e).',file: '.$e->getFile().' code: ' . $e->getCode() . "<br />Message: " . htmlentities($e->getMessage())."\n";
        self::_set_log_data($strings,'exception');
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

    public static function _set_log_data($strings,$type = '')
    {
        if ($type == 'exception')
        {
            self::$log_exception[] = $strings;
        }
        else
        {
            self::$log_data[] = $strings;
        }
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

    public static function _write_log()
    {
        if (self::$is_have_log === false)
        {
            return false;
        }
        $log_name = date("YmdHi",time() - time()%self::$log_time);
        $log_path = ZHUAYI_ROOT."/log/".APP_NAME."/";
        $log_file = $log_path.$log_name;

        /* 创建文件夹 */
        if (!is_dir($log_path))
        {
            $oldumask = umask(0);
            $reset = @mkdir($log_path,0777,true);
            if (!$reset)
            {
                die("mkdir: log_path: No such file or directory");
            }
            chmod($log_path, 0777);
        }

        if (!empty(self::$log_exception))
        {
            $exception = self::_create_uniqid().implode("\n",self::$log_exception);
            if ($_SERVER['APP']['debug'])
            {
                error_log($exception,3,$log_file.".error");
            }
            else
            {
               die($exception);
            }
        }
        if (!empty(self::$log_data))
        {
            error_log(self::_create_uniqid().implode("\n",self::$log_data)."\n",3,$log_file.".log");
        }
    }
}