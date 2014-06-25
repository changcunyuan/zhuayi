<?php
/**
* zflag.class.php     Zhuayi zflag 控制分发
*
* @copyright    (C) 2005 - 2010  Zhuayi
* @licenes      http://www.zhuayi.net
* @lastmodify   2010-10-27
* @author       zhuayi
* @QQ           2179942
* demo
* *********************************************************
* if ($this->zflag->feature("features:featureA"))
* {
*   	echo "[code...]";
* }
* else
* {
*   	echo "code...";
* }
* *********************************************************
*	type分类 ：
*	 -> switch ： 1/0控制feature是否生效；
*	 -> Ip : 通过ip范围控制是否生效； 例如：172.22.1.16-172.22.1.65；
*	 -> Percentage : 通过百分比控制；例如0.03，开发百分之三的用户 (暂缓开通)；
*	 -> date : 通过日期控制，规定日期之间时间feature为on，其他时间时间feature为off； 例如 2013-12-23 15:00:00 | 2013-12-23 15:00:00，feature测试两天
*	 -> roles : 用户角色(暂不实现)
*/
require dirname(__FILE__)."/zflag_check.class.php";

class zflag extends zhuayi
{
	private static $_instance;

	private $config ;

	private $feature_type = array(
									'switch',
									'city',
									'ip',
									'date'
								 );

	function __construct()
    {
		$this->config = parent::get_conf('flag');       
    }

    public function __invoke($fun_name)
    {
    	return true;
    }

    public static function getInstance()
    {
        if(!(self::$_instance instanceof self))
        {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * 取配置文件,参照.ini 规则
     *	[features]
	 *	featureA[type] = city;
	 *	featureA[value] = beijing;
	 *	featureA[switch] = true;
	 *	[....]
     */
    private function getConfig()
    {
    	return parent::get_conf('flag');
    }

    /**
     * 取配置文件,参照.ini 规则
     */
	public function feature($feature_name = '')
	{
		return $this->check_feature($feature_name);
	}

	/**
	 * 检查是否需要允许执行
	 */
	public function check_feature($feature_name)
	{
		if (empty($feature_name))
		{
			throw new Exception("No such flag config", -1);
		}
		list($parent_name,$child_name) = explode(":",$feature_name);
		// 如果不存在配置,直接返回 false
		if (!isset($this->config[$parent_name]) 
				|| !isset($this->config[$parent_name][$child_name])
				|| !in_array($this->config[$parent_name][$child_name]['type'],$this->feature_type)
			)
		{
			return false;
		}

		$check_function = (string)sprintf("_check_feature_by_%s",$this->config[$parent_name][$child_name]['type']);
		return zflag_check::getInstance()->$check_function($this->config[$parent_name][$child_name]['value']);
	}
}