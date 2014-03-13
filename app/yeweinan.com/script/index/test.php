#!/usr/bin/php
<?php
define('APP_ROOT', dirname(dirname(dirname(__FILE__))));
require APP_ROOT."/../../core/zhuayi.php";
class index_test extends action
{

    public function __construct()
    {

    }

    function run()
    {
        //var_dump($this->session->set('user_info',array('123456')));
        var_dump($this->session->get['user_info']);
        exit;
        //$this->output->json('1','111');

        print_r($this->input->get['-m']);
        $this->output->smarty($show);
        die("<br/>金周至,银户县;杀人放火长安县;<br/><br/> 刁蒲城,<font color=red>野渭南</font>;<br/><br/>蛮不讲理大荔县;<br/><br/> 土匪出在二华县");
    }
}

zhuayi::cil();
