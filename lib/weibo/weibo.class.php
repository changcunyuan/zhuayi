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

class weibo extends http
{

    public $conf = array(
                            'response_type'    =>     'code',
                            'grant_type'       =>     'authorization_code',
                            'authorize_url'    =>     'https://api.weibo.com/oauth2/authorize',
                            'token_url'        =>     'https://api.weibo.com/oauth2/access_token',
                        );
    /**
     * 构 造 函 数
     *
     * @author zhuayi
     */
    function __construct()
    {
        $this->post_urlencode = true;

        $conf = zhuayi::get_conf('weibo');
        $this->conf = array_merge($conf,$this->conf);
    }

    function run($ap_url,$array = array(),$method = 'get')
    {
        if ($method == 'get')
        {
            $reset = $this->get($ap_url,$array);
        }
        else
        {
            $this->post($ap_url,$array);
        }

        if ($this->status > 0)
        {
            throw new Exception($this->error, -1);
        }
        $reset = json_decode($this->results,true);
        if (!is_array($reset))
        {
            throw new Exception("接口返回数据异常!", -1);
        }

        /* 判断是否调用失败 */
        if (isset($reset['error']))
        {
            throw new Exception($reset['error'].":{$reset['error_code']}", -1);
        }

        return $reset;

    }


    public function login()
    {
        $arr['client_id'] = $this->conf['client_id'];
        $arr['response_type'] = $this->conf['response_type'];
        $arr['redirect_uri'] = $this->conf['redirect_uri'];
        $login_url = $this->conf['authorize_url']."?".http_build_query($arr);
        output::go($login_url);
    }

    public function access_token($code)
    {
        $arr['client_id'] = $this->conf['client_id'];
        $arr['client_secret'] = $this->conf['client_secret'];
        $arr['grant_type'] = $this->conf['grant_type'];
        $arr['code'] = $code;
        $arr['redirect_uri'] = $this->conf['redirect_uri'];
        return $this->run('https://api.weibo.com/oauth2/access_token',$arr,'post');
    }

    function get_user_info_by_token($access_token,$uid)
    {
        $uid = intval($uid);
        $access_token = mysql_escape_string($access_token);

        if (empty($uid) || empty($access_token))
        {
            throw new Exception("参数错误!", 1);
        }
        $arr['access_token'] = $access_token;
        $arr['uid'] = $uid;
        return $this->run('https://api.weibo.com/2/users/show.json',$arr,'get');
    }

}
 
?>