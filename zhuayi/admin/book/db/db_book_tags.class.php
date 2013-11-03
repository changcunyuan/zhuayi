<?php
/*
 * db_book_tags.php     Zhuayi 小说作者
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @tags       zhuayi
 * @QQ			 2179942
 */

 
class db_book_tags extends zhuayi
{
	private static $table_name = "book_tags";
	private static $db_name_conf = 'book';
	
	public static $_instance;
	
	static function get_book_tags_by_name($tags_name)
	{
		$tags_name = mysql_escape_string($tags_name);

		if (empty($tags_name))
		{
			throw new Exception("参数错误!", -1);
		}
		$where['tag_name'] = $tags_name;
		return self::$_instance->db->select_db(self::$db_name_conf)->fetch(self::$table_name,$where);
	}

	static function insert_book_tags($tags_name)
	{
		$tags_name = mysql_escape_string($tags_name);

		if (empty($tags_name))
		{
			throw new Exception("参数错误!", -1);
		}

		$insert_array = array();
		$insert_array['tag_name'] = $tags_name;
		$insert_array['tags_pinyin'] = self::$_instance->load_fun('pinyin',$tags_name);
	
		return self::$_instance->db->select_db(self::$db_name_conf)->insert(self::$table_name,$insert_array);
	}
}
?>