<?php
/**
 * index.php     Zhuayi DB抽象类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ			 2179942
 * 
 * ------------------------------------------------
 * $this->load_class('db');
 * 
 * // 查询单条记录
 * $reset = $this->db->fetch('admin');
 * 
 * // 查询多条记录,默认为30条
 * $reset = $this->db->fetch_row('admin');
 * 
 * // 带判断条件查询多条
 * $this->db->fetch_row('admin',array('id'=>'1'),' id desc',' 0,30');
 * 
 * // 链式调用方法,查询单条
 * $reset = $this->db->table('admin')->show();
 * 
 * // 链式调用查询多条,并指定字段
 * $reset = $this->db->table(array('article','keyid , id , title '))->limit('0,30')->order(' id desc ')->show();
 *
 * // 链式调用查询多条,带复杂判断
 * $reset = $this->db->table('article')->limit('0,11')->where('id > 100')->show();
 *
 * // 链式调用: 新增数据
 * $reset = $this->db->table('admin')->add(array('password'=>'2','username'=>'zhuayi'));
 *
 * // 新增数据
 * $reset = $this->db->insert('admin',array('password'=>'2','username'=>'zhuayi'));
 *
 * // 链式调用: 编辑数据
 * $reset = $this->db->table('admin')->where(array('id'=>'3'))->edit(array('password'=>'3','username'=>'zhuayi'));
 *
 * // 链式调用: 编辑数据,自定义判断挑件
 * $reset = $this->db->table('admin')->where('id = 2 ')->edit(array('password'=>'{+}3','username'=>'zhuayi'));
 *
 * // 链式调用: 查询总数
 * $reset = $this->db->table('admin')->where(array('id'=>'3'))->maxnum();
 *
 * // 查询总数
 * $reset = $this->db->maxnum('admin',array('id'=>'3'));
 * //编辑数据
 * $reset = $this->db->update('admin',array('password'=>time(),'username'=>'zhuayi'),array('id'=>'3'))
 *
 * // 链式调用: 删除数据
 * $reset = $this->db->table('admin')->where(array('id'=>'3'))->del();
 *
 * // 删除数据
 * $reset = $this->db->delete('admin',' id > 3');
 *
 * -------------------------------------------------
 */
$db_base_performance_sql_count = array();
class db
{

	static $querynum = 0;

	static $querytime = 0;

	static $db_base_performance_sql_count = array();

	/* 防止同一个查表字段执行N次 */
	static $get_fields_count = array();

	/* 分表数组 */
	static $sub_table_list = array();

	static $db_config_key = 'default';

	static $db_select = false;

	/* 记录当前选择的数据库 */
	public $before_db_name ;

	/* 记录上一个选择的数据库 */
	public $laset_db_name;

	public $objects = array();


	/**
	 * 构造函数
	 * @param find $fields 数据库配置文件 
	 */
	function __construct($fields = array())
	{
		global $cache;

		extract($fields, EXTR_OVERWRITE);
	
		foreach ($fields as $key=>$val)
		{
			$this->db_config[$key] = $val;
		}

		/* 加载缓存类 */
		$this->cache = & $cache;
	}

