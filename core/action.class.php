<?php
/**
 * action.class.php     Zhuayi 控制器抽象类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
abstract class action extends zhuayi
{
    function __construct()
    {
       
    }

    static function perf_info()
    {
        /* 占用内存 */
        if (isset($_GET['debug']))
        {
            global $pagestartime;

            $db_ex_end_time = sprintf("%0.3f",self::getmicrotime() - self::getmicrotime($pagestartime));
            $include_count = count(parent::$perf_include_count);
            $memory_get_usage = sprintf('%0.5f', memory_get_usage() / 1048576 );
            /* 包含文件数 */
            self::print_log("[DEBUG]\n".implode("\n",self::$perf_include_count)."\n页面用时: {$db_ex_end_time} 秒 文件加载数: {$include_count} 个 内存占用: {$memory_get_usage} MB ");
        }
    }

    static function print_log($str = '')
    {
        if (is_array($str))
        {
            $str = implode("\n",$str);
        }
        if (php_sapi_name() === 'cli')
        {
            echo "\n-----------------------------------------------------------------------\n";
            echo $str;
            echo "\n-----------------------------------------------------------------------\n";
        }
        else
        {
            echo "\n<!--\n".$str."\n-->";
        }
    }

    static function write_sql_log()
    {
        $db_list = mysql::$db_base_performance_sql_count;
        $db_num = 1;

        if (empty($db_list))
        {
            return false;
        }
        $db_str = "\nUID:".md5(time() . mt_rand(1,1000000))." (".FILENAME.")\n";
        foreach ($db_list as $key=>$val)
        {
            $db_str .= "  =>{$val['sql']}\n";
            $db_num++;
        }
        unset($db_list);
        error_log($db_str,3,log::_get_log_path()."sql_".date("Y-m-d").".log");
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

    function __destruct()
    {
        /* 写入LOG */
        log::write_log();
        self::perf_info();
        /* 写入 mysql log */
        self::write_sql_log();
        unset($this);
    }
}