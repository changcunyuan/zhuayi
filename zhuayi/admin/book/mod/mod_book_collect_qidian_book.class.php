<?php
/*
 * mod_book_collect_qidian_book.class.php     Zhuayi 起点采集类
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */

class mod_book_collect_qidian_book extends zhuayi
{
	private static $qidian_book_url = "http://www.qidian.com/Book/#id#.aspx";

	public static $_instance;

	private static $agent = 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)';

	function __construct()
	{
		self::$http = $this->load_class('http');
	}

	static function collect_book_info_by_id($id)
	{
		self::$_instance->agent = self::$agent;

		$url = str_replace("#id#",$id,self::$qidian_book_url);
		$book['url'] = $url;
		
		self::$_instance->get($url);

		/* 采集小说标题 */
		preg_match('/<h1 itemprop="name">(.*?)<\/h1>/s', self::$_instance->results,$book_preg);
		$book['book_name'] = trim($book_preg[1]);

		/* 采集小说小分类 */
		preg_match('/<span itemprop="genre">(.*?)</s', self::$_instance->results,$book_preg);
		$book['smail_category'] = trim($book_preg[1]);

		/* 采集小说作者 */
		preg_match('/<span itemprop="name">(.*?)</s', self::$_instance->results,$book_preg);
		$book['author'] = trim($book_preg[1]);

		/* 采集小说简介 */
		preg_match('/<span itemprop="description">(.*?)<\/span>/s', self::$_instance->results,$book_preg);
		$book['description'] = trim($book_preg[1]);

		/* 采集小说进程 */
		preg_match('/itemprop="updataStatus">(.*?)<\/span>/s', self::$_instance->results,$book_preg);
		$book['update_status'] = trim($book_preg[1]);
		if (strpos($book['update_status'],'完') === false)
		{
			$book['update_status'] = 1;
		}
		else
		{
			$book['update_status'] = 0;
		}

		/* 采集完成字数 */
		preg_match('/itemprop="wordCount">([0-9]+)<\/span>/s', self::$_instance->results,$book_preg);
		$book['wordcount'] = trim($book_preg[1]);

		/* 采集tag */
		$strings_tmp = explode('自定义标签：', self::$_instance->results);
		$strings_tmp = explode('读者', $strings_tmp[1]);
		$strings_tmp = strip_tags($strings_tmp[0]);
		$strings_tmp = str_replace("&nbsp;",'',$strings_tmp);
		$strings_tmp = trim($strings_tmp);
		$book['tags'] = explode('、',$strings_tmp);
		
		/* 采集封面 */
		preg_match('/itemprop="image" src="(.*?)"/s', self::$_instance->results,$book_preg);
		$book['litpic'] = trim($book_preg[1]);

		/* 采集时间 */
		preg_match('/"dateModified">(.*?)</s', self::$_instance->results,$book_preg);
		$book['update_time'] = trim($book_preg[1]);

		return $book;
	}
}
?>