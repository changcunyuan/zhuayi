<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);
$argv = $_SERVER['argv'];

/* 判断是否有 -C 参数*/
foreach ($argv as $key=>$val)
{
	if ($val == '-c')
	{
		$config_file = $argv[$key+1];
		break;
	}
}

/* 如果是文件夹 */
if (!empty($config_file))
{
	if (is_dir($config_file))
	{
		if (substr($config_file,-1,1) != '/')
		{
			$config_file .= "/";
		}
		$files = array();
		$handle = opendir($config_file); 
		while (false != ($file = readdir($handle)))
		{
			if ($file != "." && $file != ".." && $file != ".DS_Store" && strpos($file,'.config') !== false)
			{
				get_srv_config($config_file.$file);
			}
		}
		closedir($handle);
	}

	else if (is_file($config_file))
	{
		get_srv_config($config_file);
	}
	else
	{
		throw new Exception("配置文件错误!", -1);
	}

}

if (in_array('--help',$argv) || in_array('-h',$argv) || in_array('-?',$argv) || in_array('-help',$argv))
{
	$cron_help = "________________________________________________________________________________________\n";
	$cron_help .= "|                                                                                      |\n";
	$cron_help .= '| -c <path>|<file>        SetEnv Directory example: ***/basic.baidu.com.conf/*.conf    |'."\n";
	$cron_help .= "| --help                  this help                                                    |\n";
	$cron_help .= "| -recache                delete cache                                                 |\n";
	$cron_help .= "| --debug                 Show \$_SERVER list                                           |\n";
	$cron_help .= "_______________________________________________________________________________________\n";

	echo $cron_help;
	exit;
}

if (in_array('--debug',$argv))
{
	print_r($_SERVER);
	exit;
}

if (in_array('-recache',$argv))
{
	$_GET['recache'] = 1;
}

include_once dirname(__FILE__)."/../config/config.inc.php";
function echo_strings($strings)
{
	echo " ";
	$leng = strlen($strings)+5;
	for ($i=0;$i<=$leng;$i++)
	{
		echo "-";
	}
	echo "\n|  ";
	echo $strings;
	echo "    |\n ";
}


function get_srv_config($file)
{
	$array_config = file($file);
	foreach ($array_config as $val)
	{
		$val = trim($val);
		if (strpos($val,'#') === false && !empty($val) && substr($val,0,1) == 'S')
		{
			$val = explode(' ', $val);
			$_SERVER[$val[1]] = trim($val[2]);
		}
		
	}
}
?>
