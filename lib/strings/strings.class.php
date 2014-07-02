<?php
/*
 * strings.php     Zhuayi 字符串操作类
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ           2179942
 */

 class strings
 {
    function __construct() 
    {
        
    }

    /**
     * limit 根据指定的字符数目对一段字符串进行截取
     *
     * 截取字符串(UTF-8)
     *
     * @param string $string 原始字符串
     * @param $start 开始截取位置
     * @param $len 需要截取的偏移量
     * $type=1 等于1时末尾加'...'不然不加
     **/
    function limit($string, $start, $len, $byte=3)
    {
        $string = htmlspecialchars_decode($string);
        $string = str_replace('<br />','\n',$string);
        $string = strip_tags($string);
        if (empty($string))
        {
            return $string;
        }
        if(strlen($string)<3*$len)
        {
            return $string;
        }
        
        $re = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        preg_match_all($re, $string, $match);
        $string = join("",array_slice($match[0], $start, $len));
        return str_replace('\n','<br />',$string);
    }

    /**
     * iconvn 万能字符串转码
     *
     * @return void
     * @author 
     **/
    function iconvn($string,$outcode='utf-8//IGNORE')      
    {

        $incode = mb_detect_encoding($string, array('ASCII','gb2312','gbk','utf-8','utf-16'));

        if (empty($incode))
        {
            $incode = 'GBK';
        }
        if ($incode == $outcode)
        {
            return $string;
        }
        else
        {
            return iconv($incode,$outcode, $string);
        }
        return $encode;
    }

    /**
     * replace_a 过滤HTML
     *
     * @return void
     * @author 
     **/
    function replace_a($string)
    {
        $string = htmlspecialchars_decode($string);
        $string =  preg_replace('/<a(.*?)href=(.*?)>(.*?)<\/a>/i',"$3" , $string);
        return htmlspecialchars($string);
    }

    /**
     * replace_a 过滤HTML,但保留BR换行
     *
     * @return void
     * @author 
     **/
    function replace_html_no_br($string)
    {
        $string = htmlspecialchars_decode($string);
        $string = str_replace('<br />','\n',$string);
        $string = strip_tags($string);
        return str_replace('\n','<br />',$string);
    }

    /**
     * strip 过滤HTML
     *
     * @return void
     * @author 
     **/
    function strip($string)
    {
        $string = htmlspecialchars_decode($string);
        return strip_tags($string);
    }

    /**
     * mymd5 加密字符串
     *
     * @return void
     * @author 
     **/
     function mymd5($string)
     {
        return md5($string.md5($string));
     }

     /**
     * compress 压缩字符串
     *
     * @return void
     * @author 
     **/
    function compress($string)
    {
        $string = preg_replace("/(^http:)*\/\/[\S^;]*;/","",$string);
        $string = preg_replace("/\<\!\-\-[\s\S]*?\-\-\>/","",$string);
        $string = preg_replace("/\>[\s]+\</","><",$string);
        $string = preg_replace("/;[\s]+/",";",$string);
        $string = preg_replace("/[\s]+\}/","}",$string);
        $string = preg_replace("/}[\s]+/","}",$string);
        $string = preg_replace("/\{[\s]+/","{",$string);
        $string = preg_replace("/([\s]){2,}/","$1",$string);
        return preg_replace("/[\s]+\=[\s]+/","=",$string);
    }

    function isUTF8($str)
    {
       if ($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32")) {
           return true;
       }
       else
       {
           return false;
       }
    }

    /**
     * 计算内容中的字数，未对长连接特殊处理。
     * 规则同微博(汉字1，字母、数字1/2)
     * @param string $content
     * @return number
     */
    function get_str_len($str)
    {
        return ceil(strlen(iconv('UTF-8','GBK', $str))/2);
    }


    static function php_crc32($value)
    {
        
        return sprintf("%u", crc32($value));
        
    }

    function un_script_code($str)
    {
        $s          = array();
        $s["/<script[^>]*?>.*?<\/script>/si"] = "";
        return strings::filt_string($str, $s, true);
    }

    /**
     * 过滤字符串中的特殊字符
     * @return string
     * @param string $str 需要过滤的字符
     * @param string $filtStr 需要过滤字符的数组（下标为需要过滤的字符，值为过滤后的字符）
     * @param boolen $regexp 是否进行正则表达试进行替换，默认false
     */
    
    function filt_string($str, $filtStr, $regexp = false)
    {
        $str = htmlspecialchars_decode($str);

        if (!is_array($filtStr))
        {
            return $str;
        }
        $search     = array_keys($filtStr);
        $replace    = array_values($filtStr);
                
        if ($regexp)
        {
            return preg_replace($search, $replace, $str);
        }
        else
        {
            return str_replace($search, $replace, $str);
        }
    }

    /* 序列化数据的时候的编码和反序列化时的编码不一样导致字符串的长度出现偏差,导致unserialize失败 */
    function unserialize($strings)
    {
        $strings = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'",$strings );

        return unserialize($strings);
    }

    /**
    *十进制转二进制、八进制、十六进制 不足位数前面补零*
    *
    * @param array $datalist 传入数据array(100,123,130)
    * @param int $bin 转换的进制可以是：2,8,16
    * @return array 返回数据 array() 返回没有数据转换的格式
    * @Author chengmo QQ:8292669
    * @copyright http://www.cnblogs.com/chengmo 
    */
    function decto_bin($datalist,$bin)
    {
        static $arr = array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F');
        if(!is_array($datalist))
        {
            $datalist = array($datalist);
        }
        //相同进制忽略
        if($bin==10)
        {
            return $datalist;
        }

        //获得如果是$bin进制，一个字节的长度
        $bytelen = ceil(16/$bin);
        $aOutChar = array();
        foreach ($datalist as $num)
        {
            $t ="";
            $num = intval($num);
            if($num === 0)continue;
            while( $num > 0 )
            {
                $t = $arr[$num%$bin].$t;
                $num = floor($num/$bin);
            }

            $tlen = strlen($t);
            if( $tlen % $bytelen!=0 )
            {
                $pad_len = $bytelen - $tlen % $bytelen;
                $t = str_pad("",$pad_len,"0",STR_PAD_LEFT).$t; //不足一个字节长度，自动前面补充0
            }
            $aOutChar[] = $t;
        }
        return $aOutChar;
    }

    /**
     *二进制、八进制、十六进制 转十进制*
     *
     * @param array $datalist 传入数据array(df,ef)
     * @param int $bin 转换的进制可以是：2,8,16
     * @return array 返回数据 array() 返回没有数据转换的格式
     * @copyright chengmo QQ:8292669
     */
    function bin_todec($datalist,$bin)
    {
        static $arr=array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9,'A'=>10,'B'=>11,'C'=>12,'D'=>13,'E'=>14,'F'=>15);
        if(!is_array($datalist))$datalist=array($datalist);
        if($bin==10)return $datalist; //为10进制不转换
        $aOutData=array(); //定义输出保存数组
        foreach ($datalist as $num)
        {
            $atnum=str_split($num); //将字符串分割为单个字符数组
            $atlen=count($atnum);
            $total=0;
            $i=1;
            foreach ($atnum as $tv)
            {
                $tv=strtoupper($tv);
                 
                if(array_key_exists($tv,$arr))
                {
                    if($arr[$tv]==0)continue;
                    $total=$total+$arr[$tv]*pow($bin,$atlen-$i);
                }
                $i++;
            }
            $aOutData[]=$total;
        }
        return $aOutData;
    }

    /**
     * 取字符串之间
     */
    static function get_section_strings($strings,$start,$end)
    {
        $_strings = explode($start,$strings);
        $_strings = explode($end,$_strings[1]);
        return $_strings[0];
    }

    /**
     * 时间转换
     */
    static function formatTime($date)
    {
        $str = '';
        $timer = strtotime($date);
        $diff = $_SERVER['REQUEST_TIME'] - $timer;
        $day = floor($diff / 86400);
        $free = $diff % 86400;
        if($day > 0)
        {
            return $day."天前";
        }
        else
        {
            if($free>0)
            {
                $hour = floor($free / 3600);
                $free = $free % 3600;
                if($hour>0)
                {
                    return $hour."小时前";
                }
                else
                {
                    if($free>0)
                    {
                        $min = floor($free / 60);
                        $free = $free % 60;
                        if($min>0)
                        {
                            return $min."分钟前";
                        }
                        else
                        {
                            if($free>0)
                            {
                                return $free."秒前";
                            }
                            else
                            {
                                return '刚刚';
                            }
                        }
                    }
                    else
                    {
                        return '刚刚';
                    }
                }
            }
            else
            {
                return '刚刚';
            }
        }
    }
 }