<?php
error_reporting(E_ALL^E_DEPRECATED^E_NOTICE^E_WARNING);
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

include dirname(__FILE__)."/../config/config.inc.php";
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
		if (strpos($val,'#') === false && !empty($val) && (substr($val,0,3) == 'Set' || substr($val,0,13) == 'fastcgi_param'))
		{
			/* 兼容nginx 去掉最后一个; */
			if (substr($val,0,13) == 'fastcgi_param')
			{
				$val = substr($val, 0,(strlen($val)-1));
			}
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
	unset($argv[0]);

	for ($i = 1; $i < $_SERVER['argc']; $i++)
	{
		if (substr($argv[$i],0,1) == '-' && isset($argv[$i+1]) && substr($argv[$i+1],0,1) != '-' )
		{
			$_ARG[$argv[$i]] = $argv[$i+1];
			continue;
		}
		if (substr($argv[$i],0,1) == '-' )
		{
			$_ARG[$argv[$i]] = 'true';
		}
	}
	return $_ARG;
}

function console_log($action,$strings,$sleep = 0,$setup = 0)
{
	if ($setup == 0)
	{
		echo "[".date("Y-m-d H:i:s")."] {$_SERVER['PHP_SELF']} -> ";
	}
	else
	{
		echo ">";
	}
	echo "[{$action}] : {$strings}\n";
	if ($sleep > 0)
	{
		sleep($sleep);
	}
}

?>
