<?php
/*
 * mod_book.class.php     Zhuayi 小说模型
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */
class mod_book extends zhuayi
{
	
	private static $path = "/#book_id#/#chapters_id#.txt";

	static function get_book_content_path_by_book_id_chapters_id($book_id,$chapters_id)
	{
		$filename = str_replace("#book_id#",substr(md5($book_id),3,7),$_SERVER['BOOK_CONTENT_DIR'].self::$path);
		return str_replace("#chapters_id#",substr(md5($chapters_id),5,9),$filename);
	}

	static function write_book_content_by_book_id_chapters_id($book_id,$chapters_id,$content)
	{
		if (empty($content))
		{
			return false;
		}
		
		$filename = self::get_book_content_path_by_book_id_chapters_id($book_id,$chapters_id);
		
		$dir = dirname($filename);

		if (!is_dir($dir))
		{
			$oldumask = umask(0);
			$reset = @mkdir($dir.'/',0777,true);
			chmod($dir.'/', 0777);
		}
		return file_put_contents($filename,$content);
		
	}
}
?>