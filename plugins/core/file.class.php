<?php

/**
 *  文件操作类
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 * <code>
 * $this->load_class('file',true);
 * //遍历目录
 * $reset = $this->file->filelist('zhuayi',true,true);
 * //写入文件
 * $reset = $this->file->write('1.html',time());
 * //删除文件或目录
 * $reset = $this->file->delete('test',true);
 * </code>
 */

 class file
 {

 	function __construct($fields = array())
	{
		if (is_array($fields))
		{
			foreach ($fields as $key=>$val)
			{
				$this->$key = $val;
			}

			if (isset($this->server))
			{
				$this->servers = new $this->server();
			}
		}
		
	}
 	/**
 	 * file_path 转换路径
 	 *
 	 * @return void
 	 * @author 
 	 *
 	 */
 	private function file_path($filename,$domain='')
 	{
 		if (empty($domain))
 		{
 			$domain = $this->path[array_rand($this->path)];
 		}
 		/* 判断$domain url 最后一个是不是"/",如果不是,则加上"/" */
 		if (substr($domain['url'],-1,1) != '/')
 		{
 			$domain['url'] .= '/';
 		}

 		/* 判断$domain url 最后一个是不是"/",如果不是,则加上"/" */

 		if (substr($domain['root'],-1,1) != '/')
 		{
 			$domain['root'] .= '/';
 		}

 		if (strpos($filename,$domain['root']) === false)
 		{
 			/* 去掉domain filename 左侧第一个"/" */ 
	 		if (substr($filename,0,1) == '/')
	 		{
	 			$filename = substr($filename,1,strlen($filename)-1);
	 		}

 			$filename = $domain['root'].$filename;
 		}
 		if (!preg_match('/^sae(.*)/i',$domain['root']))
 		{

 			$filename = str_replace("//", '/', $filename);
 		}
 
 		$domain['filename'] = $filename;

 		return $domain;
 	}


 	/**
 	 * mkdir_file 创建文件夹 
 	 *
 	 * @return void
 	 * @author 
 	 *
 	 */
 	function mkdir_file($file_path)
 	{
 		/* 如果是sae数据流则不创建文件夹 */
 		if (preg_match('/^sae(.*)/i',$file_path) || isset($this->server))
 		{
 			return true;
 		}

 		if (!is_dir($file_path))
 		{
 			$oldumask = umask(0);
			$reset = @mkdir($file_path.'/',0777,true);
			chmod($file_path.'/', 0777);
			if (!$reset)
			{
				throw new Exception('创建文件夹'.$file_path.'失败...', -1);
			}
			else
			{
				return true;
			}
 		}
 		else
 		{
 			return true;
 		}

 	}

 	/**
 	 * write 写入文件 
 	 *
 	 * @return void
 	 * @author 
 	 *
 	 */
 	public function write($filename , $conent = '',$domain=array(), $purview = 'w+')
 	{
 		$filename = $this->file_path($filename,$domain);

 		$file_path = $this->mkdir_file(dirname($filename['filename']));
 		
 		if (is_array($file_path))
 		{
 			return $file_path;
 		}

 		/* 判断是否有服务对象 */
 		if (is_object($this->servers))
 		{
 			$this->servers->write($filename['filename'],$conent);

 		}
 		else
 		{
 			if (!@file_put_contents($filename['filename'],$conent))
 			{
 				throw new Exception('写入文件'.$filename['filename'].'失败...', -1);
 			}
 		}
 		return $this->results($filename);
 	}

 	/**
 	 * 写入缓存, 判断domain,如果是缓存,则写入MC
 	 *
 	 * @param string $filename 文件名称,可以是目录名
 	 * @param string $domain 文件存放domain 
 	 * @author zhuayi
 	 *
 	 */
 	function cache_write($filename,$contents,$outtime,$domain = array())
 	{

 		$contents = strings::compress($contents);
 		
 		if (strpos('!'.$filename,'mc::')>0)
 		{
 			global $cache;
 			
 			return $cache->set(md5($filename),$contents,$outtime);
 		}
 		else
 		{
 			/* 压缩文件 */
 			
 			return $this->write($filename,$contents,$domain);
 		}
 	}


 	/**
 	 * exists_file 判断文件是否存在, 首先判断domain,如果是缓存,则调用MC->get
 	 *
 	 * @param string $filename 文件名称,可以是目录名
 	 * @param string $domain 文件存放domain 
 	 * @author zhuayi
 	 *
 	 */
 	function exists_file($filename,$outtime)
 	{
 		if (strpos('!'.$filename,'mc::'))
 		{
 			global $cache;
 			return $cache->get(md5($filename));
 		}

 		/* 判断文件是否存在或过期 */
 		if (file_exists($filename) && (time() - filemtime($filename)) < $outtime)
 		{
 			return true;
 		}
 		else
 		{
 			return false;
 		}

 	}

 	/**
 	 * get 首先判断domain,如果是缓存,则调用MC->get读取,否则引用文件
 	 *
 	 * @param string $filename 文件名称,可以是目录名
 	 * @param string $domain 文件存放domain 
 	 * @author zhuayi
 	 *
 	 */
 	function get($filename)
 	{

 		if (strpos($filename,'mc::'))
 		{
 			global $cache;
 			
 			return $cache->get(md5($filename),true);
 		}
 		else
 		{
 			return file_get_contents($filename);
 		}
 	}


 	/**
 	 * delete 删除文件,  
 	 *
 	 * @param string $filename 文件名称,可以是目录名
 	 * @param string $domain 文件存放domain 
 	 * @author zhuayi
 	 *
 	 */
 	function delete($filename,$domain)
 	{
 		if (empty($domain))
 		{
 			throw new Exception('domain为空不可删除..', -1);
 		}
 		$filename = $this->file_path($filename,$domain);

 		if (@unlink($filename['filename']))
 		{
 			return true;
 		}
 		else
 		{
 			throw new Exception('删除失败..', -1);
 		}
 	
 	}

 	private function _file_path($filename)
 	{
 		if (strpos($filename,ZHUAYI_ROOT) === false)
 		{
 			$filename = ZHUAYI_ROOT.'/'.$filename;
 		}
 		$filename = str_replace("//", '/', $filename);
 		return $filename;
 	}

 	private function _filelist($path,$level)
 	{
 		$path = $this->_file_path($path);
 		//$path = $this->file_path($path);

 		$files = array($path);

 		$handle = opendir($path); 
		while (false != ($file = readdir($handle)))
		{
			if ($file != "." && $file != ".." && $file != ".DS_Store")
			{
				$file = $path.'/'.$file;
				
				if ($level && is_dir($file))
				{

					$files = array_merge($files,$this->_filelist($file,$level));
				}
				else
				{
					$files = array_merge($files,array($file));
				}
			}
		}
		closedir($handle);
		return $files;
 	}
 	
 	/**
 	 * filelist 遍历文件夹  
 	 *
 	 * @param string $path
 	 * @param string $is_file 是否只是文件 
 	 * @param string $level 是否遍历 
 	 * @author zhuayi
 	 *
 	 */
 	public function filelist($path,$is_file = false, $level=false)
 	{
 		$list = $this->_filelist($path,$level);

 		if (!$is_file)
 		{
 			return $list;
 		}
 		foreach ($list as $key=>$val)
 		{
 			if (!is_file($val))
 			{
 				unset($list[$key]);
 			}
 		}
 		$list = explode(",",implode(",",$list));
 		return $list;
 	}

 	/**
 	 * results 替换URL地址,  
 	 *
 	 * @param string $filename 数组,key是返回URL,key2为路径
 	 * @author zhuayi
 	 *
 	 */
 	function results($filename)
 	{
 		return str_replace($filename['root'],$filename['url'],$filename['filename']);
 	}

 }