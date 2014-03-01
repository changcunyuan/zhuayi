<?php
class index_test extends action
{
    public $tpl = '';

    public function __construct()
    {
        $this->db = new db_index();
        

    }

    function run($a = '',$b = '')
    {

      

        // var_dump($a);
        // var_dump($b);
        
        print_r($this->db->get_test('asd'));
        // print_r($this);
        // $show = array('adsd'=>time());
        // $this->display($show);
        // $asd = serialize($this);
        // $asd = unserialize($asd);
        // var_dump($asd);

        die("金周至,银户县;杀人放火长安县;<br/><br/> 刁蒲城,<font color=red>野渭南</font>;<br/><br/>蛮不讲理大荔县;<br/><br/> 土匪出在二华县");
    }
}



