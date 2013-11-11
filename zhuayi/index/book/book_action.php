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

	function detail($book_name,$book_id)
	{
		db_book::$_instance = &$this;
		db_book_category::$_instance = &$this;

		$show['book_info'] = db_book::get_book_info_by_book_id($book_id);

		if (empty($show['book_info']))
		{
			throw new Exception("404", -1);
		}

		if (urldecode($book_name) != $show['book_info']['book_name'])
		{
			$book_url = str_replace('#book_name#',$show['book_info']['book_name'],mod_book::$book_intro_url);
			$book_url = str_replace('#book_id#',$show['book_info']['id'],$book_url);
			return output::url($book_url);
		}

		/* 查询所属分类 */
		$show['category'] = db_book_category::get_book_category_by_id($show['book_info']['category_id']);
		if (!empty($show['category']['parent_id']))
		{
			$show['parent_category'] = db_book_category::get_book_category_by_id($show['category']['parent_id']);
		}

		parent::$smarty_registerPlugin[] = array(
													'function',
													'replace_description',
													array(
															'mod_book',
															'replace_description'
														  )
													);

		$this->display($show,'smarty');
	}
}