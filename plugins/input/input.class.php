<?php
/**
 * input.php     Zhuayi 输入类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ			 2179942
 */
class input 
{

	function __construct()
	{
		
	}


	static function _method_post($v)
	{
		if (isset($_POST[$v]))
		{
			return $_POST[$v];
		}
	}
	
	/**
	 * 取post方式的变量值
	 * @return mexid
	 * @param	string	$v	取值得名称
	 */
	static function _method_get($v)
	{
		if (isset($_GET[$v]))
		{
			return $_GET[$v];
		}
	}

	static function _revert($key,$defval,$cgitype,$xss_filter)
	{
		$cgi_in = NULL;
		switch($cgitype)
		{
			case 1:
				$cgi_in = input::_method_post($key);
				break;
			case 2:
				break;
			default:
				$cgi_in = input::_method_get($key);
				break;
		}

		if (is_numeric($defval))
		{
			if (!is_numeric($cgi_in))	// 如果要求是数值，而传入是非数值
			{
				$cgi_in = $defval + 0;
			}
			$cgi_in = intval($cgi_in);
		}
		else
		{
			if (is_null($cgi_in))
			{
				$cgi_in = $defval . '';
			}

			if ($xss_filter)
			{
				$cgi_in = strings::un_script_code($cgi_in);
			}
			//$cgi_in = mysql_escape_string($cgi_in);
		}

		return $cgi_in;
	}

	/**
	 * 以get方式取cgi变量
	 * @param	string $get_key GET参数
	 * @param	string $defval 输出类型和默认值,如果为数字,则返回整型
	 * @param	bool $xss_filter 是否进行xss过滤,只针对返回为字符串类型的参数
	 */
	function get($get_key,$defval,$xss_filter = false)
	{
		return input::_revert($get_key, $defval,0 ,$xss_filter);
	}

	/**
	 * 以post方式取cgi变量
	 */
	function post($post_key,$defval)
	{
		return input::_revert($post_key, $defval,1,$xss_filter = false);
	}
}
?>