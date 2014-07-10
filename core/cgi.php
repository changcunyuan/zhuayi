<?php
/**
* index.php     Zhuayi cli 入口
*
* @copyright    (C) 2005 - 2010  Zhuayi
* @licenes      http://www.zhuayi.net
* @lastmodify   2010-10-27
* @author       zhuayi
* @QQ           2179942
*/
cgi::init();
require APP_ROOT."/../../core/zhuayi.php";
zhuayi::cil();

class cgi
{
    public static $argv;

    static function init()
    {
        $message = $_SERVER['argv'][1];
        list($appname,$request_uri) = explode(":",$message);

        if (empty($appname))
        {
            throw new Exception("no such appname", -1);
        }
        define('APP_NAME',$appname);
        define('APP_ROOT', dirname(dirname(__FILE__))."/app/".APP_NAME);
        define('APP_MODE', 'cgi');
        $_SERVER['REQUEST_URI'] = trim($request_uri);
    }

}