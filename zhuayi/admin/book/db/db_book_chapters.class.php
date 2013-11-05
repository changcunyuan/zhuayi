<?php
/*
 * db_book_chapters.php     Zhuayi 章节
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @tags       zhuayi
 * @QQ			 2179942
 */

 
class db_book_chapters extends zhuayi
{
	private static $table_name = "book_chapters";
	private static $db_name_conf = 'book';
	
	public static $_instance;

	static function get_book_chapters_by_chapters_title_book_id($chapters_title,$book_id)
	{
		$book_id = intval($book_id);
		$chapters_title = mysql_escape_string($chapters_title);

		if (empty($chapters_title))
		{
			throw new Exception("参数错误!", -1);
		}
		$where['crc32_book_id_title'] = crc32($book_id.$chapters_title);
		return self::$_instance->db->select_db(self::$db_name_conf)->fetch(self::$table_name,$where);
	}


	static function insert_book_chapters($book_id,$title)
	{
		$book_id = intval($book_id);
		$title = mysql_escape_string($title);

		if (empty($book_id) || empty($title))
		{
			throw new Exception("参数错误!", -1);
		}

		$insert_array = array();
		$insert_array['book_id'] = $book_id;
		$insert_array['title'] = $title;
		$insert_array['crc32_book_id_title'] = crc32($book_id.$title);;
		return self::$_instance->db->select_db(self::$db_name_conf)->insert(self::$table_name,$insert_array);
	}

	static function update_book_chapters_is_ok_by_chapters_id($is_ok,$chapters_id)
	{
		$chapters_id = intval($chapters_id);
		$is_ok = intval($is_ok);

		if (empty($chapters_id))
		{
			throw new Exception("参数错误!", -1);
		}

		$update_array = array();
		$update_array['is_ok'] = $is_ok;
		return self::$_instance->db->select_db(self::$db_name_conf)->update(self::$table_name,$update_array,"id = {$chapters_id}");
	}
}
?>