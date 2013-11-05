<?php
/*
 * book_action.php     Zhuayi 小说前台
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */
class book_action extends zhuayi
{

	/* 构造函数 */
	function __construct()
	{
		parent::__construct();
		
		$this->load_class('db');
	}

	function index()
	{
		
	}

	function intro($book_name,$book_id)
	{
		db_book::$_instance = &$this;

		$book_info = db_book::get_book_info_by_book_id($book_id);
		if (empty($book_info))
		{
			throw new Exception("Error Processing Request", -1);
		}

		if (urldecode($book_name) != $book_info['book_name'])
		{
			$book_url = str_replace('#book_name#',$book_info['book_name'],mod_book::$book_intro_url);
			$book_url = str_replace('#book_id#',$book_info['id'],$book_url);
			return output::url($book_url);
		}

		print_r($book_info);
		var_dump($book_name);
		var_dump($book_id);
		exit;
	}
}