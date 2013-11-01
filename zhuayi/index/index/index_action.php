<?php
/*
 * index_action.php     Zhuayi 前台
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */
class index_action extends zhuayi
{

	/* 构造函数 */
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$show['content'] = 'Hello Zhuayi!';
		$this->display($show);
	}
}