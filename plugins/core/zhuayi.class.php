<?php
/**
 * index.php     Zhuayi 主框架文件
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ			 2179942
 */

class zhuayi
{
	var $url_debug = array(
								'error_debug',
								'api_debug',
								'db_debug',
								'cache_debug',
								'debug',
								'url_debug',
								'config_debug',
								'include_debug',
								'http_debug',
								'e_debug',
								'all_debug'
								);

	static $admin = false;
	static $perf_include_count = array();

	/* smarty 注册函数 */
	static $smarty_registerPlugin = array();

	/**
	 * 构造函数
	 */
	function __construct()
	{
		global $cache,$file,$input;

		$this->cache = & $cache;

		$this->file = & $file;

		$this->input = & $input;

	}

	function app()
	{
		global $config;

		/* 打开全部debug */
		if (isset($_GET['all_debug']))
		{
			foreach ($this->url_debug as $val)
			{
				$_GET[$val] = '1';
			}
		}

		/* 检查是否开启DEBUG模式,如果关闭则删除GET参数 */
		if ($config['debug'] === false)
		{
			foreach ($this->url_debug as $val)
			{
				if ($val!='recahce')
				unset($_GET[$val]);
			}
		}
		
		/* 过滤GET参数中的Debug参数 */
		$this->url_debug = '&'.implode('|&',$this->url_debug).'|\?'.implode('|\?',$this->url_debug);
		$this->url_debug = preg_replace("#".$this->url_debug."#",'',$_SERVER["REQUEST_URI"]);

		$controller_key = 'controller'.str_replace('index.php', '', $this->url_debug);
		

		/* URL路由处理 */
		$controller = $this->cache->get($controller_key);
		
		if ($controller === false)
		{
			$url = new z_url($this->url_debug);

			/* 开启多级域名支持 */
			$url->url_domain = $config['url_domain'];

			$controller = $url->url($config['url_config']);
			$this->cache->select_connection()->set($controller_key,$controller,$_SERVER['BAIDU_MEMCACHED_OUTTIME']);
		}

		if (isset($_GET['url_debug']))
		{
			echo "<!--\n routing:";
			echo $controller_array = print_r($controller,true);
			echo "\n-->\n";
		}
		/* 加载 APP */
		$app = $this->load_app($controller);
		
	}

	function load_app($controller)
	{
		/* add 增加相关接口支持文件夹存放 by renxin*/
		/* 判断是否接口 */

		if ($controller['modle'] == 'api')
		{
			if ($controller['admin'] == 1)
			{
				$admin = "/zhuayi/admin";
				self::$admin = true;
			}
			else
			{
				$admin = "/zhuayi/index";
			}
			$filename = ZHUAYI_ROOT.$admin."/".$controller['finder']."/".$controller['modle'].'/'.$controller['action'].".class.php";
			$class = "{$controller['action']}";
			$action = 'run';
		}
		else if ($controller['admin'])
		{
			$filename = $filename = APP_ROOT.'admin/'.$controller['modle']."/".$controller['modle']."_action.php";

			$class = $controller['modle'].'_action';
			$action = $controller['action'];

			self::$admin = true;
		}
		else
		{
			$filename = $filename = APP_ROOT.'index/'.$controller['modle']."/".$controller['modle']."_action.php";
			$class = $controller['modle'].'_action';
			$action = $controller['action'];
		}
		
		if (!self::_includes($filename))
		{
			throw new ZException("page","加载{$filename}失败!!", '-1','','',$controller['admin']);
		}

		$app =  new $class;


		/* 检查方法 */
		if (method_exists($app,$action))
		{
			$app->modle = $controller['modle'];
			$app->action = $controller['action'];
			#$app->finder = $controller['finder'];
			$app->fileds = $controller['fileds'];
			try
			{
				//ob_start('ob_gzip'); 
				call_user_func_array(array($app,$action),$app->fileds);
				//ob_end_flush(); 
			}
			catch (Exception $e)
			{
				if ($controller['modle'] == 'api')
				{
					$message = json_decode($e->getMessage(),true);

					if (!is_array($message))
					{
						$message = $e->getMessage();
					}
					
					throw new ZException('json',$message, $e->getCode(),$e->getFile(),$e->getLine(),$controller['admin']);
				}
				else
				{
					throw new ZException(
											"page",
											$e->getMessage(),
											$e->getCode(),
											$e->getFile(),
											$e->getLine(),
											$controller['admin']
											);
					
				}
			}
			
		}
		else
		{
			throw new ZException('page','没有找到《'.$controller['action'].'》方法...',-1,'','',$controller['admin']);
		}
		self::perf_info($app);

	}


