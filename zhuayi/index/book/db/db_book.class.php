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

	static function get_book_info_by_book_id($book_id)
	{
		$book_id = intval($book_id);

		if (empty($book_id))
		{
			throw new Exception("参数错误!", -1);
		}

		$where = array();
		$where['book_id'] = $book_id;
		return self::$_instance->db->select_db(self::$db_name_conf)->fetch(self::$table_name,$where);
	}
}
?>