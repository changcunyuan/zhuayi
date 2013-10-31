<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);
$argv_array = arg_parser();

/* 如果是文件夹 */
if (!empty($argv_array['-c']))
{
	if (is_dir($argv_array['-c']))
	{
		if (substr($argv_array['-c'],-1,1) != '/')
		{
			$argv_array['-c'] .= "/";
		}
		$files = array();
		$handle = opendir($argv_array['-c']); 
		while (false != ($file = readdir($handle)))
		{
			if ($file != "." && $file != ".." && $file != ".DS_Store" && strpos($file,'.config') !== false)
			{
				get_srv_config($argv_array['-c'].$file);
			}
		}
		closedir($handle);
	}

	else if (is_file($argv_array['-c']))
	{
		get_srv_config($argv_array['-c']);
	}
	else
	{
		throw new Exception("配置文件错误!", -1);
	}

}

if (isset($argv_array['-help']) || isset($argv_array['-h']) || isset($argv_array['-?']) || isset($argv_array['-help']))
{
	$cron_help = "________________________________________________________________________________________\n";
	$cron_help .= "|                                                                                      |\n";
	$cron_help .= '| -c <path>|<file>        SetEnv Directory example: ***/basic.baidu.com.conf/*.conf    |'."\n";
	$cron_help .= "| -help                  this help                                                    |\n";
	$cron_help .= "| -recache                delete cache                                                 |\n";
	$cron_help .= "| -debug                 Show \$_SERVER list                                           |\n";
	$cron_help .= "_______________________________________________________________________________________\n";

	echo $cron_help;
	exit;
}

if (isset($argv_array['-debug']))
{
	print_r($_SERVER);
	exit;
}

if (isset($argv_array['-recache']))
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

function cmd_stdin()
{
	$fp = fopen("/dev/stdin", "r");
	$input = fgets($fp, 255);
	fclose($fp);
	return $input;
}

/* 格式化 arg */
function arg_parser()
{
	$argv = $_SERVER['argv'];
	$argc = $_SERVER['argc'];
	$argv_array = array();
	if ( $argc > 1 )
	{
		for( $i = 1 ; $i < $argc ; $i++)
		{
			if ( substr($argv[$i],0,1) == "-" )
			{
				if ( $argc > ($i+1) )
				{
					if ( substr($argv[$i+1],0,1) != "-" )
					{
						$argv_array[$argv[$i]] = $argv[$i+1];
						$i++;
						continue;
					}
					else
					{
						$argv_array[$argv[$i]] = '';
					}
				}
			}
		} 
	}
	return $argv_array;
}
?>
