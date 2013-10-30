<?php

//-----转换时间
function dtime($dtime,$num='0')
{
	if (!is_numeric($dtime))
	{
		$dtime = strtotime($dtime);
	}
	switch ($num)
	{
		case "0";
		return date("Y-m-d G:i:s",$dtime);
		break;
		case "1";
		return date("Y年m月d日",$dtime);
		break;
		case "2";
			$time = time() - $dtime;
			if ($time < 86400)
			{
				if ($time <60 )
				return $time.'秒前';
				if ($time <3600 && $time >60)
				return intval($time/60).'分钟前';
				if ($time >3600 && $time <86400)
				return intval($time/3600).'小时前';
			}
			return date("Y年m月d日",$dtime);
		break;
		case "3";
		return date("Y-m-d",$dtime);
		case "4";
		return date("d",$dtime);
		case "5";
		{
			 $day = array("星期天","星期一","星期二","星期三","星期四","星期五","星期六",);
			 $time = date("w",$dtime);
			return $day[$time];
		}
		case "6";
		return date("m-d",$dtime);
		default :
		return date($num,$dtime);
	}
}

?>