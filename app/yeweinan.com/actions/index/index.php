<?php
class index_index extends action
{
    public $smarty = false;

    function __construct()
    {

    }
    
    function run($a = '',$b = '')
    {
        //var_dump($this->user);
        //print_r($a);
        //print_r($_GET);
        //print_r($this->input->get);
        //exit;
        //$this->session->insert('user_info',array('user_name'=>'zhuayi111','passport'=>array('198698')));
        //$this->session->update('user_info',array('user_name'=>'zhuayi'));
        //$this->session->delete('user_info','passport');
       
        //var_dump($this->input->get['asds']);
        //print_r($this->input->get);
        
        //print_r($_GET);
        //$this->input->cookie = array('asd'=>'123');
        //print_r($this->input->set_cookie('asd',123));
        //print_r($this->session->get['user_info']);
        //$this->input->post = array('ddd'=>time());
        //print_r($this->input->post);
        //print_r($this->input->set_cookie(''))
        var_dump($this->db_index->get_test());
        $this->output->smarty($show);
    }
}
