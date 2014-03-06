##zhuayi

    Zhuayi 是一套给 PHP 网站开发者使用的应用程序开发框架和工具包。它提供一套丰富的标准库以及简单的接口和逻辑结构，其目的是使开发人员更快速地进行项目开发。使用 Zhuayi 可以减少代码的编写量，并将你的精力投入到项目的创造性开发上。
    
###Zhuayi 是为谁准备的？

    * 你想要一个小巧但强大的框架。
    * 你需要出色的性能。
    * 你需要广泛兼容标准主机上的各种 PHP 版本和配置。
    * 你想要一个高效可配置的框架。
    * 你想要一个不需使用命令行的框架。
    * 你想要一个不需坚守限制性编码规则的框架。
    * 你对 PEAR 这种大规模集成类库不感兴趣。
    * 你不希望被迫学习一门模板语言（虽然可以选择你喜欢的模板解析器）。
    * 你不喜欢复杂，热爱简单。
    * 你需要清晰、完整的文档

###Zhuayi 是是轻量级的

    真正的轻量级。我们的核心系统只需要一些非常小的库，这与那些需要更多资源的框架完全相反。额外的库文件只在请求的时候加载，依需求而定，所以核心系统是非常快而且轻的。
    
###Zhuayi 是快速的

    qps达到1000+.你要找到一个比 Zhuayi 表现更优的框架应该很难吧。
    
###Zhuayi 生成干净的 URL

    Zhuayi 生成的 URL 非常干净而且是对搜索引擎友好化的。不同于标准的“字符串查询”方法，Zhuayi使用了 基于段 的方法：
    
```
example.com/news/article/345/参数1/参数2/....
```

>注意：index.php 文件是被默认包含在 URL 中的，但是可以通过更改 .htaccess 文件来改变这个设置
    
###Zhuayi 功能强大

    Zhuayi 拥有全范围的类库，可以完成大多数通常需要的网络开发任务，包括： 读取数据库、发送电子邮件、数据确认、保存 session 、对图片的操作，以及支持 XML-RPC 数据传输等。

###Zhuayi 是可扩展的

    这个系统可以非常简单的通过自定义类库、辅助函数来进行扩展，或者也可以通过扩展类、系统钩子来实现。


***

#### 由于依赖的SERVER变量存放在vhosts中(如下),所以在cmd下执行php是获取不到`SERVER`变量的

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
 * @QQ           2179942
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