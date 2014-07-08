<?php
/**
 * file.class.php     Zhuayi  文件操作类
 *
 * @copyright    (C) 2005 - 2010  Zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-27
 * @author       zhuayi
 * @QQ           2179942
 * 
 */

class file 
{

    function __construct()
    {
        
    }

    
    /**
     * mkdir_file 创建文件夹 
     *
     * @return void
     * @author 
     *
     */
    function mkdir($path)
    {
        if (!is_dir($path))
        {
            $oldumask = umask(0);
            $reset = @mkdir($path.'/',0777,true);
            chmod($path.'/', 0777);
            if (!$reset)
            {
                throw new Exception('创建文件夹'.$path.'失败...', -1);
            }
            else
            {
                return true;
            }
        }
        else
        {
            return true;
        }
    }


    function get($filename)
    {
        return file_get_contents($filename);
    }

    function write($filename,$strings,$flags = FILE_USE_INCLUDE_PATH)
    {
        $reset = file_put_contents($filename, $strings,$flags);

        if ($reset === false)
        {
            $path = dirname($filename);
            $this->mkdir($path);
            $reset = file_put_contents($filename, $strings);
        }
        
        return $reset;
    }
}