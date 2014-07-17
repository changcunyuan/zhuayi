<?php
/*
 * weibo.php     Zhuayi 淘宝客
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ           2179942
 */

class weibo extends zhuayi
{
    public $access_token = null;

    public $conf = array(
                            'response_type'    =>     'code',
                            'grant_type'       =>     'authorization_code',
                            'authorize_url'    =>     'https://api.weibo.com/oauth2/authorize',
                            'token_url'        =>     'https://api.weibo.com/oauth2/access_token',
                        );

    public $cache_outtime = 86400;
    
    public $callback_session_key = "weibo_callback_";

    public $weibo_session_key = "weibo";

    /**
     * 构 造 函 数
     *
     * @author zhuayi
     */
    function __construct()
    {
        $this->http->post_urlencode = true;

        $conf = zhuayi::get_conf('weibo');
        $this->conf = array_merge($conf,$this->conf);
    }

    function run($ap_url,$array = array(),$method = 'get')
    {
        $cache_key = "weibo-".md5(json_encode($array));
        $reset = $this->cache->get($cache_key);

        if ($reset === false)
        {
            if ($method == 'get')
            {
                $reset = $this->http->get($ap_url,$array);
            }
            else
            {
                $this->http->post($ap_url,$array);
            }

            if ($this->http->status > 0)
            {
                throw new Exception($this->http->error, -1);
            }

            $reset = json_decode($this->http->results,true);
            if (!is_array($reset))
            {
                throw new Exception("接口返回数据异常!", -1);
            }
            
            /* 判断是否调用失败 */
            if (isset($reset['error']))
            {
                throw new Exception($reset['error'].":{$reset['error_code']}", -1);
            }
            else
            {

                $this->cache->set($cache_key,$reset,$this->cache_outtime);
            }
        }

        return $reset;

    }

    public function callback($callback = '')
    {
        if (empty($callback))
        {
            $callback = $_SERVER["REQUEST_URI"];
        }
        $this->session->insert($this->callback_session_key,$callback);
        return $this;
    }

    public function go($value='')
    {
        $this->output->go($this->session->get[$this->callback_session_key]);
    }

    public function get_user_info()
    {
        return $this->session->get[$this->weibo_session_key];
    }

    public function set_user_info($user_info)
    {
       return $this->session->insert($this->weibo_session_key,$user_info);
    }


    public function login($callback = '')
    {
        $arr['client_id'] = $this->conf['client_id'];
        $arr['response_type'] = $this->conf['response_type'];
        $arr['redirect_uri'] = $this->conf['redirect_uri'];
        $login_url = $this->conf['authorize_url']."?".http_build_query($arr);
        $this->callback($callback);
        output::go($login_url);
    }

    public function access_token($code)
    {
        $arr['client_id'] = $this->conf['client_id'];
        $arr['client_secret'] = $this->conf['client_secret'];
        $arr['grant_type'] = $this->conf['grant_type'];
        $arr['code'] = $code;
        $arr['redirect_uri'] = $this->conf['redirect_uri'];
        $reset = $this->run('https://api.weibo.com/oauth2/access_token',$arr,'post');
        $this->set_user_info($reset);
        return $reset;
    }

    function get_user_info_by_token($access_token,$uid)
    {
        $uid = floor(floatval($uid));
        $access_token = mysql_escape_string($access_token);

        if (empty($uid) || empty($access_token))
        {
            throw new Exception("参数错误!", 1);
        }
        $this->access_token = $arr['access_token'] = $access_token;
        $arr['uid'] = $uid;
        return $this->run('https://api.weibo.com/2/users/show.json',$arr,'get');
    }

    public function get_weibo_info_by_id($weiboid,$access_token = '')
    {
        //$weiboid = floor(floatval($weiboid));

        if (empty($access_token))
        {
            $access_token = $this->access_token;
        }
        if (empty($weiboid)  || empty($access_token))
        {
            throw new Exception("参数错误!", -1);
        }

        $array['access_token'] = $access_token;
        $array['id'] = $weiboid;
        return $this->run('https://api.weibo.com/2/statuses/show.json',$array,'get');
    }

    public function repost($access_token,$weiboid,$status,$is_comment)
    {
        $access_token = mysql_escape_string($access_token);
        $weiboid = mysql_escape_string($weiboid);
        $status = mysql_escape_string($status);
        $is_comment = intval($is_comment);

        if (empty($access_token) || empty($weiboid))
        {
            throw new Exception("参数错误", -1);
        }
        $array['access_token'] = $access_token;
        $array['id'] = $weiboid;
        $array['status'] = $status;
        $array['is_comment'] = $is_comment;
        return $this->run('https://api.weibo.com/2/statuses/repost.json',$array,'post');
    }

    public function comments($access_token,$weiboid,$comment,$comment_ori)
    {
        $access_token = mysql_escape_string($access_token);
        $weiboid = mysql_escape_string($weiboid);
        $comment = mysql_escape_string($comment);
        $comment_ori = intval($comment_ori);

        if (empty($access_token) || empty($weiboid))
        {
            throw new Exception("参数错误", -1);
        }
        $array['access_token'] = $access_token;
        $array['id'] = $weiboid;
        $array['comment'] = $comment;
        $array['comment_ori'] = $comment_ori;
        return $this->run('https://api.weibo.com/2/comments/create.json',$array,'post');
    }

}
 
?>