	function load_class($class,$construct = false)
	{
		if (isset($this->$class) && is_object($this->$class))
		{
			return $this->$class;
		}

		$filename = $this->_include_class($class);

		if ($this->_includes($filename))
		{
			$fileds = array();

			$fileds = $this->load_config($class);

			try
			{
				$this->$class = new $class($fileds);
			}
			catch (Exception $e)
			{
				print_r($e);
			}
			if ($construct === true && !empty($fileds))
			{
	
				/* 以配置文件初始该模块 */
				foreach ($fileds as $key=>$val)
				{
					$this->$class->$key = $val;
				}
			}
			
			return $this->$class;
		}
		else
		{
			throw new ZException("page","加载插件{$class}失败", -1);
		}
	}

	static  function _load_class($class)
	{
		/* 为了兼容smarty,这里判断是否有smarty前缀,如果有,则调用smarty的加载方法 */
		if (strpos($class,'Smarty_') !== false)
		{
			smartyAutoload($class);
		}
		else
		{
			$filename = self::_include_class($class);
			if (!self::_includes($filename))
			{
				throw new ZException("page","加载{$filename}失败~~", -1);
			}
		}
		
	}

	static function _include_class($classname)
	{
		if (self::$admin == 1)
		{
			$admin = "/admin";
		}
		else
		{
			$admin = "/index";
		}
		/* 判断是否有"_",有则表示是模块类 */
		$classname = explode('_',$classname,2);
		/* api 调用 */
		if ($classname[0] == 'api')
		{
			$filename = ZHUAYI_ROOT.$admin."/api/".$classname[0]."_".$classname[1].".class.php";
		}
		/* db类加载 */
		else if ($classname[0] == 'db' && !empty($classname[1]))
		{
			$classname = explode('_',$classname[1],2);

			if (isset($classname) && !empty($classname[1]))
			{
				$classname[1] = "_".$classname[1];
			}
			else
			{
				$classname[1] = '';
			}
			$filename = ZHUAYI_ROOT.'/zhuayi'.$admin.'/'.$classname[0]."/db/db_".$classname[0].$classname[1].".class.php";
		}

		/* modle 类加载 */
		else if ($classname[0] == 'mod' && !empty($classname[1]))
		{
			$classname = explode('_',$classname[1],2);
			
			if (isset($classname) && !empty($classname[1]))
			{
				$classname[1] = "_".$classname[1];
			}
			else
			{
				$classname[1] = '';
			}
			
			$filename = ZHUAYI_ROOT.'/zhuayi'.$admin.'/'.$classname[0]."/mod/mod_".$classname[0].$classname[1].".class.php";
		}
		/* 后台调用action */
		else if (self::$admin && !empty($classname[1]))
		{
			$filename = APP_ROOT.'admin/'.$classname[0]."/".$classname[0]."_".$classname[1].".php";
		}
		else if (!empty($classname[1]))
		{
			$filename = APP_ROOT.$classname[0]."/".$classname[0]."_".$classname[1].".php";
		}
		else
		{
			$filename = PLUGINS_ROOT.$classname[0]."/".$classname[0].".class.php";
		}
		
		return $filename;
	}
	/**
	 * 加载文件，失败返回false
	 *
	 * @param string $filename 文件路径
	 */
	static function _includes($filename)
	{
		/* 加载前判断是否已经加载过了,如果加载过,则不用加载 */
		if (!in_array($filename,self::$perf_include_count))
		{
			self::perf_include_count($filename);
		}
		if (file_exists($filename))
		{
			return require_once $filename;
		}
		else
		{
			return false;
		}
	}

