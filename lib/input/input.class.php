<?php
/**
 * input.class.php     Zhuayi 用输入类,接收GHT,POST,COOKIE
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
ini_set("magic_quotes_runtime", 0);
class input extends zhuayi
{

    function __construct()
    {

    }


    function __get($name = '')
    {
        if ($name == 'get')
        {
            return $this->get();
        }
        else if ($name == 'post')
        {
            return $this->get = $this->post();
        }
    }

    /* 递归过滤 */
    public function filter_xss($data)
    {
        foreach ($data as $key => $val)
        {
            if (!is_array($val))
            {
                $data[$key] = htmlspecialchars($val);
                continue;
            }
            else
            {
                $data[$key] = $this->filter_xss($val);
            }
        }
        return $data;
    }

    /* 格式化get 参数 */
    public function get()
    {   
        $this->get = parse_url($_SERVER['REQUEST_URI']);
        parse_str($this->get['query'],$this->get);
        return $this->get = $this->filter_xss($this->get);
    }

    public function post()
    {
        return $this->get = $this->filter_xss($_POST);
    }
}