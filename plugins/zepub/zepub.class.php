<?php
/**
 * bdupssp.class.php     Zhuayi bdup 单点登陆验证
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ			 2179942
 * zhuayi框架调用, $this->load_class('bdup');
 * 其他框架调用 $bdup = bdup::getInstance();
 **/
include_once ZHUAYI_ROOT."/plugins/zepub/EPub.php";
include_once ZHUAYI_ROOT."/plugins/zepub/EPubChapterSplitter.php";

class zepub 
{
	private static $_instance;

	private $epub = NULL;
	/**
	 * 构 造 函 数
	 *
	 * @author zhuayi
	 */
	function __construct()
	{
		self::getInstance();
	}

	//创建__clone方法防止对象被复制克隆
	public function __clone()
	{
		trigger_error('Clone is not allow!',E_USER_ERROR);
	}

	//单例方法,用于访问实例的公共的静态方法
	public static function getInstance()
	{
		if(!(self::$_instance instanceof self))
		{
			self::$_instance->epub = new EPub();
		}
		return self::$_instance;
	}

	static function get_content_start()
	{
		return "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
				. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
				. "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
				. "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
				. "<head>"
				. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
				. "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\" />\n"
				. "<title>Test Book</title>\n"
				. "</head>\n"
				. "<body>\n";
	}

	static function get_bookEnd()
	{
		return "</body>\n</html>\n";
	}

	static function addCSSFile()
	{
		$cssData = "body {\n  margin-left: .5em;\n  margin-right: .5em;\n  text-align: justify;\n}\n\np {\n  font-family: serif;\n  font-size: 10pt;\n  text-align: justify;\n  text-indent: 1em;\n  margin-top: 0px;\n  margin-bottom: 1ex;\n}\n\nh1, h2 {\n  font-family: sans-serif;\n  font-style: italic;\n  text-align: center;\n  background-color: #6b879c;\n  color: white;\n  width: 100%;\n}\n\nh1 {\n    margin-bottom: 2px;\n}\n\nh2 {\n    margin-top: -2px;\n    margin-bottom: 2px;\n}\n";
		self::$_instance->epub->addCSSFile("styles.css", "css1", $cssData);
	}

	function set_book_info($book_name,$book_description,$litpic)
	{
		global $config;

		self::$_instance->epub->setTitle($book_name);

		/* 标识符 */
		self::$_instance->epub->setIdentifier($book_name, EPub::IDENTIFIER_URI);
		self::$_instance->epub->setLanguage("en");
		self::$_instance->epub->setDescription($book_description);
		self::$_instance->epub->setAuthor("zhuayi",'zhuayi');
		self::$_instance->epub->setPublisher($config['web']['webname'], $config['web']['weburl']);
		self::$_instance->epub->setDate(time());
		self::$_instance->epub->setRights($config['web']['weburl']);
		self::$_instance->epub->setSourceURL($config['web']['weburl']);
		self::$_instance->epub->addDublinCoreMetadata(DublinCore::CONTRIBUTOR, "PHP");

		self::$_instance->epub->setSubject($book_name);

		// self::$_instance->epub->addCustomMetadata("calibre:series", $book_name);
		// self::$_instance->epub->addCustomMetadata("calibre:series_index", "2");
		self::$_instance->epub->setCoverImage("Cover.jpg", file_get_contents($litpic), "image/jpeg");
		self::addCSSFile();
	}

	function addChapter($title,$chapter_html,$content)
	{
		self::$_instance->epub->addChapter($title, $chapter_html, self::get_content_start().$content.self::get_bookEnd());
	}

	function create_epub()
	{
		//self::$_instance->epub->rootLevel();
		//self::$_instance->epub->buildTOC();
		self::$_instance->epub->finalize();
		return self::$_instance->epub->sendBook('tmp');
	}
	
}

?>