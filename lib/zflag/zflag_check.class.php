<?php
/**
* zflag_check.class.php     Zhuayi zflag 检查类
*
* @copyright    (C) 2005 - 2010  Zhuayi
* @licenes      http://www.zhuayi.net
* @lastmodify   2010-10-27
* @author       zhuayi
* @QQ           2179942
*/

class zflag_check extends zhuayi
{
	private static $_instance;

	function __construct()
    {
		
    }

    //单例方法,用于访问实例的公共的静态方法
    public static function getInstance()
    {
        if(!(self::$_instance instanceof self))
        {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

	// 开关检查
	public function _check_feature_by_switch($value)
	{
		//check code
		return true;
	}

	// 城市检查
	public function _check_feature_by_city($value)
	{
		//check code
		return true;
	}

	// IP检查
	public function _check_feature_by_ip($value)
	{
		//check code
		return true;
	}

	// 时间检查
	public function _check_feature_by_date($value)
	{
		//check code
		return true;
	}
}