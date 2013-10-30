<?php
/*
 * image.php     Zhuayi 提取内容图片地址
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */ 
function image($string)
{
	$string = htmlspecialchars_decode($string);

	preg_match_all('/<\s*img\s+[^>]*?src\s*=(.*?\s)\s*(.*)>/i',$string,$img);
	if (isset($img[1]) && !empty($img))
	{
		$img[1] = str_replace('"','',$img[1]);
		$img[1] = str_replace("'",'',$img[1]);
		return $img[1];
	}
	else
	return false;
}

?>