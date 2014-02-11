### cmd下执行PHP时,如何使用SERVER变量和argv参数

```
php  cron_test.php  -c /data/vhosts/baidu_soft.conf  
```

>zhuayi框架会对 `argv` 参数  进行了格式化数组处理,处理结果放在`argv_array`数组中,直接`global`引用即可!


***

### 由于依赖的SERVER变量存放在vhosts中(如下),所以在cmd下执行php是获取不到`SERVER`变量的

```
<VirtualHost *>
    <Directory "/data/site/code.csdn.net/zhuayi/">
        Options -Indexes FollowSymLinks
        Allow from all
        AllowOverride All
    </Directory>
    ServerAdmin admin@www.zhuayi.net
    DocumentRoot "/data/site/code.csdn.net/zhuayi"
    ServerName zhuayi:80
    ErrorLog "/private/var/log/apache2/zhuayi-error_log"
    CustomLog "/data/logs/apache/zhuayi/access_log" common
    
    SetEnv BAIDU_CMS_MEMCACHE 127.0.0.1:11211
    SetEnv BAIDU_MEMCACHED_KEY zhuayi
    SetEnv BAIDU_MEMCACHED_OUTTIME 3600
    SetEnv DEBUG true
    ##SESSION##
    SetEnv COOKIE_PATH tcp://127.0.0.1:11211
    SetEnv COOKIE_HANDLER memcache
    SetEnv COOKIE_TIMEOUT 1200
</VirtualHost>

```
[https://code.csdn.net/zhuayi/zhuayi/tree/master/apache.server](https://code.csdn.net/zhuayi/zhuayi/tree/master/apache.server)

*需要引用apache或者nginx的SERVER变量时可以使用  `-c` 参数指定到apache的vhosts或者nginx的conf文件.*

# cmd php example
```
<?php
/*
 * cron_test.php     命令行下执行PHP

 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 * php cron_test.php -c /data/vhosts/baidu_soft.conf -soft_id  ~/Downloads/soft_id  -out ~/Downloads/hao123.xml
 */
include_once "../cron/cron.inc.php";
class cron_test extends zhuayi
{
	/* 脚本最大执行时间 */
	public $timeout = 1200;
	public $run_start_time;
	
	/* 构造函数 */
	function __construct()
	{
		parent::__construct();
		$this->load_class('db',false);
		parent::$admin = true;
	}

	function run()
	{
		global $argv_array;
		print_r($argv_array);
		print_r($_SERVER);
	}
}

$cron = new cron_test();
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
```