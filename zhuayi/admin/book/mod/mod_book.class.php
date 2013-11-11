<?php
/*
 * mod_book.class.php     Zhuayi 小说模型
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */
class mod_book extends zhuayi
{
	private static $bcs_book_url = "bs://zhuayi/book";

	private static $bcs_url = "http://bcs.duapp.com/zhuayi/book";

	private static $chapters_url = "/detail/#book_id#/#chapters_id#.txt"; 

	private static $book_url = "/text/#book_id#.txt"; 

	private static $book_epub_url = "/epub/#book_id#.epub"; 


	static function _get_md5_book_id($book_id)
	{
		return substr(md5($book_id),3,7);
	}

	static function _get_md5_chapters_id($chapters_id)
	{
		return substr(md5($chapters_id),5,9);
	}

	static function get_chapters_url_by_book_id_chapters_id($book_id,$chapters_id)
	{
		$filename = str_replace("#book_id#",self::_get_md5_book_id($book_id),self::$chapters_url);
		return str_replace("#chapters_id#",self::_get_md5_chapters_id($chapters_id),$filename);
	}

	static function write_book_content_by_book_id_chapters_id($book_id,$chapters_id,$content)
	{
		if (empty($content))
		{
			return false;
		}
		
		$chapters_url = self::get_chapters_url_by_book_id_chapters_id($book_id,$chapters_id);

		/* 创建临时文件 */
		$filename = tempnam($_SERVER['ZHUAYI_CACHE_DIR'],'zhuayi');
		
		if (file_put_contents($filename,$content))
		{
			if (self::cp_bcs($filename,self::$bcs_book_url.$chapters_url) !== false)
			{
				return array(
								$filename,
								self::$bcs_url.$chapters_url
							);
			}
		}
		return false;
	}

	static function cp_bcs($original_file,$target_file)
	{
		$shell = "{$_SERVER['BCS_SHELL_DIR']} cp {$original_file} {$target_file}";
		exec($shell,$reset);
		$reset = implode(" ",$reset);
		return strpos($reset,'success');
	}

	static function merge_text($file_list,$book_id,$book_name)
	{
		global $config;

		if (empty($file_list))
		{
			return false;
		}

		/* 创建临时文件 */
		$filename = tempnam($_SERVER['ZHUAYI_CACHE_DIR'],'zhuayi');
		$book_url = str_replace("#book_id#",self::_get_md5_book_id($book_id),self::$book_url);

		$content = "<<{$book_name}>> {$config['web']['webname']}({$config['web']['weburl']}) \n\n";

		foreach ($file_list as $val)
		{
			$content .= "\n\n{$val['title']}\n\n";
			$content .= file_get_contents($val[0]);
		}
		if (file_put_contents($filename,$content))
		{
			if (self::cp_bcs($filename,self::$bcs_book_url.$book_url) !== false)
			{
				return self::$bcs_url.$book_url;
			}
			else
			{
				return false;
			}
		}
		return false;
	}

	function replace_chapters_content($content)
	{
		$content = str_replace("</p>",'',$content);
		$content = str_replace("<p>","\n",$content);
		return $content;
	}

	function create_epub($file_list,$book_id,$book_name,$book_description,$litpic)
	{
		global $config;

		if (empty($file_list))
		{
			return false;
		}
		
		$book = $this->load_class('zepub');
	
		$book->set_book_info($book_name,$book_description,$litpic);

		date_default_timezone_set('Europe/Berlin');

		foreach ($file_list as $key=>$val)
		{
			$book->addChapter($val['title'], "{$key}.html", file_get_contents($val[0]));
		}

		

		/* 创建临时文件 */
		$filename = tempnam($_SERVER['ZHUAYI_CACHE_DIR'],'zhuayi');

		$content = $book->create_epub();

		$book_url = str_replace("#book_id#",self::_get_md5_book_id($book_id),self::$book_epub_url);
		if (file_put_contents($filename,$content))
		{
			if (self::cp_bcs($filename,self::$bcs_book_url.$book_url) !== false)
			{
				return self::$bcs_url.$book_url;
			}
			else
			{
				return false;
			}
		}

		return false;
	}
}
?>