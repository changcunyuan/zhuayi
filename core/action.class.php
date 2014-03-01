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

    function display($show = array(),$tpl = '')
    {
        if (empty($tpl))
        {
            $tpl = explode('_',get_called_class());
        }
        $filename = APP_ROOT."/template/{$tpl[0]}/{$tpl[0]}_{$tpl[0]}.html";

        $show = $this->_set_show($show);   
        if (empty($this->tpl))
        {
            require $filename;
        }
        if ($this->smarty)
        {
            $this->smarty($show,$filename);
        }
        return true;
    }


    function _set_show($show)
    {
        return array_merge($show,$this->conf);
    }


    function smarty($show,$filename)
    {
        $config = zhuayi::get_conf('smarty');
        
        $config['compile_dir'] = ZHUAYI_ROOT."/".$config['compile_dir']."{$this->conf['app']['appname']}";
        
        $smarty = new Smarty;
        $smarty->setCompileDir($config['compile_dir']);
        $smarty->left_delimiter = $config['left_delimiter'];
        $smarty->right_delimiter = $config['right_delimiter'];
        $smarty->assign('show',$show);
        $smarty->display($filename);
    }
}