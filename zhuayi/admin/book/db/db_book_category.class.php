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
	
	static function get_book_category_by_name($category_name)
	{
		$category_name = mysql_escape_string($category_name);

		if (empty($category_name))
		{
			throw new Exception("参数错误!", -1);
		}
		$where['category_name'] = $category_name;

		return self::$_instance->db->select_db(self::$db_name_conf)->fetch(self::$table_name,$where);
	}

	static function insert_book_category($category_name,$parent_id)
	{
		
		$parent_id = intval($parent_id);
		$category_name = mysql_escape_string($category_name);

		if (empty($category_name))
		{
			throw new Exception("参数错误!", -1);
		}

		$insert_array = array();
		$insert_array['parent_id'] = $parent_id;
		$insert_array['category_name'] = $category_name;
		$insert_array['category_pinyin'] = self::$_instance->load_fun('pinyin',$category_name);
	
		return self::$_instance->db->select_db(self::$db_name_conf)->insert(self::$table_name,$insert_array);
	}
}
?>