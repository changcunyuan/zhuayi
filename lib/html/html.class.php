<?php
/**
 * html.class.php     Zhuayi  
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
require dirname(__FILE__)."/simple_html_dom.php";
class html 
{
    private $dom = null;

    function __construct()
    {
        
    }

    // get html dom from string
    function str_get_html($str, $lowercase=true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT)
    {
        $this->dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
        if (empty($str) || strlen($str) > MAX_FILE_SIZE)
        {
            $this->dom->clear();
            return false;
        }
        $this->dom->load($str, $lowercase, $stripRN);
        return $this->dom;
    }

    function __call($name,$arguments)
    {
        $arguments = $arguments[0];
        return $this->dom->$name($arguments);
    }
    
}