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
    private  $routing;

    public $shortopts = "app:h:help::";

    function __construct()
    {
        $shortopts  = "";
        $shortopts .= "a:";  // Required value
        $shortopts .= "h";  // Required value
         
        $longopts  = array(
            "help",     // Required value
            "app:",    // Optional value
        );
        $options = getopt($shortopts, $longopts);

        if(!isset($options['a']) && !isset($options['app'])) exit("require -a [appname]. ep: -a star\n");

        if (isset($options['a']))
        {
            $appname = $options['a'];
        }
        else
        {
            $appname =  $options['app'];
        }
      
        define('APP_ROOT', dirname(dirname(__FILE__))."/app/".$appname);
        define('APP_MODE', 'swoole');
        require_once dirname(dirname(APP_ROOT))."/core/zhuayi.php";

        $this->router = new router();

    }
  

    function __destruct()
    {
        
    }

    function init()
    {
        $serv = new swoole_server("127.0.0.1", 9501);
        $serv->set(array(
            'worker_num' => 30,   //工作进程数量
            'daemonize' => false, //是否作为守护进程
            'heartbeat_check_interval' => 3,
            'heartbeat_idle_time' => 1,
            'dispatch_mode'=>1, 
            'package_eof' => "\r\n\r\n",  //http协议就是以\r\n\r\n作为结束符的，这里也可以使用二进制内容
            'open_eof_check' => 1,
            'task_worker_num' => 15,
        ));

        $serv->on('connect', function ($serv, $fd){
            echo "Client:Connect.\n";
        });
        $serv->on('receive', function ($serv, $fd, $from_id, $data) {
            try
            {
                $reset = $this->analyze_data($serv,$data);

            } catch (Exception $e) {
                
                $reset = $e->getMessage()."({$e->getCode()})";
            }
            // $start_fd = 0;
            // while(true)
            // {
            //     $conn_list = $serv->connection_list($start_fd, 10);
            //     if($conn_list===false)
            //     {
            //         echo "finish\n";
            //         break;
            //     }
            //     $start_fd = end($conn_list);
            //     var_dump($conn_list);
            //     foreach($conn_list as $fds)
            //     {
            //         if ($fd != $fds)
            //         $serv->send($fds, "[{$fds}]:".$data."\n");
            //     }
            // }

            $serv->send($fd,$reset);
            $serv->close($fd);
        });
        $serv->on('close', function ($serv, $fd) {
            //echo "Client: Close.\n";
        });
        

        $serv->on('Task', function ($serv, $task_id, $from_id, $data) {

            // 
            // $this->router->routing()->parse_url()->app();
            $data = unserialize($data);
            $reset = '';
            if (isset($data['http']))
            {
                //ob_start();
                //
                $this->router->url = $data['http'];
                $reset = $this->router->routing()->parse_url()->app();
                //$data = ob_get_contents();
                //ob_end_flush();
            }
            //echo "New AsyncTask[id=$task_id]".PHP_EOL;
            
            // @list($appname,$request_url) = explode("/",substr($request[1],1,strlen($request[1])-1),2);

            // if (empty($appname) || empty($request_url))
            // {
            //     throw new Exception("failed to open stream : No such appname", -1);
            // }
           
            // $_SERVER['REQUEST_URI'] = $request[1];
            // //var_dump($_SERVER['REQUEST_URI']);
            // ob_start();
            //$cmd = new router();
            //$cmd->routing()->parse_url()->app();
            // //var_dump($cmd->parameter);
            // $data = ob_get_contents();
            // ob_end_flush();

            $serv->finish($reset);
        });


        $serv->on('Finish', function ($serv, $task_id, $data) {
            echo "AsyncTask[$task_id] Finish: $data".PHP_EOL;
        });

        $serv->start();
    }

    // 执行
    public function run($serv, $fd, $from_id, $data)
    {
        // 获取 header 头
        $headers = $this->get_request_headers($data);
        

        // 取请求
        $request = $this->get_request_data($headers);

        if ($request[0] == "GET")
        {
            if (strlen($request[1]) == 1)
            {
                throw new Exception("failed to open stream : No such appname", -1);
            }

            $result = $serv->taskwait(serialize($request));
            
        }
        else
        {
            die("this method is miss!!");
            throw new Exception("this method is miss!!", -1);
        }

        return $result;
    }

    //解析 request header
    public function get_request_headers($headers = '')
    {
        $headers = explode("\r\n\r\n",$headers);

        return explode("\n",$headers[0]);
    }

    public function get_request_data($headers = array())
    {
        return explode(" ", $headers[0]);
    }

    //解析包体
    public function analyze_data($serv,$data = '')
    {
        // 首先判断是否 json 数据
        $data_tmp  = json_decode(trim($data),true);

        //正常 http1.1 协议
        if (!is_array($data_tmp))
        {
            // 获取 header 头
            $headers = $this->get_request_headers($data);

            // 取请求
            $request = $this->get_request_data($headers);

            if ($request[0] == "GET")
            {
                if (strlen($request[1]) == 1)
                {
                    throw new Exception("failed to open stream : No such appname", -1);
                }

                $result = $serv->taskwait(serialize(array("http"=>$request[1])));

            }
            else
            {
                die("this method is miss!!");
                throw new Exception("this method is miss!!", -1);
            }
        }
        else
        {
            $data = $data_tmp;
            //json 包体
            var_dump($data);
        }
        
        return $result;
    }
}
