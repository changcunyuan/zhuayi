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
		
		$nowindex = (empty($argv_array['-page'])?0:$argv_array['-page'])+self::$nowindex;

		db_book::$_instance = &$this;
		db_book_source::$_instance = &$this;
		db_book_chapters::$_instance = &$this;
		mod_book_collect_qidian_book::$_instance = &$this->http;
		
		/* 取未完结小说 */
		$book_info = db_book::get_book_info_by_update_status(1,"{$nowindex},1");
		$book_info = $book_info[0];
		if (empty($book_info))
		{
			throw new Exception("完了", -1);
		}

		echo "小说名: {$book_info['book_name']}";

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
			echo "@@@@@@http_code is 404 delete @@@@@@\n";
			return false;
		}

		if (empty($qidian_book_info['book_name']))
		{
			echo "@@@@@@{$book_info['book_name']}  error sleep 30s @@@@@@\n";
			sleep(30);
			return false;
		}

		if (strtotime($book_info['update_time']) == strtotime($qidian_book_info['update_time']))
		{
			echo "     章节状态: 无更新\n";
		}
		else
		{
			echo "\n------------------------------------------------------------------------\n";
			/* 章节采集 */
			$chapters_list = mod_book_collect_qidian_book::collect_book_chapters_list_by_source_info($source_info);
	
			foreach ($chapters_list as $val)
			{
				try
				{
					$write_status = "empty";
					$is_ok = 0;

					$chapters_id = db_book_chapters::insert_book_chapters($val['book_id'],$val['title']);

					if (!empty($val['url']))
					{
						$content = mod_book_collect_qidian_book::_get_qidian_free_content_by_url($val['url']);

						if (mod_book::write_book_content_by_book_id_chapters_id($val['book_id'],$chapters_id,$content))
						{
							$write_status = "success";
							$is_ok = 1;
							db_book_chapters::update_book_chapters_is_ok_by_chapters_id($is_ok,$chapters_id);
						}
					}
					echo "ADD章节: {$val['title']} Write: {$write_status}\n";
					
				}
				catch (Exception $e)
				{
					
				}
			}

			/* 更新表 */
			db_book::update_book_info_update_status_by_id(
															$val['title'],
															$qidian_book_info['update_status'],
															$qidian_book_info['update_time'],
															$qidian_book_info['wordcount'],
															$book_info['id']
														);
		}

		self::$nowindex++;
		$sleep = rand(1,10);
		echo "@@@@@@sleep:{$sleep}@@@@@@\n";
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