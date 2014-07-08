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
	
###Zhuayi 功能强大

	Zhuayi 拥有全范围的类库，可以完成大多数通常需要的网络开发任务，包括： 读取数据库、发送电子邮件、数据确认、保存 session 、对图片的操作，以及支持 XML-RPC 数据传输等。

###Zhuayi 是可扩展的

	这个系统可以非常简单的通过自定义类库、辅助函数来进行扩展，或者也可以通过扩展类、系统钩子来实现。


***

#### 独立的配置文件,减少各应用间的耦合
> 路径存放 `$PATH/conf/appname/app.conf`

```
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
; 全局配置
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
debug = true;
title = dota2;
session_name = dota2;
admin_session_name = zhuayi


;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
; SESSION配置
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

[session]
;Session Cookie 的名字。
session_cookie_name = ZHUAYISESSID;

;session 持续的秒数。默认是2个小时(7200秒)。如果将这个数值设为: 0，就可以得到 永久 session。
session_expiration = 86400;

;cookie设置域名,为空为当前域名
session_cookie_domain = ;

;以什么方式存储session,支持file,memcache
session_save_handler = file;

;session存储位置,session_save_handler为file时,该参数才有效
session_save_path = ;



;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
; 缓存配置
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
[cache]
use_cache = on
memcache_host = 127.0.0.1:11211,127.0.0.1:11211

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
; rewire
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
[rewire]
;/index/index = 'index.html';


;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
; Smarty模板的配置
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

[smarty]
; 同时支持绝对路径和相对路径，后面的目录配置也类似的
template_dir = template/

; 模板编译目录
compile_dir = data/smarty/compile/

; 是否检测模板更新
; 0 - 不检测，有助于提升性能
; 1 - 检测
compile_check = 0

; 配置目录
config_dir = data/smarty/config/

; 预加载的产品全局配置文件，必须相对于config_dir
; 留空表示不需要预加载
config_load =

; 插件目录
plugins_dir = 

; 左右通配符
left_delimiter = "{%"
right_delimiter = "%}"


```
# cli下PHP执行的一个例子
```
#!/usr/bin/php
<?php
/**
 * dnspod_modify_record.php     Zhuayi DNS修改A解析记录
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 * @explam  php /dnspod/script/dnspod/dnspod_domain_list.php -domain dns.yeweinan.com -value 127.0.0.1 -u ** -p **
 */

define('APP_ROOT', dirname(dirname(dirname(__FILE__))));
require APP_ROOT."/../../core/zhuayi.php";

class dnspod_modify_record extends action
{

    const  GET_DOMAIN_LIST  = 'https://dnsapi.cn/Domain.List';

    const  GET_DOMAIN_ALIAS_LIST = 'https://dnsapi.cn/Record.List';

    const  MODIFY_RECORD = 'https://dnsapi.cn/Record.Modify';

    const  OUT_IP_URL = 'http://iframe.ip138.com/ic.asp';

    public  $cache_file;

    public $post_array = array(
                                'format' => 'json',
                              );

    public $domain;

    public $record;

    public function __construct()
    {
        $this->post_array['login_email'] = $this->input->get['-u'];
        $this->post_array['login_password'] = $this->input->get['-p'];

        if (empty($this->post_array['login_email']) || empty($this->post_array['login_password']))
        {
            throw new Exception("username or password is not value", -1);
        }

        if (empty($this->input->get['-value']))
        {
            /* 获取外网IP */
            $this->http->get(self::OUT_IP_URL);
            preg_match('/\[(.*?)\]/i',$this->http->results,$this->input->get['-value']);

            $this->input->get['-value'] = $this->input->get['-value'][1];

            if (empty($this->input->get['-value']))
            {
                throw new Exception("is not find ip by ".self::OUT_IP_URL, -1);
            }
        }

        $this->cache_file = ZHUAYI_ROOT."/data/".APP_NAME."/ip.cache";

        /* 读取缓存,看是否有IP变化 */
        $cache = file_get_contents($this->cache_file);

        if ($cache == $this->input->get['-value'])
        {
            $this->log->notice("cache_ip {$cache} == {$this->input->get['-value']}");
            exit;
        }
    }

    
    function run()
    {
        /* 取顶级域名 */
        $this->input->get['-domain'] = $this->_reset_domain($this->input->get['-domain']);
    
        $domain_list = $this->get_domain_alias_by_domain($this->input->get['-domain']['top_domain'].".".$this->input->get['-domain']['postfix']);

        /* 取需要解析的IP */
        if (empty($this->input->get['-domain']['second_domain']))
        {
            $this->input->get['-domain']['second_domain'] = "@";
        }
       
        $this->record = $domain_list[$this->input->get['-domain']['second_domain']];
  
        /* 修改记录 */
        $this->_update_alias_by_domain_id();
    }

    function _update_alias_by_domain_id()
    {
        if (empty($this->domain['id']) || empty($this->record['id']))
        {
            throw new Exception("domain_id or record_id is not value !!", -1);
        }

        /* 修改解析 */
        $this->post_array['domain_id'] = $this->domain['id'];
        $this->post_array['record_id'] = $this->record['id'];
        $this->post_array['record_type'] = 'A';
        $this->post_array['record_line'] = '默认';
        $this->post_array['sub_domain'] = $this->input->get['-domain']['second_domain'];
        $this->post_array['value'] = $this->input->get['-value'];

        unset($this->post_array['domain']);

        $results = $this->_post(self::MODIFY_RECORD,$this->post_array);

        $this->file->write(ZHUAYI_ROOT."/data/".APP_NAME."/ip.cache",$results['record']['value']);

        $this->log->notice("{$this->record['name']}.{$this->domain['name']} == {$results['record']['value']} is {$results['status']['message']}");
    }


    /* 获取域名记录列表 */
    function get_domain_alias_by_domain($domain)
    {
        $this->post_array['domain'] = $domain;

        $results = $this->_post(self::GET_DOMAIN_ALIAS_LIST,$this->post_array);

        $this->domain = $results['domain'];

        $results = $results['records'];

        foreach ($results as $val);
        {
            $array_tmp[$val['name']] = $val;
        }
        return $array_tmp;

    }


    function get_domain_list()
    {
        $domains = $this->_post(self::GET_DOMAIN_LIST,$this->post_array);
        $domains = $domains['domains'];
        foreach ($domains as $val)
        {
            $domains_tmp[$val['name']] = $val;
        }

        return $domains_tmp;
    }

    function _reset_domain($domain)
    {
        /* 取后缀 */
        $dmtypeRegx = '/(.*?)(com\.cn|org\.cn|net\.cn|com\.jp|co\.jp|com\.kr|com\.tw|cn|com|org|info|us|fr|de|tv|net|cc|biz|hk|jp|kr|name|me|tw|la|pw)$/i';  
        preg_match($dmtypeRegx, $domain, $matches);

        if (empty($matches))
        {
            return false;
        }
        $postfix = $matches[2];
        $domain = $matches[1];

        /* 判断是几级域名 */
        $domain = explode('.',$domain);
        if (count($domain) == 3)
        {
            if ($domain[0] == 'www')
            {
                unset($domain[0]);
            }
        }
        /* 判断万网该死的域名 */
        if (count($domain) == 2 && $domain[0] == 'www')
        {
            $domain = array();
        }
        if (count($domain) > 2 )
        {
            $second_domain = reset($domain);
        }

        $top_domain = end(array_filter($domain));
        return array('top_domain'=>$top_domain,'second_domain'=>$second_domain,'domain'=>$domain,'postfix'=>$postfix);
    }

    function _post($url,$post_array)
    {
        $this->http->post($url,$post_array);

        $this->http->results = json_decode($this->http->results,true);

        if ($this->http->results['status']['code'] != 1)
        {
            throw new Exception($this->http->results['status']['message'], $this->http->results['status']['code']);
        }

        return $this->http->results;
    }
}

zhuayi::cil();
```