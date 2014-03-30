<?php
class index_index extends action
{
    public $smarty = false;

    function __construct()
    {

    }
    
    function run($a = '',$b = '')
    {
        print_r($a);
        print_r($_GET);
        print_r($this->input->get);
        exit;
        //$this->session->insert('user_info',array('user_name'=>'zhuayi111','passport'=>array('198698')));
        //$this->session->update('user_info',array('user_name'=>'zhuayi'));
        //$this->session->delete('user_info','passport');
       
        //var_dump($this->input->get['asds']);
        //print_r($this->input->get);
        
        //$this->input->cookie = array('asd'=>'123');
        //print_r($this->input->set_cookie('asd',123));
        print_r($this->input->cookie['asd']);
        //$this->input->post = array('ddd'=>time());
        //print_r($this->input->post);
        //print_r($this->input->set_cookie(''))
        //print_r($this->db_index->get_test());
        
        echo ( "   
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
            ");

        //$this->output->display($show);
    }
}
