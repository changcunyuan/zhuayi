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

	private static $qidian_chapters_url = 'http://read.qidian.com/BookReader/#id#.aspx';

	public static $_instance;

	private static $agent = 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)';

	private static $qidian_book_content_url = 'http://read.qidian.com/BookReader/#id#,#chaptersid#.aspx';

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

		if (self::$_instance->http_status == '404')
		{
			return 404;
		}

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

	static function _get_qidian_id_by_book_url($qidian_book_url)
	{
		preg_match('/.*?([0-9]+)\./',$qidian_book_url,$qidian_book_id);
		return $qidian_book_id[1];
	}

	static function get_qidian_book_info_by_source_info($source_info)
	{
		$qidian_book_id = self::_get_qidian_id_by_book_url($source_info['source_url']);
		return self::collect_book_info_by_id($qidian_book_id);
	}


	static function _get_qidian_free_content_by_url($url)
	{
		$qidian_book_content_url = self::$qidian_book_content_url;

		preg_match("/([0-9]+),([0-9]+)/s",$url,$val_url);
		$qidian_book_content_url = str_replace("#id#",$val_url[1],$qidian_book_content_url);
		$qidian_book_content_url = str_replace("#chaptersid#",$val_url[2],$qidian_book_content_url);

		self::$_instance->get($qidian_book_content_url);
		preg_match('/bookcontent"(.*?)src=\'(.*?)\'/s', self::$_instance->results,$list);
		
		$content = false;
		if (!empty($list[2]))
		{
			$content = file_get_contents($list[2]);
			$content = iconv('gbk','utf-8',$content);
			$content = str_replace('document.write(\'','',$content);
			$content = str_replace('\')','',$content);
			$content = preg_replace('/<a(.*)<\/a>/s', '', $content);	
		}
		
		return $content;
	}

	static function collect_book_chapters_list_by_source_info($source_info)
	{
		if (!is_array($source_info) && !empty($source_info['url']))
		{
			throw new Exception("参数错误!", -1);
		}

		self::$_instance->agent = self::$agent;

		preg_match('/.*?([0-9]+)\./',$source_info['source_url'],$qidian_book_id);
		$qidian_book_id = $qidian_book_id[1];

		$url = str_replace("#id#",$qidian_book_id,self::$qidian_chapters_url);
		
		self::$_instance->get($url);

		/* 采集免费列表 */
		preg_match_all("/itemprop='chapter'(.*?)href=\"(.*?)\"(.*?)headline'>(.*?)</s",self::$_instance->results,$list);

		$list_tmp = array();		
		foreach ($list[4] as $key=>$val)
		{
			$list_tmp[$key]['book_id'] = $source_info['book_id'];
			$list_tmp[$key]['title'] = trim($val);
			$list_tmp[$key]['url'] = $list[2][$key];
		}

		$i = $key;
		/* 采集VIP列表 */
		preg_match_all("/<a rel=\"nofollow\"(.*?)vipreader.qidian.com\/BookReader\/vip(.*?)title='(.*?)>(.*?)</s",self::$_instance->results,$list);
		foreach ($list[4] as $val)
		{
			$i++;
			$list_tmp[$i]['book_id'] = $source_info['book_id'];
			$list_tmp[$i]['title'] = $val;
		}
	
		return $list_tmp;
	}
}
?>