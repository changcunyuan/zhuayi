<?php
/**
 * zlog.class.php     Zhuayi log类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ			 2179942
 * zhuayi框架调用, $this->load_class('zlog');
 * 其他框架调用 $zlog = zlog::getInstance();
 * $this->zlog->trace('this is a demo');
 * $this->zlog->debug('this is a demo');
 * $this->zlog->notice('this is a demo');
 * $this->zlog->warning('this is a demo');
 * $this->zlog->fatal('this is a demo',-1234);
 **/
class zlog 
{
	private static $_instance;
	private $uniqid;

	private $level = array(
							'debug' => 'DEBUG',
							'trace' => 'TRACE',
							'notice' => 'NOTICE',
							'warning' => 'WARING',
							'fatal' => 'FATAL',
						  );
	/**
	 * 构 造 函 数
	 *
	 * @author zhuayi
	 */
	function __construct()
	{
		$this->uniqid = uniqid(php_uname('n'),true);
	}

	//创建__clone方法防止对象被复制克隆
	public function __clone()
	{
		trigger_error('Clone is not allow!',E_USER_ERROR);
	}

	//单例方法,用于访问实例的公共的静态方法
	public static function getInstance()
	{
		if(!(self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function write($msg,$errno = 0)
	{
		global $config;

		if (empty($_SERVER['LOG_PATH']))
		{
			throw new Exception("$_SERVER['LOG_PATH'] is Not defined", -1);
		}

		$debug_info = debug_backtrace();
		$debug_array['function'] = $debug_info[1]['function'];
		$debug_array['line'] = $debug_info[1]['line'];
		$debug_array['module'] = $debug_info[2]['class'];
		$debug_array['action'] = $debug_info[2]['function'];

		$run_time = sprintf("%0.1f",$_SERVER['REQUEST_TIME_FLOAT'] - $_SERVER['REQUEST_TIME']);

		if (php_sapi_name() === 'cli')
		{
			$filename = "file=".$debug_info[2]['file'];
		}
		else
		{
			$filename = "url=".$_SERVER['REQUEST_URI'];
		}

		$strings = $this->level[$debug_array['function']].": ".date("Y-m-d H:i:s")." {$config['web']['appname']} {$this->uniqid} [local_ip={$_SERVER['REMOTE_ADDR']} client_ip= ".ip::get_ip()." module={$debug_array['module']} action={$debug_array['action']} line={$debug_array['line']} run_time={$run_time}s {$filename} errno={$errno} msg={$msg}] \n";

		$write = $_SERVER['LOG_PATH']."/{$config['web']['appname']}/log.".date("YmdH");

		if (!is_dir(dirname($write)))
		{
 			$oldumask = umask(0);
			$reset = @mkdir(dirname($write).'/',0777,true);
			chmod($file_path.'/', 0777);
			if (!$reset)
			{
				throw new Exception('mkdir '.dirname($write)."is fatal", -1);
			}
		}

		/* 写入文件 */
		return file_put_contents($write,$strings,LOCK_EX);
	}

	public function debug($msg)
	{
		return $this->write($msg);
	}

	public function trace($msg)
	{
		return $this->write($msg);
	}

	public function notice($msg)
	{
		return $this->write($msg);
	}

	public function warning($msg,$errorno)
	{
		return $this->write($msg,$errorno);
	}

	public function fatal($msg,$errorno)
	{
		$this->write($msg,$errorno);
		throw new Exception("Process exit", -1);
	}
}