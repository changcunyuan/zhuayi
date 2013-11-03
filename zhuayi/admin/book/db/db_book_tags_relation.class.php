<?php
/*
 * db_book_tags.php     Zhuayi tag关系表
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @tags       zhuayi
 * @QQ			 2179942
 */

 
class db_book_tags_relation extends zhuayi
{
	private static $table_name = "book_tags_relation";
	private static $db_name_conf = 'book';
	
	public static $_instance;


	static function insert_book_tags_relation($tag_id,$book_id)
	{
		$tag_id = intval($tag_id);
		$book_id = intval($book_id);

		if (empty($tag_id) || empty($book_id))
		{
			throw new Exception("参数错误!", -1);
		}

		$insert_array = array();
		$insert_array['tags_id'] = $tag_id;
		$insert_array['book_id'] = $book_id;
	
		return self::$_instance->db->select_db(self::$db_name_conf)->insert(self::$table_name,$insert_array);
	}
}
?>