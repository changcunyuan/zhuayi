<?php
/*
 * db_book.php     Zhuayi 小说
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */

 
class db_book extends zhuayi
{
	static $table_name = "book";
	static $db_name_conf = 'book';
	
	public static $_instance;


	static function insert_book($category_id,$book_name,$author_id,$description,$update_status,$wordcount,$litpic,$update_time)
	{
		$category_id = intval($category_id);
		$book_name = mysql_escape_string($book_name);
		$author_id = intval($author_id);
		$description = mysql_escape_string($description);
		$update_status = intval($update_status);
		$litpic = mysql_escape_string($litpic);
		$wordcount = intval($wordcount);

		if (empty($category_id) || empty($book_name) || empty($author_id) )
		{
			throw new Exception("参数错误!", -1);
		}

		$insert_array = array();
		$insert_array['category_id'] = $category_id;
		$insert_array['book_name'] = $book_name;
		$insert_array['author_id'] = $author_id;
		$insert_array['description'] = $description;
		$insert_array['update_status'] = $update_status;
		$insert_array['wordcount'] = $wordcount;
		$insert_array['litpic'] = $litpic;
		// $insert_array['update_time'] = $update_time;
		return self::$_instance->db->select_db(self::$db_name_conf)->insert(self::$table_name,$insert_array);
	}

	static function get_book_info_by_category_id_author_id_book_name($category_id,$author_id,$book_name)
	{
		$category_id = intval($category_id);
		$book_name = mysql_escape_string($book_name);
		$author_id = intval($author_id);
		if (empty($category_id) || empty($book_name) || empty($author_id))
		{
			throw new Exception("参数错误!", -1);
		}

		$where = array();
		$where['category_id'] = $category_id;
		$where['book_name'] = $book_name;
		$where['author_id'] = $author_id;
		return self::$_instance->db->select_db(self::$db_name_conf)->fetch(self::$table_name,$where);
	}

	static function update_book_info_update_status_by_id($last_chapter,$update_status,$update_time,$wordcount,$book_id)
	{
		$update_status = intval($update_status);
		$last_chapter = mysql_escape_string($last_chapter);
		$update_time = mysql_escape_string($update_time);
		$book_id = intval($book_id);

		if (empty($book_id))
		{
			throw new Exception("参数错误!", -1);
		}

		$update = array();
		$update['last_chapter'] = $last_chapter;
		$update['update_status'] = $update_status;
		$update['update_time'] = $update_time;
		$update['wordcount'] = $wordcount;
		return self::$_instance->db->select_db(self::$db_name_conf)->update(self::$table_name,$update," id = {$book_id}");
	}

	static function get_book_info_by_update_status($update_status,$limit)
	{
		$update_status = intval($update_status);
		$where = array();
		$where['update_status'] = $update_status;
		return self::$_instance->db->select_db(self::$db_name_conf)->fetch_row(self::$table_name,$where,'',$limit);
	}
}
?>