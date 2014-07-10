<?php
/**
 * http.class.php     Zhuayi CURL 操 作 类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ             2179942
 * 
 * ------------------------------------------------
 * $this->load_class('http',true);
 * 
 * // 设 置 来 路
 * $this->http->referer = 'http://www.baidu.com';
 * 
 * // 设 置 COOKIE 
 * $cookie = array();
 * $cookie['cookie1_key'] = 'cookie1_val';
 * $cookie['cookie2_key'] = 'cookie2_val';
 * $this->http->cookie = $cookie;
 * 
 * // 设 置 POST 提 交 ,
 * $this->http->post(url,参 数);
 * 
 * // 设 置 POST 提 交 并 上 传 文 件,
 * $this->http->post(url,array('参 数'=>' 参 数 1 值',filename'=>'@$val'));
 * 
 * // 设 置 GET
 * $this->http->get(url,array('参 数'=>' 参 数 1 值'...));
 * -------------------------------------------------
 */

class http 
{
    
    /*  超 时 时 间 */
    var $timeout = 10000;

    /* 伪 造 来 路 */
    var $referer = '';
    
    var $agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.1';
    
    var $accept = "image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*";
    
    /* 验证用户 username:password*/
    var $userpassword = '';

    /* 绑定HOSTS*/
    var $host = '';

    /* CURL 返 回 错 误 */
    var $error = '';

    /* 重定向次数 */
    var $redirect = 0;

    var $_redirect = 0;

    var $post_urlencode = false;

    var $range = 0; //CURLOPT_RANGE 返回字节数, 为0则不设置该参数


        /**
        * 构造函数
        */
    function __construct()
    {
        $this->curl = curl_init();
    }

    function exec($url,$method = 'GET',$parame = array()) 
    {
        
        $this->parame = $parame;

        /* 如果URL传递为数组,则认定为需要绑定HOSTS */
        if (is_array($url))
        {
            $this->host = $url[1];
            $url = $url[0];
        }

        /* 格 式 化 URL */
        $url_parts = parse_url($url);
        
        if (is_array($parame) && $method == 'GET')
        {
            $encoded = '';
            foreach($parame as $key => $value)
            {
                if ($this->post_urlencode)
                {
                    $value = urlencode($value);
                }
                $encoded .= urlencode($key).'='.$value.'&';
            }
            $parame = substr($encoded, 0, strlen($encoded)-1);
        }

        /* POST 提 交 */
        if ($method == 'POST')
        {
            /* oauth授权时需要urlencode */
            if ($this->post_urlencode)
            {
                $postBodyString = "";
                foreach ($parame as $k => $v)
                {
                    $postBodyString .= "$k=" . urlencode($v) . "&"; 
                }
                $parame = $postBodyString;
            }
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $parame);
        }

        /* GET 提 交 */
        if ($method == 'GET')
        {
            if (strpos($url,'?') === false )
            {
                if (!empty($parame))
                {
                    $url .= '?'.$parame;
                }
            }
            else
            {
                $url .= "&".$parame;
            }
        }

