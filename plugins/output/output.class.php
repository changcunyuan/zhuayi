<?php
/**
 * index.php     Zhuayi 消息输出类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ			 2179942
 */
class output extends zhuayi
{

	/**
	 * 错误页面,
	 *
	 * @param string $title 错误页面提示性文字
	 */
	static function error($title='未知错误!',$file='',$line)
	{
		global $config;

		if (empty($url))
		{
			$url = @$_SERVER['HTTP_REFERER'];
		}
		
		$show['title'] = $title;
		$show['msg'] = "出错页面: {$file}";
		$show['msg'] .= "<br/> 所在行: {$line}";
		$show['msg'] .= "<br/> URL地址: http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
		if (!empty($_SERVER['HTTP_REFERER']))
		{
			$show['msg'] .= "<br/> 来路地址: ".$_SERVER['HTTP_REFERER'];			
		}
		$show['msg'] .= "<br/> 访问IP: ".ip::get_ip(false);
		$show['url'] = $url;
		require  dirname(__FILE__).'/template/error.html';
	}

	/**
	 * 404,
	 *
	 * @param string $title 错误页面提示性文字
	 */
	function _404($tpl='')
	{

		if (empty($tpl))
		{
			require  dirname(__FILE__).'/template/404.html';
		}
		else
		{
			require $tpl;
		}
		

		exit;
	}

	/**
	 * 返回JSON数据,
	 *
	 * @param string $title 错误页面提示性文字
	 */
	function json($status = 0 ,$msg = '',$line='',$file='')
	{
		header('Content-type: application/json');
		$array['status'] = $status;
		$array['msg'] = $msg;
		$array['message'] = $msg;
		if ($status == '-1')
		{
			$array['message'] = $msg;
			$array['error'] = 1;
		}
		if (!empty($line))
		{
			$array['line'] = $line;
		}
		if (!empty($file))
		{
			$array['file'] = $file;
		}
		return json_encode($array);
	}

	function jsonp($status = 0 ,$msg = '',$callback)
	{
		header('Content-type: application/json');
		$array['status'] = $status;
		$array['msg'] = $msg;
		return "{$callback}(".json_encode($array).")";
	}

	/**
	 * 返回数组,
	 *
	 * @param string $title 错误页面提示性文字
	 */
	function arrays($status = 0 ,$msg = '')
	{
		return array('status'=>$status,'msg'=>$msg);
	}

	/**
	 * 跳转URL
	 *
	 * @param string $title 错误页面提示性文字
	 */
	function url($url)
	{
		if (empty($url))
		{
			$url = $_SERVER['HTTP_REFERER'];
		}

		header("Location: ".urldecode($url));
	}

	function go($title,$url)
	{
		$show['title'] = $title;
		$show['url'] = $url;
		
		require  dirname(__FILE__).'/template/go.html';
	}

	function auth($username,$password)
	{

		if (!isset($_SERVER['PHP_AUTH_USER']))
		{
			header('WWW-Authenticate: Basic realm=""');
			header('HTTP/1.0 401 Unauthorized');
			echo 'Text to send if user hits Cancel button';
			exit;
		}
		elseif($_SERVER['PHP_AUTH_USER']!=$username or $_SERVER['PHP_AUTH_PW']!=$password)
        {
        	header('WWW-Authenticate:Basic realm=""'); 
			header('HTTP/1.0 401 Unauthorized'); 
			output::error('Get out of here!');
        }

	}
}
?>
