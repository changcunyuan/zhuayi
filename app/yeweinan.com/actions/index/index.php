<?php
class index_index extends action
{
    public $smarty = false;

    public $check_cgi = true;

    function __construct()
    {
        
    }
    
    function run($a = '',$b = '')
    {
        // var_dump($a);
        // var_dump($b);
        // log::notice('this is a demo');
        //$thi
        var_dump($this->db_index->get_test('asd'));
        var_dump($this->get->asds);
        var_dump($this->post->asd);
        //print_r($this->db_index->get_test('asd'));
        // // print_r($this);
        // // $show = array('adsd'=>time());
        // $show = array('a'=>1);
        // $this->display($show);
        // $asd = serialize($this);
        // $asd = unserialize($asd);
        // var_dump($asd);
        echo "<br/>       
                    刁蒲城，<font color=red>野渭南</font><br/><br/>
                    不讲理的大荔县<br/><br/>
                    蛮临潼,鬼合阳<br/><br/>
                    韩城是个球咬腿<br/><br/>
                    白水能出二秆子,不如富平的八点子<br/><br/>
                    金周至，银户县 ,杀人放火长安县<br/><br/>
                    二球出在澄城县<br/><br/>
                    土匪出在两华县<br/><br/><br/><br/>
                    孟原的风，赤水的葱<br/><br/>
                    武功县的烧鸡公<br/><br/>
                    米脂的婆姨，绥德的汉<br/><br/>
                    清涧的石板，瓦窑堡的炭<br/><br/>
                    三原的媳妇不能看<br/><br/>
            ";
    }
}