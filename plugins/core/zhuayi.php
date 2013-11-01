<?php

//加载URL路由
require PLUGINS_ROOT.'/core/url.class.php';

//加载主框架文件
require PLUGINS_ROOT.'/core/zhuayi.class.php';

//加载缓存文件
require_once PLUGINS_ROOT.'/core/cache.class.php';

// 加载文件操作类
// require_once PLUGINS_ROOT.'/core/file.class.php';

// 加载应用公共静态方法
require_once ZHUAYI_ROOT.'/zhuayi/mod_app.php';

//默认实例化类
spl_autoload_register(array('zhuayi', '_load_class'));

try
{
     //开启cache缓存
    if (function_exists("memcache_get"))
    {
        $cache = new mem_cache();
        /* 加载缓存key配置文件 */
        require_once ZHUAYI_ROOT.'/config/app_cache_variable.config.php';
    }
    else
    {
        throw new Exception("该环境不支持memcache!", -1);
    }
		
}
catch (Exception $e)
{
	throw new ZException("page",$e->getMessage(), $e->getCode());
}



/**
 * --------------------------------
 * 加载文件操作
 * --------------------------------
 */

#$file = new file();


/* 加载输入类 */
$input = new input();

/**
 * --------------------------------
 * 强制关闭转义
 * --------------------------------
 */
ini_set("magic_quotes_runtime", 0);

//处理被 get_magic_quotes_gpc 自动转义的数据,转换为HTML实体
$in = array(& $_GET, & $_POST, & $_COOKIE, & $_REQUEST);
while (list ($k, $v) = each($in))
{
    foreach ($v as $key => $val)
    {
        if (! is_array($val))
        {
            //$in[$k][$key] = htmlspecialchars(stripslashes($val));
            $in[$k][$key] = htmlspecialchars($val);
            continue;
        }
        $in[] = & $in[$k][$key];
    }
}
unset($in);

/* 处理32位机器,big_intval溢出的问题 */
function big_intval($int)
{
    return floor(floatval($int));
}

function ob_gzip($content)
{
    if(!headers_sent() && // 如果页面头部信息还没有输出
        extension_loaded("zlib") && // 而且zlib扩展已经加载到PHP中
        strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip")) //而且浏览器说它可以接受GZIP的页面 
    {
        $content = gzencode($content,9);//此页已压缩”的注释标签，然后用zlib提供的gzencode()函数执行级别为9的压缩，这个参数值范围是0-9，0表示无压缩，9表示最大压缩，当然压缩程度越高越费CPU。
        
        //然后用header()函数给浏览器发送一些头部信息，告诉浏览器这个页面已经用GZIP压缩过了！
        header("Content-Encoding: gzip"); 
        header("Vary: Accept-Encoding");
        header("Content-Length: ".strlen($content));
    }
    return $content;
}
?>