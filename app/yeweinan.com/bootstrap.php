<?php
/**
 * bootstrap.php  
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 */
class bootstrap
{
    public static function __set_default_modle(router $zhuayi)
    {
        $zhuayi->default_modle = 'index';
        $zhuayi->default_action = 'index';
    }

    public static function __set_user_session(zhuayi $zhuayi)
    {
        $zhuayi->user = array('zhuayi'=>true);
    }
}