        /* GET 提 交 */
        if ($method == 'PUT')
        {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $parame);
        }

        $this->request_url = $url;
        curl_setopt($this->curl, CURLOPT_URL,$url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($this->curl, CURLOPT_TIMEOUT_MS,$this->timeout);
        curl_setopt($this->curl, CURLOPT_HEADER, 1);
        
        /* 支 持 SSL */
        if ($url_parts['scheme'] == 'https')
        {
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false); 
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (!empty($this->userpassword))
        {
            curl_setopt($this->curl,CURLOPT_USERPWD,$this->userpassword);
        }
        
        /* 返回字节数 */
        if (!empty($this->range))
        {
            curl_setopt($this->curl, CURLOPT_RANGE, '0-'.$this->range); 
        }


        /* 伪 造 来 路 页 面 */
        if (!empty($this->referer))
        {
            curl_setopt($this->curl, CURLOPT_REFERER, $this->referer); 
        }
        
        /* 模 拟 浏 览 器 */
        if (!empty($this->agent))
        {
            curl_setopt($this->curl,CURLOPT_USERAGENT,$this->agent); 
        }
        
        /* 设 置 cookie */
        if (!empty($this->cookie))
        {
            curl_setopt($this->curl, CURLOPT_COOKIE , $this->_cookies($this->cookie) );
        }

        if (!empty($this->encode))
        {
            curl_setopt($this->curl, CURLOPT_ENCODING, $this->encode);
        }
        
        /* 设置header头 */
        $httpheader['CONNECTION'] = '""';
        $httpheader['Accept-Language'] = 'zh-CN,zh;q=0.8';
        $httpheader['Cache-Control'] = 'no-cache';
        $httpheader['ACCEPT'] = $this->accept;
        $httpheader['ACCEPT-CHARSET'] = 'GBK,utf-8;q=0.7,*;q=0.3';
        //exit;
        if (!empty($this->host))
        {
            $httpheader['host'] = $this->host;
        }
        
        foreach ($httpheader as $key=>$val)
        {
            $httpheaders[] = $key.':'.$val;
        }
        curl_setopt($this->curl,CURLOPT_HTTPHEADER,$httpheaders);

        $this->results = curl_exec($this->curl) or $this->error = curl_error($this->curl);
        $this->status = curl_errno($this->curl);
        
        /* 返 回 状 态 吗 */
        $this->http_status = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $this->content_type = curl_getinfo($this->curl, CURLINFO_CONTENT_TYPE);
        /* 执行时间 */
        $this->total_time = curl_getinfo($this->curl, CURLINFO_TOTAL_TIME);

        /* 格式化COOKIE */
        $this->setcookies();

        /* 如果为302 则跳转 */
        if ($this->redirect > 0 && $this->_redirect < $this->redirect && $this->http_status == 302 && !empty($this->Location))
        {
            /* 增加重定向次数 */
            $this->_redirect++;
            $this->referer = $url;
            $this->get($this->Location);
        }

        

        if (isset($_GET['http_debug']))
        {
            echo "<!--http:\n";
            echo " method: {$method}\r\n";
            echo " url: {$url}\r\n";
            if (is_array($parame))
            {
                echo " parame: ".http_build_query($parame)."\r\n";
            }
            else
            {
                echo " parame: ".$parame."\r\n";
            }
            
            $parent = print_r( curl_getinfo($this->curl) ,true);
            echo $parent;
            //echo " info:{$this->results}\r\n";
            echo "-->\r\n";
        }
        //curl_close($this->curl);
        //unset($this->curl);
    }
    
    /**
     * _cookies 转 换 cookie
     *
     * @param string $array 
     * @param string $f 
     * @return void
     * @author zhuayi
     */
    function _cookies($array = '')
    {
        if (empty($array) || !is_array($array))
        {
            return $array;
        }

        foreach ($array as $key=>$val)
        {
            $cookie[] = $key."=".$val;
        }

        return implode(';',$cookie);
    }
    
    /**
     * get
     *
     * @param string $url 
     * @param string $array 
     * @return void
     * @author zhuayi
     */
    function get($url,$array = array())
    {
        $this->exec($url,'GET',$array);
        return $this;
    }


    /**
     * put
     *
     * @param string $url 
     * @param string $array 
     * @return void
     * @author zhuayi
     */
    function put($url,$strings)
    {
        if (is_array($strings))
        {
            $strings = http_build_query($strings);
        }
        $this->exec($url,'PUT',$strings);
        return $this;
    }
    
    /**
     * get_links
     *
     * @param string $reg 必须包含的连接
     * @return void
     * @author zhuayi
     */
    function links($reg = '')
    {
        $this->results = $this->_striplinks($this->results);
        if ($reg == '')
        {
            return $this->results ;
        }
        else
        {
            foreach ($this->results as $key=>$val)
            {
                if (!strpos($val,$reg))
                {
                    unset($this->results[$key]);
                }
            }
            return $this->results;
        }
        
    }
    
    /**
     * post 
     *
     * @param string $url 
     * @param string $array 
     * @return void
     * @author zhuayi
     */
    function post($url,$array = array(),$multi = false)
    {
        $this->exec($url,'POST',$array,$multi);
    }

    function _striplinks($document)
    {
        preg_match_all("'<\s*a\s.*?href\s*=\s*            # find <a href=
                        ([\"\'])?                    # find single or double quote
                        (?(1) (.*?)\\1 | ([^\s\>]+))        # if quote found, match up to next matching
                                                    # quote, otherwise match up to next space
                        'isx",$document,$links);
                        

        // catenate the non-empty matches from the conditional subpattern
        $match = array();
        while(list($key,$val) = each($links[2]))
        {
            if(!empty($val))
                $match[] = $val;
        }                
        
        while(list($key,$val) = each($links[3]))
        {
            if(!empty($val))
                $match[] = $val;
        }        
        
        // return the links
        return $match;
    }

    function setcookies()
    {
        $results = explode("\r\n\r\n",$this->results);

        //$this->results = end($results);
        $this->results_tmp = array();
        $headers = array();
        foreach ($results as $val)
        {
            if (strpos('^'.$val,'HTTP/1.1') > 0)
            {
                $headers[] = $val;
            }
            else
            {
                $this->results_tmp[] = trim($val);
            }
        }
        $headers = implode('\n', $headers);
        $headers = explode("\n", $headers);
        $this->results = implode("\n", $this->results_tmp);

        foreach ($headers as $key=>$val)
        {
            if (!empty($val) && $key > 0  && preg_match("/^[a-z\:]/i",substr($val,0,20)) && $key<20)
            {
                /* 取出COOKIE */
                if(preg_match('/^set-cookie:[\s]+(.*)/i', $val,$match_cookie))
                {
                    $match_cookie = explode(';', $match_cookie[1]);

                    foreach ($match_cookie as $val)
                    {
                        $val = explode('=',$val,2);
                        $this->cookie[trim($val[0])] = urldecode($val[1]);
                    }
                }
                /*if(preg_match('/^set-cookie:[\s]+([^=]+)=([^;]+)/i', $val,$match))
                {
                    $match = explode(';', $match[1]);
                    foreach ($match as $val)
                    {
                        $val = explode('=', $val);
                        $this->cookie[trim($val[0])] = urldecode($val[1]);
                    }
                    //$this->cookie[$match[1]] = urldecode($match[2]);
                }

                $match = array();*/
                /* 取出Location */
                if (!is_array($val) && strpos(strtolower($val),'location:') !== false)
                {
                    preg_match('/^location:(.*)/i', $val,$match);
                    $this->Location = urldecode(trim($match[1]));
                }
                $this->headers[] = $val;
            }

            if ($key > 20)
            {
                break;
            }

        }

        unset($this->results_tmp);
    }

}