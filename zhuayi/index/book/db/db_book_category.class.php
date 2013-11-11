<?php
/*
 * db_book_category.php     Zhuayi 小说分类
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */

 
class db_book_category extends zhuayi
{
	private static $table_name = "book_category";
	private static $db_name_conf = 'book';
	
	public static $_instance;
	
	static function get_book_category_by_id($id)
	{
		$id = intval($id);

		if (empty($id))
		{
			throw new Exception("参数错误!", -1);
		}
		$where['id'] = $id;

		return self::$_instance->db->select_db(self::$db_name_conf)->fetch(self::$table_name,$where);
	}
}
?>