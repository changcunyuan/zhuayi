<?php
class cli
{
    public static $argv;

    static function init()
    {
        self::$argv = $_SERVER['argv'];

        self::$argv = self::arg_parser();

        if (in_array('--help',self::$argv) || in_array('-h',self::$argv) || in_array('-?',self::$argv) || in_array('-help',self::$argv) || empty(self::$argv['-a']))
        {
            self::get_help();
        }
        foreach (self::$argv as $key=>$val)
        {
            if ($key != '-a' || $key != '-m')
            {
                $_GET[str_replace("-",'',$key)] = $val;
            }
        }

        return '/'.self::$argv['-m'].'/'.self::$argv['-a'];
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
            if (substr($argv[$i],0,1) == '-' )
            {
                $_ARG[$argv[$i]] = 'true';
            }
        }
        return $_ARG;
    }
}