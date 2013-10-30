<?php

/**
 *  获取拼音信息
 *
 * @access    public
 * @param     string  $str  字符串
 * @param     int  $ishead  是否为首字母
 * @param     int  $isclose  解析后是否释放资源
 * @return    string
 */
function pinyin($str, $ishead=0, $isclose=0)
{
    global $pinyins;
    

    $data =  dirname(__FILE__).'/data/pinyin.dat';

    if (!file_exists($data))
    {
        return output::arrays('-1','缺少pinyin.dat');
    }

    $restr = '';
    $str = trim(mb_convert_encoding($str, "gb2312", "utf-8"));
    $slen = strlen($str);
    if($slen < 2)
    {
        return $str;
    }
    if(count($pinyins) == 0)
    {
        $fp = fopen(dirname(__FILE__).'/data/pinyin.dat', 'r');
        while(!feof($fp))
        {
            $line = trim(fgets($fp));
            $pinyins[$line[0].$line[1]] = substr($line, 3, strlen($line)-3);
        }
        fclose($fp);
    }

    for($i=0; $i<$slen; $i++)
    {
        if(ord($str[$i])>0x80)
        {
            $c = $str[$i].$str[$i+1];
            $i++;
            if(isset($pinyins[$c]))
            {
                if($ishead==0)
                {
                    $restr .= $pinyins[$c];
                }
                else
                {
                    $restr .= $pinyins[$c][0];
                }
            }else
            {
                $restr .= "_";
            }
        }else if( preg_match("/[a-z0-9]/i", $str[$i]) )
        {
            $restr .= $str[$i];
        }
        else
        {
            $restr .= "_";
        }
    }
    if($isclose==0)
    {
        unset($pinyins);
    }
    return strtolower($restr);
}


?>