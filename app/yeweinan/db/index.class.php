<?php

class db_index extends mysql
{
    public $db_name_conf = 'yeweinan';
    public $table_name = 'book';

    function get_test()
    {
        if ($this->cache->get('test') !== false)
        {
            $this->count(array('id'=>1,'book_name'=>"{%太空%}",'author_id'=>"{>300}"));
            $this->fetch(array('id'=>1,'book_name'=>"{%太空%}",'author_id'=>"{>300}"),'id desc');
            
            $id = $this->insert(array('book_name'=>"测试数据222".time()));
            $this->update(array('category_id'=>100),array('id'=>$id));

            $this->delete(array('id'=>$id));
        }
        return $this->cache->get('test');
    }
}


