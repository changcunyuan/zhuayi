<?php
/**
 * mysql.class.php     Zhuayi mysql ORM
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
abstract class mysql extends zhuayi
{
    public $db_name_conf;

    public $table_name;

    private static $_instance;

    static $db_base_performance_sql_count = array();

    static $db_name = '';

    private $_connects = array();

    function __construct()
    {
        
    }

    //单例方法,用于访问实例的公共的静态方法
    public static function getInstance($config)
    {
        if(!(self::$_instance instanceof self))
        {
            self::$_instance = new self;
        }
        return self::$_instance;
    }


    /* 获取连接 */
    public function connect_string($slave = 0)
    {
        $db_conf = zhuayi::get_conf('mysql');
        if (!isset($db_conf[$this->db_name_conf]))
        {
            throw new Exception("get db_conf: ".$this->db_name_conf." : No such file or directory", -1);
        }
        $db_conf = $db_conf[$this->db_name_conf];
        if ($slave == '0')
        {
            /* 连接主库 */
            $connect['dbhost'] = "mysql:host=". $db_conf['mysql_host_m'] .";port=". $db_conf['mysql_port'] .";dbname=". $db_conf['mysql_db'] ;
        }
        else
        {
            /* 连接从库*/
            $connect['dbhost'] = "mysql:host=". $db_conf['mysql_host_s'] .";port=". $db_conf['mysql_port'] .";dbname=". $db_conf['mysql_db'] ;
        }

        self::$db_name = $db_conf['mysql_db'];
        $connect['user'] = $db_conf['mysql_user'];
        $connect['pass'] = $db_conf['mysql_pass'];
        $connect['charset'] = $db_conf['mysql_charset'];
        return $connect;
    }

    /* 连接mysql */
    public function connect($slave)
    {
        if (!isset($this->_connects[$this->db_name_conf][$slave]) || !is_object($this->_connects[$this->db_name_conf][$slave]))
        {
            $connect = $this->connect_string($slave);
            $this->_connects[$this->db_name_conf][$slave] = new PDO($connect['dbhost'],$connect['user'],$connect['pass'],array(PDO::ATTR_PERSISTENT => true));

            /* 设置报错信息*/
            $this->_connects[$this->db_name_conf][$slave]->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); 

            /* 设置编码 */
            $this->_connects[$this->db_name_conf][$slave]->exec("SET NAMES '{$connect['charset']}' ");

            if($this->version() > '5.0.1')
            {
                $this->_connects[$this->db_name_conf][$slave]->exec("SET sql_mode=''");
            }
        }
        return $this->_connects[$this->db_name_conf][$slave];
    }

    /**
     * 返回mysql版本
     *
     * @return string
     */
    function version() {
        return PDO::ATTR_CLIENT_VERSION;
    }


    public function _query($sql,$slave = 0)
    {

        /* SQL 执行时间开始 */
        $db_exe_start_time = action::getmicrotime();

        /* 检查连接是否存在 */
        $status = $this->connect($slave)->getAttribute(PDO::ATTR_SERVER_INFO);
        if ($status == 'MySQL server has gone away')
        {
            /* 关闭链接 */
            unset($this->_connects[$this->db_name_conf][$slave]);

            /* 重连数据库 */
            $this->connect($slave);
        }

        $sth = $this->connect($slave)->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($sth)
        {
            $sth->execute();
        }

        if ($_SERVER['APP']['debug'] && isset($_GET['db_debug']))
        {
            $db_ex_end_time = sprintf("%0.3f",action::getmicrotime()-$db_exe_start_time);
            self::perf_add_count($slave,$sql,$db_ex_end_time);
        }
        return $sth;
    }

    /**
     * ---------------------------------------
     * 返回自增ID
     *
     * @return int
     * ---------------------------------------
     */
    function insert_id()
    {
        return $this->connect(0)->lastInsertId();
    }

    /**
     * ----------------------------------------------------------------
     * factor 获取表字段
     *
     * @param string $where 可为数组或(id=18)类型,只有数组才会和字段对比
     * @return $this
     * ----------------------------------------------------------------
     */
    function factor()
    {
        $cache_key = __CLASS__."_{$this->db_name_conf}_{$this->table_name}";
        $ret = $this->cache->get($cache_key);
        if ($ret === false)
        {
            $query = $this->_query(" show fields from `{$this->table_name}`",1);
            $fields = $query->fetchAll(2);
            foreach ($fields as $key=>$val)
            {
                $ret["{$val['Field']}"] = "{$val['Type']}";
            }
            $this->cache->set($cache_key,$ret);
        }
        $this->_set_table_fields($ret);
        return $ret;
    }

    private function _set_table_fields($fields)
    {
        $this->_connects[$this->db_name_conf][$this->table_name] = $fields;
    }

    private function get_table_fields()
    {
        if (!isset($this->_connects[$this->db_name_conf][$this->table_name]))
        {
            $this->factor();
        }
        return $this->_connects[$this->db_name_conf][$this->table_name];
    }

    /**
     * --------------------------------
     * where 格式化参数 
     *
     * @param string $table 表明
     * @return  $this
     * --------------------------------
     */
    function parse_array($array)
    {
        /* 得到表字段 */
        $factor = $this->get_table_fields();
        if (is_array($array))
        {
            /* 去除不在表字段中 */
            $array_tmp = array();

            foreach ($array as $key=>$val)
            {
                if (!isset($factor[$key]))
                {
                    continue;
                }

                $_key = "`{$key}`";

                if (preg_match('/\{%(.*?)%\}/i',$val))
                {
                    $val = preg_replace('/\{%(.*?)%\}/i','$1',$val);
                    $array_tmp[] = $_key." like '%".$val."%'";
                }
                elseif (preg_match('/\{(.*?)\}/i',$val))
                {

                    /* 大于 {>} 小于{<}  {in}*/
                    $val = preg_replace('/\{(.*?)\}/i','$1',$val);
                    $array_tmp[] = $_key.$val."";
                }
                else
                {
                    if (!preg_match('/int\(/i',$factor[$key]))
                    {
                        $val = "'{$val}'";
                    }
                    
                    $array_tmp[] = $_key." = ".$val."";
                }
            }
            $array =  $array_tmp;
        }
        
        return $array;

    }

    /**
     * --------------------------------
     * order order 
     *
     * @param string $table 表明
     * @return  $this
     * --------------------------------
     */
    function order($order)
    {
        if (!empty($order))
        {
            $order_tmp = explode(' ', $order);
            $order_tmp = array_flip($order_tmp);
            
            if (isset($order_tmp['order']))
            {
                return $order;
            }
            
            $order_tmp = array_flip($order_tmp);
            $order_tmp = implode(' ', $order_tmp);
            
            if (strpos($order_tmp,'group by '))
            {
                return $order;
            }
            else
            {
                return " order by {$order}";
            }
        }
    }
    /**
     * --------------------------------
     * limit 格式化limit all - 不限制条数
     *
     * @param string $table 表明
     * @return  $this
     * --------------------------------
     */
    function limit($limit)
    {

        if (!empty($limit))
        {
            if (strpos('^'.$limit,'all'))
            {
                return '';
            }
            if (strpos('^'.$limit,'limit'))
            {
                return $limit;
            }
            else
            {
                $limit = explode(',',$limit);

                if (trim($limit[0]) < 0)
                {
                    $limit[0] = 0;
                }
                return " limit {$limit[0]},$limit[1]";
            }
        }
    }


    public function _select($where = '',$order = '',$limit = '',$slave = 1)
    {
        $factor = array_keys($this->get_table_fields());
        $factor = "`".implode("`,`",$factor)."`";
        $sql = " select {$factor} from `{$this->table_name}` where".implode(' and ',$this->parse_array($where)).$this->order($order).$this->limit($limit);
        return $this->_query($sql,$slave);
    }

    public function fetch($where = '',$order = '',$slave = 1)
    {
        $query = $this->_select($where,$order,'0,1',$slave);

        return $query->fetch(2);
    }

    public function fetch_all($where = '',$order = '',$limit = '',$slave = 1)
    {
        $query = $this->_select($where,$order,$limit,$slave);

        return $query->fetchAll(2);
    }

    public function insert($array)
    {
        $sql = "insert into `{$this->table_name}` set ".implode(" , ", $this->parse_array($array));
        $query = $this->_query($sql);
        
        if ($query)
        {
            return $this->insert_id();
        }
        else
        {
            return false;
        }
    }

    public function update($array,$where)
    {
        $array = $this->parse_array($array);
        $where = $this->parse_array($where);

        $sql = "update `{$this->table_name}` set ".implode(" , ", $array)." where ".implode(" and ",$where);
        $query = $this->_query($sql,0);
        if ($query)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * --------------------------------
     * delete 删除信息
     *
     * @param string $table 表名
     * @return  $this
     * --------------------------------
     */
    function delete($where)
    {
        $sql = "delete from `{$this->table_name}` where ".implode(" and ",$this->parse_array($where));
     
        $query = $this->_query($sql,0);
        if ($query)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function count($where,$slave = 1)
    {
        $sql = "select count(*) as count from `{$this->table_name}` where ".implode(" and ",$this->parse_array($where));
        $query = $this->_query($sql,$slave);
        $reset = $query->fetch(2);
        return $reset['count'];
    }

    ####################################################################################
    // 性能分析
    static function perf_add_count($slave,$sql, $time=0)
    {
        $array['sql'] = $sql;
        $array['execute_time'] = $time;
        $array['slave'] = $slave;
        $array['db_name'] = self::$db_name;
        self::$db_base_performance_sql_count[] = $array;
    }

    function __destruct()
    {
        unset($this->_connects);
    }
}