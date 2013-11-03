<?php
/*
 * db_book_author.php     Zhuayi 小说作者
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */

 
class db_book_author extends zhuayi
{
	private static $table_name = "book_author";
	private static $db_name_conf = 'book';
	
	public static $_instance;
	
	static function get_book_author_by_name($author_name)
	{
		$author_name = mysql_escape_string($author_name);

		if (empty($author_name))
		{
			throw new Exception("参数错误!", -1);
		}
		$where['author_name'] = $author_name;

		return self::$_instance->db->select_db(self::$db_name_conf)->fetch(self::$table_name,$where);
	}

	static function insert_book_author($author_name)
	{
		$author_name = mysql_escape_string($author_name);

		if (empty($author_name))
		{
			throw new Exception("参数错误!", -1);
		}

		$insert_array = array();
		$insert_array['author_name'] = $author_name;
		$insert_array['author_pinyin'] = self::$_instance->load_fun('pinyin',$author_name);
	
		return self::$_instance->db->select_db(self::$db_name_conf)->insert(self::$table_name,$insert_array);
	}
}
?>