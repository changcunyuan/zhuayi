<?php
class cli
{
    public static $argv;

    function __construct()
    {

    }

    static function init()
    {
        self::$argv = $_SERVER['argv'];

        self::$argv = self::arg_parser();

        if (in_array('--help',self::$argv) || in_array('-h',self::$argv) || in_array('-?',self::$argv) || in_array('-help',self::$argv) )
        {
            self::get_help();
        }
        $url['path'] = "/index/test";
        $_SERVER['REQUEST_URI'] = "/?".http_build_query(self::$argv);
        return $url;
    }

    static function get_help()
    {
        $cron_help = "________________________________________________________________________________________\n";
        $cron_help .= "|                                                                                      |\n";
        $cron_help .= '| -a <action>             控制器     |'."\n";
        $cron_help .= "| --help                  this help                                                    |\n";
        $cron_help .= "_______________________________________________________________________________________\n";

        echo $cron_help;
        exit;
    }

    /* 格式化 arg */
    static function arg_parser()
    {
        $argv = $_SERVER['argv'];
        unset($argv[0]);

        $_ARG = array();
        for ($i = 1; $i < $_SERVER['argc']; $i++)
        {
            if (substr($argv[$i],0,1) == '-' && isset($argv[$i+1]) && substr($argv[$i+1],0,1) != '-' )
            {
                $_ARG[$argv[$i]] = $argv[$i+1];
                continue;
            }
            if (substr($argv[$i],0,2) == '--' )
            {
                $argv[$i] = str_replace('--','',$argv[$i]);
                $_GET[$argv[$i]] = 'true';
            }

            if (substr($argv[$i],0,1) == '-' )
            {
                $_ARG[$argv[$i]] = 'true';
            }
        }
        return $_ARG;
    }
}