	/**
	 * --------------------------------
	 * link 链接数据库
	 *
	 * @param string $table 表明
	 * @return	$this
	 * --------------------------------
	 */
	function link($slave)
	{
		if ($slave == '0')
		{
			/* 连接主库 */
			$dbhost="mysql:host=". $this->mysql_host_m .";port=". $this->mysql_port .";dbname=". $this->mysql_db ;
		}
		else
		{
			/* 连接从库*/
			$dbhost="mysql:host=". $this->mysql_host_s .";port=". $this->mysql_port .";dbname=". $this->mysql_db ;
		}
		// echo "====================\n";
		// var_dump($dbhost);
		// echo "====================\n";
		try 
		{
			if ($slave == 0)
			{
				$this->link_master = new PDO($dbhost,$this->mysql_user,$this->mysql_pass);
				$this->link_master->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); 
				/* 设置编码 */
				if($this->mysql_charset)
				{
					$this->link_master->exec("SET NAMES '{$this->mysql_charset}' ");
				}

				if($this->version() > '5.0.1')
				{
					$this->$link_name->exec("SET sql_mode=''");
				}
				$this->objects[$this->laset_db_name]['master'] = $this->link_master;
			}
			else
			{
				$this->link_slave = new PDO($dbhost,$this->mysql_user,$this->mysql_pass);
				$this->link_slave->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				/* 设置编码 */
				if($this->mysql_charset)
				{
					$this->link_slave->exec("SET NAMES '{$this->mysql_charset}' ");
				}

				if($this->version() > '5.0.1')
				{
					$this->link->exec("SET sql_mode=''");
				}
				$this->objects[$this->laset_db_name]['slave'] = $this->link_slave;
				
			}
			$this->objects[$this->laset_db_name]['db_name'] = $this->laset_db_name;
			$this->objects[$this->laset_db_name]['host'] = $dbhost;
		}
		catch (PDOException $e) 
		{
			throw new Exception($e->getMessage(), -1);
		}

	}

	/**
	 * 返回mysql版本
	 *
	 * @return string
	 */
	function version() {
		return PDO::ATTR_CLIENT_VERSION;
	}

	/**
	 * --------------------------------
	 * fetch 查询单条信息
	 *
	 * @param string $table 表明
	 * @return	$this
	 * --------------------------------
	 */
	function fetch($table,$where = '',$order = '')
	{
		$where = $this->where($table,$where);
		$table = $this->table($table,'select');
		$order = $this->order($order);
		/* 缓存 */
		$sql = "{$table['sql']} {$where} {$order} ";
		$query = $this->_query($sql,1);

		return $this->_fetch_row($query);
	}

	/**
	 * --------------------------------
	 * delete 删除信息
	 *
	 * @param string $table 表明
	 * @return	$this
	 * --------------------------------
	 */
	function delete($table,$where)
	{
		$log_table = $table;
		$log_where = $where;
		$table = $this->table($table,'delete');
		$where = $this->where($table['table'],$where);

		$sql = "{$table['sql']} {$where}";

		$before_content = self::fetch_row($log_table,$log_where);
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
	 * insert 插入信息
	 *
	 * @param string $table 表明
	 * @return	$this
	 * --------------------------------
	 */
	function insert($table,$fields,$log = true)
	{
		$table = $this->table($table,'insert');
		$fields = $this->fields($table['table'],$fields);

		$fields_key = array_keys($fields);
		$fields_key = implode(',', $fields_key);

		$fields_val = array_values($fields);
		$fields_val = "'".implode("','", $fields_val)."'";

		$sql = "{$table['sql']}({$fields_key}) values({$fields_val})";
		$query = $this->_query($sql,0);
		if ($query)
		{
			$insert_id = $this->insert_id();
			return $insert_id;
		}
		else
		{
			return false;
		}
	}

	/**
	 * --------------------------------
	 * insert 插入信息
	 *
	 * @param string $table 表明
	 * @return	$this
	 * --------------------------------
	 */
	function replace_into($table,$fields,$log = true)
	{
		$table = $this->table($table,'replace_into');
		$fields = $this->fields($table['table'],$fields);

		$fields_key = array_keys($fields);
		$fields_key = implode(',', $fields_key);

		$fields_val = array_values($fields);
		$fields_val = "'".implode("','", $fields_val)."'";

		$sql = "{$table['sql']}({$fields_key}) values({$fields_val})";
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
	 * update 更新信息
	 *
	 * @param string $table 表明
	 * @return	$this
	 * --------------------------------
	 */
	function update($table,$fields,$where,$log = true,$json_data = false)
	{
		$log_fields = $fields;
		$log_table = $table;
		$log_where = $where;
		$table = $this->table($table,'update');

		$factor = $this->fields($table['table'],$fields);

		$where = $this->where($table['table'],$where);

		$update_temp = array();

		if (is_array($factor))
		{

			
			foreach ($factor as $key=>$val)
			{
				if ($json_data === false)
				{
					if (is_array($val))
					{
						$update_temp[] = $key." = '".serialize($val)."'";
					}
					elseif (preg_match('/\{%(.*?)%\}/i',$val))
					{
						$val = preg_replace('/\{%(.*?)%\}/i','$1',$val);
						$update_temp[] = $key." like '%".$val."%'";
					}
					elseif (preg_match('/\{(.*?)\}/i',$val))
					{

						/* 大于 {>} 小于{<}  {in}*/
						$val = preg_replace('/\{(.*?)\}/i','$1',$val);
						$update_temp[] = $key." = ".$key.$val;
					}
					else
					{
						$update_temp[] = $key." = '".$val."'";
					}
				}
				else
				{
					if (is_array($val))
					{
						$update_temp[] = $key." = '".serialize($val)."'";
					}
					else
					{
						$update_temp[] = $key." = '".$val."'";
					}
					
				}
			}
		}
			
		$factor = implode(',',$update_temp);
	
		
		$sql = "{$table['sql']} {$factor} {$where}";
		if ($log === true)
		{
			$before_content = self::fetch($log_table,$log_where);
		}
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
	 * ---------------------------------------
	 * 返回自增ID
	 *
	 * @return int
	 * ---------------------------------------
	 */
	function insert_id()
	{
		return $this->objects[$this->laset_db_name]['master']->lastInsertId();
	}

	/**
	 * --------------------------------
	 * fetch_row 返回多条信息
	 *
	 * @param string $table 表明
	 * @return	$this
	 * --------------------------------
	 */
	function fetch_row($table,$where = '',$order = '',$limit = ' 0,30 ',$group_by='')
	{
		$where = $this->where($table,$where);
		$table = $this->table($table,'select');

		$order = $this->order($order);
		$limit = $this->limit($limit);

		/* 缓存组 */
		$sql = "{$table['sql']} {$where} {$this->order($order)} {$this->limit($limit)}";
		$query = $this->_query($sql,1);
		return $this->_fetch_array($query);
	}

	/**
	 * --------------------------------
	 * count 返回信息总数
	 *
	 * @param string $table 表明
	 * @return	$this
	 * --------------------------------
	 */
	function count($table,$where,$slave = 1)
	{
		$where = $this->where($table,$where);
		$table = $this->table($table,'count');
		
		/* 缓存组 */
		$sql = "{$table['sql']}{$where}";
		$query = $this->_query($sql,$slave);
		$reset =  $this->_fetch_row($query);
		return $reset['count'];
	}

	/**
	 * --------------------------------
	 * table 格式化表名,返回SQL和表名
	 *
	 * @param string $table 表明
	 * @return	$this
	 * --------------------------------
	 */
	function table($table,$action = 'select')
	{
		$factor = $this->factor($table);

		$table = $factor['table'];
		$factor = implode(",",$factor['fields']);
		switch ($action)
		{
			case 'select':
				if (!is_array($table))
				{
					$reset['sql'] = " select {$factor} from `{$table}`" ;
					$reset['table'] = trim($table) ;
				}
				else
				{
					$table  = array_values($table);
					$reset['sql'] = " select {$table[1]} from `{$table[0]}`";
					$reset['table'] = trim($table[0]);
				}
				break;
			case 'count':
				$reset['sql'] = " select count(*) as count from `{$table}`";	
				$reset['table'] = trim($table) ;
				break;
			case 'update':
				$reset['sql'] = " update `{$table}` set ";
				$reset['table'] = trim($table) ;
				break;
			case 'insert':
				$reset['sql'] = " insert into `{$table}` ";
				$reset['table'] = trim($table) ;
				break;
			case 'replace_into':
				$reset['sql'] = " replace into `{$table}` ";
				$reset['table'] = trim($table) ;
				break;
			case 'delete':
				$reset['sql'] = " delete from `{$table}` ";
				$reset['table'] = trim($table) ;
				break;
			default:
				# code...
				break;
		}

		return $reset;
		

	}

	/**
	 * --------------------------------
	 * limit 格式化limit all - 不限制条数
	 *
	 * @param string $table 表明
	 * @return	$this
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

	/**
	 * --------------------------------
	 * order order 
	 *
	 * @param string $table 表明
	 * @return	$this
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
	 * fields 格式化参数 
	 *
	 * @param string $table 表明
	 * @return	$this
	 * --------------------------------
	 */
	function fields($table,$where)
	{
		$factor = $this->factor($table);

		if (is_array($where))
		{
			$fields = array();
			foreach ($where as $key=>$val)
			{
				$key = trim($key);
				$key = "`{$key}`";
				if (isset($factor['fields'][$key]))
				{
					$fields[$key] = $val;
				}
			}
			return $fields;
		}
		else
		{
			return $where;
		}
		
		
	}

	/**
	 * --------------------------------
	 * where 格式化参数 
	 *
	 * @param string $table 表明
	 * @return	$this
	 * --------------------------------
	 */
	function where($table,$where)
	{
		/* 得到表字段 */
		$factor = $this->fields($table,$where);

		if (is_array($where))
		{
			/* 去除不在表字段中 */
			$where_tmp = array();

			foreach ($factor as $key=>$val)
			{
				if (preg_match('/\{%(.*?)%\}/i',$val))
				{
					$val = preg_replace('/\{%(.*?)%\}/i','$1',$val);
					$where_tmp[] = $key." like '%".$val."%'";
				}
				elseif (preg_match('/\{(.*?)\}/i',$val))
				{

					/* 大于 {>} 小于{<}  {in}*/
					$val = preg_replace('/\{(.*?)\}/i','$1',$val);
					$where_tmp[] = $key.$val."";
				}
				else
				{
					$where_tmp[] = $key." = '".$val."'";
				}
			}
			$where =  implode(' and ',$where_tmp);
		}
		
		if (!empty($where))
		{
			return " where {$where}";
		}

	}

	/**
	 * ----------------------------------------------------------------
	 * table_exists 检查表是否存在
	 *
	 * @param string $where 可为数组或(id=18)类型,只有数组才会和字段对比
	 * @return $this
	 * ----------------------------------------------------------------
	 */
	function table_exists($table)
	{
		$sql = "show tables like '".T."{$table}'";;
		return $this->_fetch_row($this->_query($sql));

	}

	/**
	 * ----------------------------------------------------------------
	 * sub_table 分表操作
	 *
	 * @param string $where 可为数组或(id=18)类型,只有数组才会和字段对比
	 * @return $this
	 * ----------------------------------------------------------------
	 */
	function sub_table($original_table,$new_table)
	{
		/* 检查表是否存在 */
		if ($this->table_exists($new_table) === false)
		{
			$sql = "show create table ".T."{$original_table}";
			$reset = $this->_fetch_row($this->_query($sql));
			$reset['create_sql'] = $reset['Create Table'];

			/* 替换新表SQL */
			$reset['create_sql'] = preg_replace('/CREATE TABLE `(.*?)`/i', "CREATE TABLE `".T."{$new_table}`", $reset['create_sql']);
			
			/* 创建新表 */
			$sql = $reset['create_sql'];
			if ($this->_query($sql))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		return true;
		
	}
	/**
	 * ----------------------------------------------------------------
	 * factor 获取表字段
	 *
	 * @param string $where 可为数组或(id=18)类型,只有数组才会和字段对比
	 * @return $this
	 * ----------------------------------------------------------------
	 */
	function factor($table)
	{
		if (is_array($table))
		{
			$table = $table[0];
			$this->tablename = $table;
		}
		/* 检查是否需要分表 */
		else
		{
			$this->tablename = $table;
			/* 如果存在 admin_%Y-m-d% 数据,则进行分表操作 */
			if (strpos($table,'%') !== false)
			{
				if (isset(self::$sub_table_list[$table]))
				{
					$table = self::$sub_table_list[$table];
				}
				else
				{
					$_original_table = $table;
					preg_match('/^(.*?)-%(.*?)%$/i',$table,$sub_list);

					$original_table = $sub_list[1];
					/* 取按日期分出的表 */
					$new_table = $original_table.'-'.date($sub_list[2]);
					
					if ($this->sub_table($original_table,$new_table) !== true)
					{
						throw new Exception("分表{$new_table}失败!", -1);
					}
					else
					{
						$table = $new_table;
					}

					/* 写入全局分表静态数组中,用来避免多次查询 */
					self::$sub_table_list[$_original_table] = $new_table;	
				}
			}
		}

		$table = trim($table);

		if (isset(self::$get_fields_count[$table]))
		{
			return self::$get_fields_count[$table];
		}

		$cache_key = "table_{$this->laset_db_name}_{$table}";
		$reset = $this->cache->get($cache_key);
		
		if ($reset === false)
		{
			$query = $this->_query(" show fields from `{$table}`",1);

			$fields = $this->_fetch_array($query);

			foreach ($fields as $key=>$val)
			{
				$reset['fields']["`{$val['Field']}`"] = "`{$val['Field']}`";
			}
			$reset['table'] = $table;
			$this->cache->select_connection()->set($cache_key,$reset,$this->mysql_cache_outtime);
		}
		self::$get_fields_count[$table] = $reset;
		
		return $reset;
	}

	/* 取单条信息 */
	function _fetch_row($query)
	{
		if ($query)
		{
			return $query->fetch(2);
		}
		return false;
	}

	/* 取多条信息 */
	function _fetch_array($query)
	{
		if ($query)
		{
			return $query->fetchAll(2);
		}
		else
		{
			return false;
		}
		
	}

	/* 执行SQL语句 */
	function _query($sql,$slave=0)
	{

		if ($slave == 0)
		{
			$slave_name = 'master';
		}
		else
		{
			$slave_name = 'slave';
		}

		/* SQL 执行时间开始 */
		$db_exe_start_time = zhuayi::getmicrotime();

		/* 为了向前兼容,这里增加如果没有选择数据库,则使用默认数据库配置 */
		if (empty($this->mysql_db))
		{
			$this->select_db();
		}

		// if ($slave == 0)
		// {
		// 	//$object = &$this->link_master;
		// 	$object = $this->objects[$this->laset_db_name]['master'];
		// }
		// else
		// {
		// 	//$object = &$this->link_slave;
		// 	$object = $this->objects[$this->laset_db_name]['slave'];
		// }

		// echo "===========================\n";
		// var_dump($this->before_db_name);
		// var_dump($this->laset_db_name);
		// echo "===========================\n";
		/* 增加多域多库时需要判断两个库一样不,如果不一样重新实例化 */
		//if (!isset($object) || ($this->before_db_name != $this->laset_db_name))
		// var_dump($this->objects);
		// exit;
		// var_dump(isset($this->objects[$this->laset_db_name]));
		// exit;

		// var_dump($this->objects);
		$object = isset($this->objects[$this->laset_db_name])?$this->objects[$this->laset_db_name]:array();

		if (!isset($object) || !isset($this->objects[$this->laset_db_name][$slave_name]))
		{

			//$this->laset_db_name = $this->before_db_name;
			//$this->before_db_name = $this->laset_db_name;
			$this->link($slave);

			//$object = $this->objects[$this->laset_db_name];
		}


		// if ($slave == 0)
		// {
		// 	$object = $this->objects[$this->laset_db_name]['master'];
		// }
		// else
		// {
		// 	$object = $this->objects[$this->laset_db_name]['slave'];
		// }
		$object = $this->objects[$this->laset_db_name][$slave_name];

		/* 检查是否还在链接中 */
		$status = $object->getAttribute(PDO::ATTR_SERVER_INFO);

		if (isset($_GET['db_debug']))
		{
			var_dump($status);
			var_dump($slave_name);
			$status = 'MySQL server has gone away';
		}
		

		if ($status == 'MySQL server has gone away')
		{
			/* 关闭链接 */
			unset($this->objects[$this->laset_db_name][$slave_name]);
			//$object->pgsqlLOBUnlink();

			/* 重连数据库 */
			$this->link($slave);

			$object = $this->objects[$this->laset_db_name][$slave_name];

			// if ($slave == 0)
			// {
			// 	$object = $this->objects[$this->laset_db_name]['master'];
			// }
			// else
			// {
			// 	$object = $this->objects[$this->laset_db_name]['slave'];
			// }
		}

		/* e_debug */
		$this->explain_debug_echo($object,$sql);

		
		// exit;
		// var_dump($this->objects[$this->laset_db_name]);
		// var_dump($sql);
		$sth = $object->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		if ($sth)
		{
			try
			{
				$sth->execute();
			}
			catch (Exception $e)
			{
				$this->error($e->errorInfo);
			}
		}
		
		if (isset($_GET['db_debug']))
		{
			$db_ex_end_time = sprintf("%0.3f",zhuayi::getmicrotime()-$db_exe_start_time);
			$this->perf_add_count($slave,$sql,$db_ex_end_time,$this->laset_db_name);
		}

		return $sth;

	}


	/* 选择数据库 */
	function select_db($db_config_key = 'default')
	{
		if (empty($db_config_key) || !isset($this->db_config[$db_config_key]))
		{
			throw new Exception("数据库配置出错!", -1);
		}

		/* 有可能一个页面需要查询多个库,所以这里记录下来两次不同的库,用来判断是否需要重新实例化DB */
		$this->laset_db_name = $db_config_key;
		// if (empty($this->laset_db_name))
		// {
			
		// }
		// else
		// {
		// 	if (!empty($this->before_db_name))
		// 	{
		// 		var_dump($this->before_db_name);
		// 		var_dump($this->laset_db_name);
		// 		echo ';';
		// 	}
		// 	$this->before_db_name = $db_config_key;
			
		// }
		
		foreach ($this->db_config[$db_config_key] as $key=>$val)
		{
			$this->$key = $val;
		}
		return $this;

	}

	function error($errorInfo)
	{
		throw new Exception("DB出错::{$errorInfo[1]} {$errorInfo[2]}", -1);
	}

####################################################################################
// 性能分析
	function perf_add_count($slave,$sql, $time=0,$db_name)
	{
		$array['sql'] = $sql;
		$array['execute_time'] = $time;
		$array['slave'] = $slave;
		$array['db_name'] = $db_name;
		$array['mysql_host_m'] = $this->mysql_host_m;
		$array['mysql_host_s'] = $this->mysql_host_s;
		self::$db_base_performance_sql_count[] = $array;
	}

	function explain_debug_echo($object,$sql)
	{
		if(isset($_GET["e_debug"]) && strpos($sql,'show')=== false && strpos($sql,'update')=== false && strpos($sql,'insert')=== false && strpos($sql,'delete')=== false)
		{
			$explain_sql  = "explain ". $sql;
			
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			echo  "<!-- explain::get_listcount:() {$explain_sql} -->\n";
			$sth=$object->prepare($explain_sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$res=$sth->execute();
			$lists=$sth->fetchAll(2);
			echo "<table  style='float:left' width=100% border=1><td style='width:20px'></td><td colspan=9><b>{$sql}</b></td><tr>";
			foreach($lists[0] as $key => $value)
			{
				echo "<td>{$key}</td>";
				 
			}
			echo "</tr>";
			foreach($lists as $k=> $v)
			{
				"<tr>";
			
				foreach($v as $key => $value)
				{
					if ($key == "type" || $key == "Extra")
					{
						echo "<td style='background-color:red'>{$value}</td>";
					}
					else
					{
						echo "<td>{$value}</td>";
					}
					 
				}
			
				echo "</tr>";
				
			}	
			
			echo "</table><br>&nbsp;<br><br/>";
			
		}
	}
}