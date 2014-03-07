#!/usr/bin/php
<?php
define('APP_ROOT', dirname(dirname(dirname(__FILE__))));
require APP_ROOT."/../../core/zhuayi.php";
class index_test extends action
{

    public function __construct()
    {

    }

    function run($a = '',$b = '')
    {
        //log::notice('aaa');
        print_r($this->db_index->get_test());
        // var_dump($a);
        // var_dump($b);
        
        //print_r($this->db_index->get_test('asd'));
        // print_r($this);
        // $show = array('adsd'=>time());
        // $this->display($show);
        // $asd = serialize($this);
        // $asd = unserialize($asd);
        // var_dump($asd);
        print_r($this->get['-m']);
        print_r($this->get['-p']);
        print_r($this);
        //exit;
        die("<br/>金周至,银户县;杀人放火长安县;<br/><br/> 刁蒲城,<font color=red>野渭南</font>;<br/><br/>蛮不讲理大荔县;<br/><br/> 土匪出在二华县");
        return false;
    }
}

zhuayi::cil();
