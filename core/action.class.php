<?php
/**
 * action.class.php     Zhuayi 控制器抽象类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
abstract class action extends zhuayi
{
    protected $smarty;

    protected $post;

    public $assign;

    public $check_cgi  = false;

    function parse_cgi()
    {
        /* 格式化get post 参数 */
        ini_set("magic_quotes_runtime", 0);

        //处理被 get_magic_quotes_gpc 自动转义的数据,转换为HTML实体
        //$this->get = $this->query;
        parse_str($this->query,$this->get);
        $this->post = $_POST;
        $in = array(& $this->get, & $this->post);
        while (list ($k, $v) = each($in))
        {
            foreach ($v as $key => $val)
            {
                if (! is_array($val) || !is_object($val))
                {
                    $in[$k][$key] = htmlspecialchars($val);
                    continue;
                }
                $in[] = & $in[$k][$key];
            }
        }
        unset($in);
        $this->get = (object)$this->get;
        $this->post = (object)$this->post;
        return $this;
    }

    function display($show = array(),$tpl = '')
    {
        if (empty($tpl))
        {
            $tpl = explode('_',get_called_class());
        }
        $filename = APP_ROOT."/template/{$tpl[0]}/{$tpl[0]}_{$tpl[0]}.html";

        $show['config'] = $_SERVER['APP']['web'];
        if ($this->smarty)
        {
            return $this->smarty($show,$filename);
        }
        return require $filename;
    }

    function smarty($show,$filename)
    {
        $config = zhuayi::get_conf('smarty');
        $config['compile_dir'] = ZHUAYI_ROOT."/".$config['compile_dir'].APP_NAME;
        
        $smarty = new Smarty;
        $smarty->setCompileDir($config['compile_dir']);
        $smarty->left_delimiter = $config['left_delimiter'];
        $smarty->right_delimiter = $config['right_delimiter'];

        $smarty->assign('show',$show);
        $smarty->display($filename);
    }

    function __get($name)
    {
        if ($name == 'get' || $name == 'post')
        {
            $this->parse_cgi();
            return $this->$name;
        }
        else
        {
            return $this->$name = new $name();
        }
        
    }
}