#!/usr/bin/php
<?php
/*
 * cmd_update_book.class.php     更新小说

 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */
include dirname(dirname(__FILE__))."/cron/cron.inc.php";
class cmd extends zhuayi
{

	/* 脚本最大执行时间 */
	public $timeout = 1200;

	public $run_start_time;

	static $qidian_category_url = 'http://all.qidian.com/book/bookStore.aspx?ChannelId=-1&SubCategoryId=-1&Tag=all&Size=-1&Action=-1&OrderId=6&P=all&PageIndex=#page#&update=-1&Vip=-1&Boutique=-1&SignStatus=-1';

	static $nowindex = 0;

	static $qidian_book_url = "http://www.qidian.com/Book/#id#.aspx";

	/* 构造函数 */
	function __construct()
	{
		parent::__construct();
		
		$this->load_class('db');
		$this->load_class('http');
		$this->run_start_time = time();
		parent::$admin = true;

		//$this->http->agent = 'Mozilla/5.0 (Linux; U; Android 4.0.2; en-us; Galaxy Nexus Build/ICL53F) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';
	}

	function run()
	{
		global $argv_array;

		$nowindex = file_get_contents($argv_array['-page_file']);
		if (empty($nowindex))
		{
			$nowindex = 0;
		}

		db_book::$_instance = &$this;
		db_book_source::$_instance = &$this;
		db_book_chapters::$_instance = &$this;
		mod_book_collect_qidian_book::$_instance = &$this->http;
		
		/* 取未完结小说 */
		$book_info = db_book::get_book_info_by_update_status(1,$nowindex.",1");
		$book_info = $book_info[0];
		if (empty($book_info))
		{
			return console_log("Select"," No data.",3);
		}

		console_log("Start","book_id = {$book_info['id']},book_name = {$book_info['book_name']}",0);

		/* 查询来源 */
		$source_info = db_book_source::get_book_source_by_book_id($book_info['id']);

		/* 采集小说信息,是否有更新 */
		$qidian_book_info = mod_book_collect_qidian_book::get_qidian_book_info_by_source_info($source_info);
		if ($qidian_book_info == 404)
		{
			/* 更新表 */
			db_book::update_book_info_update_status_by_id(
															$book_info['title'],
															404,
															$book_info['update_time'],
															$book_info['wordcount'],
															$book_info['id']
														);
			console_log("check update","http status 404",0,1);
			return false;
		}

		if (empty($qidian_book_info['book_name']))
		{
			console_log("check update","collect fail",10,1);
			return false;
		}

		if (strtotime($book_info['update_time']) == strtotime($qidian_book_info['update_time']))
		{
			console_log("check update","no update",0,1);
		}
		else
		{
			/* 章节采集 */
			$chapters_list = mod_book_collect_qidian_book::collect_book_chapters_list_by_source_info($source_info);
			$chapter_url = array();
			$is_ok_array = array();
			foreach ($chapters_list as $key=>$val)
			{
				try
				{
					$chapters_id = db_book_chapters::insert_book_chapters($val['book_id'],$val['title']);

					if (!empty($val['url']))
					{
						$content = mod_book_collect_qidian_book::_get_qidian_free_content_by_url($val['url']);
						$content = mod_book::replace_chapters_content($content);

						/* 上传BCS */
						$is_ok_array[$key] = mod_book::write_book_content_by_book_id_chapters_id($val['book_id'],$chapters_id,$content);
						$is_ok_array[$key]['title'] = $val['title'];

						if ($is_ok_array[$key] != false )
						{
							db_book_chapters::update_book_chapters_is_ok_by_chapters_id(1,$chapters_id);
							console_log("Add chapters","{$val['title']} success {$is_ok_array[$key][1]}",0,1);
						}
					}
					else
					{
						console_log("Add chapters","no available url",0,1);
					}
					
				}
				catch (Exception $e)
				{
					
				}
			}

			/* 合并txt */
			$merge_text = mod_book::merge_text($is_ok_array,$book_info['id'],$book_info['book_name']);
			if ($merge_text === false)
			{
				console_log("merge text","fail",0,1);
			}
			else
			{
				console_log("merge text","success {$merge_text}",0,1);
			}


			/* 生成epub 
			$epub = mod_book::create_epub($is_ok_array,$book_info['id'],$book_info['book_name'],$book_info['description'],$book_info['litpic']);
			if ($epub === false)
			{
				console_log("create epub","fail",0,1);
			}
			else
			{
				console_log("create epub","success {$epub}",0,1);
			}
			*/
			/* 更新表 */
			db_book::update_book_info_update_status_by_id(
															$val['title'],
															$qidian_book_info['update_status'],
															$qidian_book_info['update_time'],
															$qidian_book_info['wordcount'],
															$book_info['id']
														);
		}

		$nowindex++;
		file_put_contents($argv_array['-page_file'],$nowindex);
		$sleep = rand(1,10);
		console_log("END","sleep {$sleep}s",rand(1,10),1);
		sleep($sleep);
		return false;

	}
}

$cron = new cmd();
try
{

	$reset = false;
	do
	{
		$reset = $cron->run($argv);
	}
	while ($reset===false);
	
} 
catch (ZException $e){}

?>