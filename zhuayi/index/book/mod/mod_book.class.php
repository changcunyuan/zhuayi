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
	
	static $book_intro_url = "/detail/#book_name#/#book_id#";

	
	static function replace_description($params)
	{
		extract($params, EXTR_OVERWRITE);
		$description = str_replace("&nbsp;","",$description);
		$description = htmlspecialchars_decode($description);
		// $description = strip_tags($description);
		// $description = str_replace("\n","<br/>",$description);
		// $description = str_replace("\r","<br/>",$description);
		$description = str_replace("　　","",$description);

		return $description;
	}
}
?>