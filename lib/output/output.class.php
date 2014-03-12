<?php
/**
 * output.class.php     Zhuayi 输出类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
class output extends zhuayi
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

    public function _get_tpl_path()
    {
        $tpl = debug_backtrace();
        $tpl = explode('_',$tpl[2]['class']);
        return $filename = APP_ROOT."/template/{$tpl[0]}/{$tpl[0]}_{$tpl[0]}.html";
    }

    /* 返回输出 */
    public function fetch($show = array(),$filename = '')
    {
        ob_start();
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /* 原生返回 */
    public function display($show = array(),$filename = '')
    {  
        if (empty($filename))
        {
            $filename = $this->_get_tpl_path();
        }
        return require $filename;
    }

    /* smarty 输出 */
    public function smarty($show = array(),$filename = '')
    {
        if (empty($filename))
        {
            $filename = $this->_get_tpl_path();
        }
        $config = zhuayi::get_conf('smarty');
        $config['compile_dir'] = ZHUAYI_ROOT."/".$config['compile_dir'].APP_NAME;
        
        $smarty = new Smarty;
        $smarty->setCompileDir($config['compile_dir']);
        $smarty->left_delimiter = $config['left_delimiter'];
        $smarty->right_delimiter = $config['right_delimiter'];

        $smarty->assign('show',$show);
        $smarty->display($filename);
    }

    /* json 输出 */
    public function json($status,$msg)
    {
        header('Content-type: application/json');
        $array['status'] = $status;
        $array['msg'] = $msg;
        die(json_encode($array));
    }

    /* jsonp 输出 */
    public function jsonp($status = 0 ,$msg = '',$callback)
    {
        header('Content-type: application/json');
        $array['status'] = $status;
        $array['msg'] = $msg;
        echo "{$callback}(".json_encode($array).")";
    }

    /* 跳转 */
    public function go($url)
    {
        header("Location: ".$url);
        exit;
    }
}