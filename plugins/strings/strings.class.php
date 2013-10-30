<?php
/*
 * strings.php     Zhuayi 字符串操作类
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */

 class strings
 {
 	function __construct() 
 	{
 		
 	}

 	/**
 	 * limit 根据指定的字符数目对一段字符串进行截取
 	 *
 	 * 截取字符串(UTF-8)
	 *
	 * @param string $string 原始字符串
	 * @param $start 开始截取位置
	 * @param $len 需要截取的偏移量
	 * $type=1 等于1时末尾加'...'不然不加
 	 **/
	function limit($string, $start, $len, $byte=3)
 	{
 		$string = htmlspecialchars_decode($string);
 		$string = str_replace('<br />','\n',$string);
 		$string = strip_tags($string);
 		if (empty($string))
 		{
 			return $string;
 		}
 		if(strlen($string)<3*$len)
 		{
 			return $string;
 		}
		
		$re = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		preg_match_all($re, $string, $match);
		$string = join("",array_slice($match[0], $start, $len));
		return str_replace('\n','<br />',$string);
 	}

 	/**
 	 * iconvn 万能字符串转码
 	 *
 	 * @return void
 	 * @author 
 	 **/
	function iconvn($string,$outcode='utf-8//IGNORE')      
	{

		$incode = mb_detect_encoding($string, array('ASCII','gb2312','gbk','utf-8','utf-16'));

		if (empty($incode))
		{
			$incode = 'GBK';
		}
		if ($incode == $outcode)
		{
			return $string;
		}
		else
		{
			return iconv($incode,$outcode, $string);
		}
		return $encode;
	}

	/**
	 * replace_a 过滤HTML
	 *
	 * @return void
	 * @author 
	 **/
	function replace_a($string)
	{
		$string = htmlspecialchars_decode($string);
		$string =  preg_replace('/<a(.*?)href=(.*?)>(.*?)<\/a>/i',"$3" , $string);
		return htmlspecialchars($string);
	}

	/**
	 * replace_a 过滤HTML,但保留BR换行
	 *
	 * @return void
	 * @author 
	 **/
	function replace_html_no_br($string)
	{
		$string = htmlspecialchars_decode($string);
		$string = str_replace('<br />','\n',$string);
		$string = strip_tags($string);
		return str_replace('\n','<br />',$string);
	}

	/**
	 * strip 过滤HTML
	 *
	 * @return void
	 * @author 
	 **/
	function strip($string)
	{
		$string = htmlspecialchars_decode($string);
		return strip_tags($string);
	}

	/**
	 * mymd5 加密字符串
	 *
	 * @return void
	 * @author 
	 **/
	 function mymd5($string)
	 {
	 	return md5($string.md5($string));
	 }

	 /**
	 * compress 压缩字符串
	 *
	 * @return void
	 * @author 
	 **/
	function compress($string)
	{
	 	$string = preg_replace("/(^http:)*\/\/[\S^;]*;/","",$string);
		$string = preg_replace("/\<\!\-\-[\s\S]*?\-\-\>/","",$string);
		$string = preg_replace("/\>[\s]+\</","><",$string);
		$string = preg_replace("/;[\s]+/",";",$string);
		$string = preg_replace("/[\s]+\}/","}",$string);
		$string = preg_replace("/}[\s]+/","}",$string);
		$string = preg_replace("/\{[\s]+/","{",$string);
		$string = preg_replace("/([\s]){2,}/","$1",$string);
		return preg_replace("/[\s]+\=[\s]+/","=",$string);
	}

	function isUTF8($str)
	{
       if ($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32")) {
           return true;
       }
       else
       {
           return false;
       }
    }

    /**
	 * 计算内容中的字数，未对长连接特殊处理。
	 * 规则同微博(汉字1，字母、数字1/2)
	 * @param string $content
	 * @return number
	 */
    function get_str_len($str)
    {
    	return ceil(strlen(iconv('UTF-8','GBK', $str))/2);
    }


    static function php_crc32($value)
	{
		
		return sprintf("%u", crc32($value));
		
	}

	function un_script_code($str)
	{
		$s			= array();
		$s["/script(.*?)\/script/si"] = "";
		$str = strings::filt_string($str, $s, true);

		
		return $str;
	}

	/* 递归转义称html */
	function _htmlspecialchars($strings)
	{
		$strings = htmlspecialchars_decode($strings);

		/* 由于多次转义只是&变了,所以只判断是否还存在&,如果存在转义过的&,则继续转义 */
		if (strpos($strings,'&amp;') !== false)
		{
			$strings = self::_htmlspecialchars($strings);
		}
		return $strings;
	}
	/**
	 * 过滤html 但保留图片
	 * @return string
	 * @param string $strings 需要过滤的字符
	 */
	function un_html_code_no_img($strings)
	{
		/* 递归转义称html */
		$strings = self::_htmlspecialchars($strings);
		
		$strings = preg_replace('/<img(.*?)src=[\"|\'](.*?)[\"|\'](.*?)[\/]>/si', "&lt;img src='$2'/&gt;",$strings);
		/* 去除连续空格和换行 */
		$strings = preg_replace("/[\n| ]{2,}/","",$strings);
		$strings = preg_replace('/<br(.*?)>/si','&lt;br/&gt;',$strings);
		$strings = strip_tags($strings);
		//$strings = htmlspecialchars_decode($strings);
		return $strings;
	}

	/**
	 * 过滤字符串中的特殊字符
	 * @return string
	 * @param string $str 需要过滤的字符
	 * @param string $filtStr 需要过滤字符的数组（下标为需要过滤的字符，值为过滤后的字符）
	 * @param boolen $regexp 是否进行正则表达试进行替换，默认false
	 */
	
	function filt_string($str, $filtStr, $regexp = false)
	{
		$str = htmlspecialchars_decode($str);

		if (!is_array($filtStr))
		{
			return $str;
		}
		$search		= array_keys($filtStr);
		$replace	= array_values($filtStr);
				
		if ($regexp)
		{
			return preg_replace($search, $replace, $str);
		}
		else
		{
			return str_replace($search, $replace, $str);
		}
	}

	/* 序列化数据的时候的编码和反序列化时的编码不一样导致字符串的长度出现偏差,导致unserialize失败 */
	function unserialize($strings)
	{
		$strings = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'",$strings );

		return unserialize($strings);
	}

	/**
	*十进制转二进制、八进制、十六进制 不足位数前面补零*
	*
	* @param array $datalist 传入数据array(100,123,130)
	* @param int $bin 转换的进制可以是：2,8,16
	* @return array 返回数据 array() 返回没有数据转换的格式
	* @Author chengmo QQ:8292669
	* @copyright http://www.cnblogs.com/chengmo 
	*/
	function decto_bin($datalist,$bin)
	{
		static $arr = array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F');
		if(!is_array($datalist))
		{
			$datalist = array($datalist);
		}
		//相同进制忽略
		if($bin==10)
		{
			return $datalist;
		}

		//获得如果是$bin进制，一个字节的长度
		$bytelen = ceil(16/$bin);
		$aOutChar = array();
		foreach ($datalist as $num)
		{
			$t ="";
			$num = intval($num);
			if($num === 0)continue;
			while( $num > 0 )
			{
				$t = $arr[$num%$bin].$t;
				$num = floor($num/$bin);
			}

			$tlen = strlen($t);
			if( $tlen % $bytelen!=0 )
			{
				$pad_len = $bytelen - $tlen % $bytelen;
				$t = str_pad("",$pad_len,"0",STR_PAD_LEFT).$t; //不足一个字节长度，自动前面补充0
			}
			$aOutChar[] = $t;
		}
		return $aOutChar;
	}

	/**
	 *二进制、八进制、十六进制 转十进制*
	 *
	 * @param array $datalist 传入数据array(df,ef)
	 * @param int $bin 转换的进制可以是：2,8,16
	 * @return array 返回数据 array() 返回没有数据转换的格式
	 * @copyright chengmo QQ:8292669
	 */
	function bin_todec($datalist,$bin)
	{
	    static $arr=array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9,'A'=>10,'B'=>11,'C'=>12,'D'=>13,'E'=>14,'F'=>15);
	    if(!is_array($datalist))$datalist=array($datalist);
	    if($bin==10)return $datalist; //为10进制不转换
	    $aOutData=array(); //定义输出保存数组
	    foreach ($datalist as $num)
	    {
	    	if ($bin == 2)
	    	{
	    		$aOutData[] = bindec($num);
	    	}
	    	else
	    	{
	    		$atnum=str_split($num); //将字符串分割为单个字符数组
	    		$atlen=count($atnum);
	    		$total=0;
	    		$i=1;
	    		foreach ($atnum as $tv)
	    		{
	    		    $tv=strtoupper($tv);
	    		     
	    		    if(array_key_exists($tv,$arr))
	    		    {
	    		        if($arr[$tv]==0)continue;
	    		        $total=$total+$arr[$tv]*pow($bin,$atlen-$i);
	    		    }
	    		    $i++;
	    		}
	    	}
	        
	        $aOutData[]=$total;
	    }
	    return $aOutData;
	}

	/* 检查代码是否含有非法字符 */
	function check_un_code($strings)
	{
		$ra_list = self::get_un_code($strings);
		foreach ($ra_list as $val)
		{
			if (strpos($strings,$val) !== false)
			{
				return false;
			}
		}
		return true;
	}


	function get_un_code()
	{
		$ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'base');   
		$ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');   
		$ra = array_merge($ra1, $ra2);   

		return $ra;
	}


	function RemoveXSS($val)
	{    
	   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);    
	   $search = 'abcdefghijklmnopqrstuvwxyz';   
	   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';    
	   $search .= '1234567890!@#$%^&*()';   
	   $search .= '~`";:?+/={}[]-_|\'\\';   
	   for ($i = 0; $i < strlen($search); $i++)
	   {   
	   
	      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val);
	      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); 
	   }   
	   
	   $ra = self::get_un_code();
	   
	   $found = true; 
	   while ($found == true) {   
	      $val_before = $val;   
	      for ($i = 0; $i < sizeof($ra); $i++) {   
	         $pattern = '/';   
	         for ($j = 0; $j < strlen($ra[$i]); $j++) {   
	            if ($j > 0) {   
	               $pattern .= '(';    
	               $pattern .= '(&#[xX]0{0,8}([9ab]);)';   
	               $pattern .= '|';    
	               $pattern .= '|(&#0{0,8}([9|10|13]);)';   
	               $pattern .= ')*';   
	            }   
	            $pattern .= $ra[$i][$j];   
	         }   
	         $pattern .= '/i';    
	         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2);
	         $val = preg_replace($pattern, $replacement, $val);
	         if ($val_before == $val) {    
	            $found = false;    
	         }    
	      }    
	   }    
	   return $val;    
	}

	/**
	 * 富文本过滤器*
	 */
	function Safe_html($strings,$AllowTags = array())
	{
		$strings = htmlspecialchars_decode($strings);
		include dirname(__FILE__)."/Safe.php";
		$safe = new HTML_Safe;
		$array = self::get_un_code();
       	$safe->setAllowTags(array('style'));
        return $safe->parse($strings); 
	}
 }