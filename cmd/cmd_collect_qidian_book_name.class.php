#!/usr/bin/php
<?php
/*
 * cmd.php     采集起点小说名

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

		$this->http->agent = 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)';
	}

	function run()
	{
		global $argv_array;
				/* 采集小说ID */
		$page = (empty($argv_array['-page'])?1:$argv_array['-page'])+self::$nowindex;
		self::$qidian_category_url = str_replace("#page#",$page,self::$qidian_category_url);

		/* 开始采集 */
		$this->http->get(self::$qidian_category_url);

		echo "=================================================================================\n";
		echo "www.qidian.com 第{$page}页 ".self::$qidian_category_url."\n";
		echo "_________________________________________________________________________________\n";
	
		//$strings = file_get_contents('/Users/zhuayi/1.html');
		preg_match_all('/class="sw[2|1]"(.*?)hui2">(.*?)<\/a>(.*?)hui2">(.*?)<\/a>(.*?)"swb">(.*?)\/Book\/([0-9]+)(.*?)">(.*?)<\/a>(.*?)<\/div><div class="sw(2|1)"/s', $this->http->results,$list);

		foreach ($list[7] as $key=>$val)
		{
			$qidian_book_url[$key]['id'] = $val;
			$qidian_book_url[$key]['category'] = $list[2][$key];
		}
		

		/* 创建静态类  对象 */
		mod_book_collect_qidian_book::$_instance = &$this->http;
		db_book_category::$_instance = &$this;
		db_book_source::$_instance = &$this;
		db_book_author::$_instance = &$this;
		db_book::$_instance = &$this;
		db_book_tags::$_instance = &$this;
		db_book_tags_relation::$_instance = &$this;
		

		/* 开始采集小说 */
		foreach ($qidian_book_url as $val)
		{
			/* 检查分类是否存在 */
			try
			{
				$category_info = db_book_category::get_book_category_by_name($val['category']);
			}
			catch (Exception $e)
			{
				echo "@@@@@@category error sleep 30s @@@@@@\n";
				sleep(30);
				return false;
			}
			
			if ($category_info === false)
			{
				$category_info['id'] = db_book_category::insert_book_category($val['category'],0);
			}
			
			$book_info = mod_book_collect_qidian_book::collect_book_info_by_id($val['id']);

			if (empty($book_info['smail_category']))
			{
				echo "@@@@@@{$book_info['url']} smail_category error sleep 30s @@@@@@\n";
				sleep(30);
				return false;
			}

			/* 插入小分类 */
			$smail_category_info = db_book_category::get_book_category_by_name($book_info['smail_category']);
			if ($smail_category_info === false)
			{
				$smail_category_info['id'] = db_book_category::insert_book_category($book_info['smail_category'],$category_info['id']);
			}


			/* 插入作者 */
			$author_info = db_book_author::get_book_author_by_name($book_info['author']);
			if ($author_info === false)
			{
				$author_info['id'] = db_book_author::insert_book_author($book_info['author']);
			}

			$tags_list = $book_info['tags'];
			$source_url = $book_info['url'];
			/* 插入小说 */
			try
			{
				$book_info['id'] = db_book::insert_book(
													$smail_category_info['id'],
													$book_info['book_name'],
													$author_info['id'],
													$book_info['description'],
													$book_info['update_status'],
													$book_info['wordcount'],
													$book_info['litpic'],
													$book_info['update_time']
												);
				$status_name = "Add";

			} catch (Exception $e)
			{
				$book_info = db_book::get_book_info_by_category_id_author_id_book_name($smail_category_info['id'],$author_info['id'],$book_info['book_name']);
				$status_name = "skip(".$e->getMessage().")";
			}

			/* 插入来源 */
			$source_info = db_book_source::get_book_source_by_source_url_book_id($source_url,$book_info['id']);
			if ($source_info === false)
			{
				$source_info['id'] = db_book_source::insert_book_source($book_info['id'],'qidian',$source_url);
				$source_info['source_url'] = $source_url;
			}
			else
			{
				db_book_source::update_book_source_update_time_by_id($source_info['id']);
			}
			
			/* 写入TAG */
			foreach ($tags_list as $val)
			{
				if (!empty($val))
				{
					$tag_info  = db_book_tags::get_book_tags_by_name($val);

					if ($tag_info === false)
					{
						$tag_info['id'] = db_book_tags::insert_book_tags($val);
					}

					try
					{
						db_book_tags_relation::insert_book_tags_relation($tag_info['id'],$book_info['id']);	
					} catch (Exception $e) {
						
					}
				}
				
			}
			

			echo "小说名: {$book_info['book_name']} 状态: {$status_name} 采集地址: {$source_info['source_url']} 占用内存: ".sprintf('%0.5f', memory_get_usage() / 1048576 )."\n";
			echo "__________________________________________________________________________________________________\n";

		}

		self::$nowindex++;
		$sleep = rand(60,180);
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