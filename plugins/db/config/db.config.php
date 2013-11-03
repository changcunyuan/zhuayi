<?php
return array(
				/* 默认数据库 */
				'book' =>  array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_CMS_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_CMS_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_CMS_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_CMS_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_CMS_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_CMS_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									)
				);

?>