	/**
	 * 载入配置文件
	 *
	 * @param string $config 配置文件名
	 * @param string $sae 是否sae支持
	 */
	function load_config($config)
	{
		if (strpos($config,'action'))
		{
			return false;
		}
		
		$config_key = 'config_'.$config;

		$cache_config = $this->cache->get($config_key);

		if ($cache_config === false)
		{
			
			/* 以"_"为种子转换数组, 判断是模型还是系统*/
			$config = explode('_',$config);

			if (empty($config[1]))
			{
				$cache_config = PLUGINS_ROOT.$config[0].'/config/'.$config[0].'.config.php';
			}
			else
			{
				$cache_config = APP_ROOT.$config[0].'/config/'.$config[0].'.config.php';
			}

			$cache_config = $this->_includes($cache_config);
			
			if (!empty($cache_config))
			{
				$this->cache->set($config_key,$cache_config,86400);
			}
		}
	
		return $cache_config;

	}

	/**
	 * include_tpl 引用模板
	 *
	 * @return void
	 * @author 
	 **/
	function load_tpl($filename)
	{

		$arrfile = explode('_',$filename);

		if (self::$admin === true)
		{
			$admin = "admin/";
		}
		else
		{
			$admin = "index/";
		}
		if ($arrfile[0] == 'api')
		{
			$filename = ZHUAYI_ROOT.'/zhuayi/'.$admin.$finder.'/template/'.implode('_',$arrfile).'.html';
		}
		else if (!empty($arrfile[1]))
		{
			$filename = ZHUAYI_ROOT."/zhuayi/".$admin.$arrfile[0].'/template/'.implode('_',$arrfile).'.html';
		}

		return $filename;
		
	}

	/**
	 * load_modle 加载模块
	 *
	 * @return void
	 * @author 
	 **/
	function load_modle($filename,$fileds='')
	{
		$filename = explode('_',$filename,2);
		
		//$filename[0] .= '_action';
		$filename[0] = 'mod_'.$filename[0];

		if (is_callable(array($filename[0],$filename[1]),true,$callback))
		{
			if (isset($this->variable))
			{
				extract($this->variable, EXTR_OVERWRITE);
			}
			/* 由于php5.2以下不支持 $filename[0]::$filename[1]()调用模式,废弃,改用eval;*/
			eval("{$filename[0]}::{$filename[1]}(\$show,\$fileds);");
		}
		else
		{
			throw new ZException('page','加载模块 '.$filename[0].':'.$filename[1].' 失败!', -1);
		}

	}

