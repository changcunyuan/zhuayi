<?php
/*
 * db_book_source.php     Zhuayi 采集来源表
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @tags       zhuayi
 * @QQ			 2179942
 */

 
class db_book_source extends zhuayi
{
	private static $table_name = "book_source";
	private static $db_name_conf = 'book';
	
	public static $_instance;

	static function get_book_source_by_source_url_book_id($source_url,$book_id)
	{
		$source_url = mysql_escape_string($source_url);

		if (empty($source_url))
		{
			throw new Exception("参数错误!", -1);
		}
		$where['bood_id_url_key'] = crc32($book_id.$source_url);

		return self::$_instance->db->select_db(self::$db_name_conf)->fetch(self::$table_name,$where);
	}

	static function insert_book_source($book_id,$source,$source_url)
	{
		$book_id = intval($book_id);
		$source = mysql_escape_string($source);
		$source_url = mysql_escape_string($source_url);

		if (empty($book_id) || empty($source_url) || empty($source))
		{
			throw new Exception("参数错误!", -1);
		}

		$insert_array = array();
		$insert_array['book_id'] = $book_id;
		$insert_array['source'] = $source;
		$insert_array['source_url'] = $source_url;
		$insert_array['bood_id_url_key'] = crc32($book_id.$source_url);
		$insert_array['update_time'] = date("Y-m-d H:i:s");
		return self::$_instance->db->select_db(self::$db_name_conf)->insert(self::$table_name,$insert_array);
	}

	static function update_book_source_update_time_by_id($id)
	{
		$id = intval($id);
		if (empty($id))
		{
			throw new Exception("参数错误!", -1);
		}
		$update_array = array();
		$update_array['update_time'] = date("Y-m-d H:i:s");
		return self::$_instance->db->select_db(self::$db_name_conf)->update(self::$table_name,$update_array,"id = {$id}");

	}
}
?>