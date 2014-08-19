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
    public $filename;

    /* 模板变量 */
    public $show = array();

    function __construct()
    {
       
    }

    public function _get_tpl_path()
    {
        $tpl = debug_backtrace();
        $tpl = explode('_',$tpl[2]['class']);

        $path = $tpl;
        $file = array_pop($path);
        $this->filename = implode("_",$tpl).".html";
        return APP_ROOT."/template/".implode("/",$path)."/".$this->filename;
    }

    /* 设置模板变量 */
    public function append_show($show)
    {
        if (!is_null($show))
        {
            $this->show = array_merge($this->show,$show);
        }
        if (is_array($this->input->get))
        {
            $this->show['get'] = $this->input->get;
        }
        return $this->show;
    }

    /* 返回输出 */
    public function fetch($show = array(),$filename = '')
    {
        $show = $this->append_show($show);
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
        $show = $this->append_show($show);

        return require $filename;
    }

    /* smarty 输出 */
    public function smarty($show = array(),$filename = '')
    {
        if (empty($filename))
        {
            $filename = $this->_get_tpl_path();
        }
        $config = $_SERVER['APP']['smarty'];
        $config['compile_dir'] = ZHUAYI_ROOT."/".$config['compile_dir'].APP_NAME;
        
        /* 判断smarty编译文件是否存在 */
        //$file_hash = md5_file($filename);
        //$cache_file = $config['compile_dir']."/".$file_hash.".file.{$this->filename}.cache";

        //$reset = false;
        //if (!isset($_GET['recache']))
        //{
        //    $reset = include $cache_file;
        //}
        
        //if ($reset === false)
        //{

            $smarty = new Smarty;
            $smarty->setCompileDir($config['compile_dir']);
            $smarty->left_delimiter = $config['left_delimiter'];
            $smarty->right_delimiter = $config['right_delimiter'];
            $show = $this->append_show($show);
            if (!empty($show))
            {
                $smarty->assign('show',$show);
            }

          //  ob_start();
            return $smarty->display($filename);
          //  $content = ob_get_contents();
          //  ob_end_clean();
          //  $content .= "<script>var cache_file='{$file_hash}'</script>";
          //  $this->file->write($cache_file,$content);
          //  echo $content;
        //}
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
        $array['error'] = $status;
        $array['data'] = ($status === 0)?$msg:null;

        //callback参数只支持数字字母下划线及点号组成的字符串并且首字母不能是数字
        if (!preg_match('/^[a-zA-Z_\.]+[a-zA-Z0-9_\.]*$/',$callback))
        {
            $callback = '';
        }
        die("{$callback}(".json_encode($array).")");
    }

    /* 跳转 */
    public function go($url)
    {
        header("Location: ".$url);
        exit;
    }
}