	function fetch($param = '',$filename = '',$is_filename = '')
	{
		ob_start();
		$this->display($param,$filename,$is_filename);
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * 载入模版 
	 * 
	 *
	 * @param string $filename 模版名称
	 *
	*/
	function display($param = '',$filename = '',$is_filename = '')
	{

		//$is_filename = $filename;
		$this->variable['show'] = $param;

		extract($this->variable, EXTR_OVERWRITE);

		if (empty($filename) || $filename == 'smarty')
		{
			if ($filename == 'smarty')
			{
				$is_filename = $filename;
			}
			$filename = $this->modle.'_'.$this->action;
		}

		$filename = self::load_tpl($filename);
		
		if ($is_filename == 'smarty')
		{
			global $config;
			$smarty = new Smarty;
			$smarty->setCompileDir($_SERVER['ZHUAYI_CACHE_DIR']);
			$smarty->left_delimiter = '{%';
			$smarty->right_delimiter = '%}';
			$smarty->assign('config',$config);
			$smarty->assign('ZHUAYI_ROOT',ZHUAYI_ROOT);
			$smarty->assign('show',$this->variable['show']);

			if (!empty(self::$smarty_registerPlugin))
			{
				foreach (self::$smarty_registerPlugin as $val)
				{
					$smarty->registerPlugin($val[0],$val[1],$val[2]);
				}
			}
			
			$smarty->display($filename);
		}
		else
		{
			if (file_exists($filename))
			{
				require $filename;
			}
			else
			{
				throw new ZException('page',"加载模板{$filename}失败", 1);
			}
		}
		
		
		
	}

	/**
	 * 函数调用
	 * @param string $fun_name 函数名 
	 */
	function load_fun($fun_name)
	{
		/* 获取全部参数 */
		$fun = func_get_args();	
		$fun_name = $fun[0];
		unset($fun[0]);
		
		/* 载入函数文件 */
		$this->_includes(ZHUAYI_ROOT.'/function/'.$fun_name.'.php');
		
		if (function_exists($fun_name))
		{
			return call_user_func_array($fun_name,$fun);
		}
		else
		{
			throw new ZException('page',"调用函数{$fun_name}", 1);
			
		}
	}

	/* 性能分析 */
	static function perf_info()
	{
		global $pagestartime;
		
		if (isset($_GET['db_debug']))
		{
			//echo "\n";
			$db_list = db::$db_base_performance_sql_count;
			$db_num = 1;
			foreach ($db_list as $key=>$val)
			{
				if ($val['slave'] == 1)
				{
					$val['slave'] = "slave_s IP:".$val['mysql_host_s']." db_name_conf_key: ".$val['db_name'];
					
				}
				else
				{
					$val['slave'] = "slave_m IP:".$val['mysql_host_m']." db_name_conf_key: ".$val['db_name'];
				}
				echo "<!--\nsql_{$db_num}:{$val['slave']}\n";
				echo "SQL:{$val['sql']}\nexecute_time:{$val['execute_time']}\n-->\n";
				$db_num++;
			}
			unset($db_list);
		}

		if (isset($_GET['include_debug']))
		{
			echo "\n";
			$include_list = self::$perf_include_count;

			foreach ($include_list as $val)
			{
				echo "<!-- include:{$val} -->\n\n";
			}
			unset($include_list);
		}

		if (isset($_GET['debug']))
		{
			$db_ex_end_time = sprintf("%0.3f",self::getmicrotime() - self::getmicrotime($pagestartime));
			$include_count = count(self::$perf_include_count);

			$db_count = count(db::$db_base_performance_sql_count);
			$memory_get_usage = sprintf('%0.5f', memory_get_usage() / 1048576 );
			echo "<!--";
			echo "页面用时: {$db_ex_end_time} 秒 ";
			echo "文件加载数: {$include_count} 个 ";
			echo "DB执行: {$db_count} 个查询 ";
			echo "内存占用: {$memory_get_usage} MB ";
			echo "-->";
		}
	}

	static function perf_include_count($filename)
	{
		self::$perf_include_count[] = $filename;
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
}

class ZException extends Exception
{

    public function __construct($type,$message, $code = 0,$file='',$line='',$is_admin = true)
    {
    	global $config;
    	
    	zhuayi::perf_info();
       	parent::__construct($code);
        switch ($type)
        {
        	case 'json':
        		if (!empty($_GET['callback']))
        		{
        			echo output::jsonp($code,$message,$_GET['callback']);
        		}
        		else
        		{
        			echo output::json($code,$message,$line,$file);
        		}
        		break;
        	case 'array':
        		print_r(output::arrays($code,$message));
        		break;
        	case 'page':
        		if (empty($file))
        		{
        			$file = $this->getFile();
        		}
        		if (empty($line))
        		{
        			$line = $this->getLine();
        		}
        		// output::error($message,$file,$line,$code);;
        		// exit;
        		if ($is_admin === false && !isset($_GET['error_debug']))
        		{
        			output::url($config['web']['error_url']);
        		}
        		else
        		{
        			if (isset($_GET['error_debug']))
        			{
        				echo '<pre>';
        				print_r($this);
        				echo '</pre>';
        			}
        			output::error($message,$file,$line,$code);
        			exit;
        		}
        		
        	default:
        		break;
        }
    }
    
}
?>
