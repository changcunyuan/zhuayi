<?php
/**
* swoole.php     Zhuayi swoole 服务端
*
* @copyright    (C) 2005 - 2010  Zhuayi
* @licenes      http://www.zhuayi.net
* @lastmodify   2010-10-27
* @author       zhuayi
* @QQ           2179942
*/
if (!function_exists('pcntl_fork'))
{
    die("pcntl_fork not existing");
}

$swoole = new swoole();
$swoole->init();
class swoole
{
    public static $argv;

    function __construct()
    {
        self::$argv = self::arg_parser();
        define('APP_NAME',self::$argv['-app']);
        define('APP_ROOT', dirname(dirname(__FILE__))."/app/".APP_NAME);
        define('APP_MODE', 'cgi');
        require APP_ROOT."/../../core/zhuayi.php";
    }

    function reset_header($data)
    {
        return "/star/api/fling/list.json?fling_id=4\r\n\r\n";
        print_r($data);
        exit;
    }

    function init()
    {
        $serv = new swoole_server("127.0.0.1", 9501);
        $serv->set(array(
            'worker_num' => 1,   //工作进程数量
            'daemonize' => false, //是否作为守护进程
            'heartbeat_check_interval' => 5,
            'heartbeat_idle_time' => 10,
            'dispatch_mode'=>1, 
            'package_eof' => "\r\n\r\n",  //http协议就是以\r\n\r\n作为结束符的，这里也可以使用二进制内容
            'open_eof_check' => 1,
        ));

        $serv->on('connect', function ($serv, $fd){
            echo "Client:Connect.\n";
        });

        $serv->on('receive', function ($serv, $fd, $from_id, $data){

            $_SERVER['REQUEST_URI'] = $this->reset_header(trim($data));
            
            $content = '';
            //exec("php ".dirname(__FILE__)."/cgi.php {$data}",$data);
            $pid = pcntl_fork();

            if ($pid == 0)
            {
                echo "* Process {$_SERVER['REQUEST_URI']}:\n";
                ob_start();
                zhuayi::cil();
                $content = ob_get_contents();
                ob_end_clean();
                $serv->send($fd,$content);
                $serv->close($fd);
                pcntl_waitpid($pid,$status,WNOHANG);
                exit;
            }
            else
            {
                $pid = pcntl_wait($status, WUNTRACED);
                if (pcntl_wifexited($status))
                {
                    echo "\n* Sub process: {$pid} exited with {$status}\n";
                }
            }
            
            //     //pcntl_waitpid($pid,$status,WNOHANG);
            // }
            // else
            // {
            //     // $pid = pcntl_wait($status, WUNTRACED);
            //     // if (pcntl_wifexited($status))
            //     // {
            //     //     echo "\n* Sub process: {$pid} exited with {$status}\n";
            //     // }
            // }

        });
        $serv->on('close', function ($serv, $fd) {
            echo "Client: Close.\n";
        });
        $serv->start();
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
                $_ARG[$argv[$i]] = 'true';
            }

            if (substr($argv[$i],0,1) == '-' )
            {
                $_ARG[$argv[$i]] = 'true';
            }
        }
        return $_ARG;
    }
}