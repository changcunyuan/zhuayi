<?php
return array(
				/* 默认数据库 */
				'default' =>  array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_CMS_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_CMS_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_CMS_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_CMS_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_CMS_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_CMS_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 软件管理器 */
				'soft' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SOFT_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SOFT_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SOFT_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SOFT_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SOFT_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SOFT_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 心跳服务 */
				'heartbeat' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_HEARBEAT_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_HEARBEAT_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_HEARBEAT_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_HEARBEAT_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_HEARBEAT_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_HEARBEAT_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),

				/* 升级服务 */
				'update' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_UPDATE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_UPDATE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_UPDATE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_UPDATE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_UPDATE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_UPDATE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* VDC服务 */
				'vdc' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_VDC_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_VDC_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_VDC_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_VDC_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_VDC_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_VDC_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* VDC服务 */
				'rules' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_RULES_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_RULES_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_RULES_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_RULES_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_RULES_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_RULES_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),

				/* VDC信息汇总规则服务 */
				'fileinfo_hits' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_FILEINFO_HITS_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_FILEINFO_HITS_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_FILEINFO_HITS_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_FILEINFO_HITS_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_FILEINFO_HITS_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_FILEINFO_HITS_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 引擎发布 */
				'release_log_db' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_RELEASE_LOG_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_RELEASE_LOG_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_RELEASE_LOG_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_RELEASE_LOG_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_RELEASE_LOG_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_RELEASE_LOG_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 服务器数据上报 */
				'datav' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_DATAV_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_DATAV_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_DATAV_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_DATAV_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_DATAV_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_DATAV_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 用户反馈系统 */
				'fqa' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_FQA_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_FQA_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_FQA_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_FQA_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_FQA_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_FQA_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 启动加速 */
				'startup' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_STARRTUP_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_STARRTUP_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_STARRTUP_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_STARRTUP_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_STARRTUP_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_STARRTUP_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 启动加速 */
				'system' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SYSTEM_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SYSTEM_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SYSTEM_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SYSTEM_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SYSTEM_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SYSTEM_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 垃圾清理 */
				'garbage' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_GARBAGE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_GARBAGE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_GARBAGE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_GARBAGE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_GARBAGE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_GARBAGE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/*字段管理*/
				'field' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_FIELD_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_FIELD_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_FIELD_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_FIELD_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_FIELD_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_FIELD_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				//第三方样本交换
				'exchange' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_EXCHANGE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_EXCHANGE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_EXCHANGE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_EXCHANGE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_EXCHANGE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_EXCHANGE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 基础服务配置 */
				'config_service' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_CONFIG_SERVICE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_CONFIG_SERVICE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_CONFIG_SERVICE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_CONFIG_SERVICE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_CONFIG_SERVICE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_CONFIG_SERVICE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),

				/* 基础服务crash上报 */
				'CrashReport' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_CRASHREPORT_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_CRASHREPORT_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_CRASHREPORT_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_CRASHREPORT_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_CRASHREPORT_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_CRASHREPORT_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				//vdc相关的一些数据
				'vdclocal' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_VDCLOCAL_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_VDCLOCAL_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_VDCLOCAL_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_VDCLOCAL_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_VDCLOCAL_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_VDCLOCAL_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				//用于官网静态页生成
				'official' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_OFFICIAL_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_OFFICIAL_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_OFFICIAL_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_OFFICIAL_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_OFFICIAL_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_OFFICIAL_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				//topn
				'topn' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_TOPN_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_TOPN_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_TOPN_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_TOPN_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_TOPN_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_TOPN_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 蜘蛛配置 */
				'spider_config' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SPIDER_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SPIDER_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SPIDER_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SPIDER_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SPIDER_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SPIDER_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 样本收集 */
				'soft_spider_config' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SAMPLE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SAMPLE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SAMPLE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SAMPLE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SAMPLE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SAMPLE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				'source_info' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SOURCE_INFO_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SOURCE_INFO_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SOURCE_INFO_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SOURCE_INFO_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SOURCE_INFO_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SOURCE_INFO_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 样本来源管理*/
				'source' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SOURCE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SOURCE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SOURCE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SOURCE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SOURCE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SOURCE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),

				/* 监控*/
				'monitor' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_MONITOR_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_MONITOR_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_MONITOR_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_MONITOR_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_MONITOR_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_MONITOR_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),

				/* 漏洞修复*/
				'patch' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_PATCH_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_PATCH_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_PATCH_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_PATCH_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_PATCH_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_PATCH_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),

				/* 样本周报 */
				'weekly' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_WEEKLY_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_WEEKLY_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_WEEKLY_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_WEEKLY_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_WEEKLY_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_WEEKLY_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* URL管理 */
				'url' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_URL_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_URL_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_URL_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_URL_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_URL_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_URL_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 极光升级配置 */
				'weishi_update' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_WEISHI_UPDATE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_WEISHI_UPDATE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_WEISHI_UPDATE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_WEISHI_UPDATE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_WEISHI_UPDATE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_WEISHI_UPDATE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 杀毒升级配置 */
				'shadu_update' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SHADU_UPDATE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SHADU_UPDATE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SHADU_UPDATE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SHADU_UPDATE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SHADU_UPDATE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SHADU_UPDATE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 极光心跳配置 */
				'weishi_heart' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_WEISHI_HEART_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_WEISHI_HEART_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_WEISHI_HEART_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_WEISHI_HEART_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_WEISHI_HEART_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_WEISHI_HEART_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 杀毒心跳配置 */
				'shadu_heart' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SHADU_HEART_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SHADU_HEART_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SHADU_HEART_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SHADU_HEART_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SHADU_HEART_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SHADU_HEART_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 极光配置下发配置 */
				'weishi_configservices' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_WEISHI_CONFIGSERVERS_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_WEISHI_CONFIGSERVERS_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_WEISHI_CONFIGSERVERS_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_WEISHI_CONFIGSERVERS_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_WEISHI_CONFIGSERVERS_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_WEISHI_CONFIGSERVERS_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 杀毒配置下发配置 */
				'shadu_configservices' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SHADU_CONFIGSERVERS_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SHADU_CONFIGSERVERS_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SHADU_CONFIGSERVERS_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SHADU_CONFIGSERVERS_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SHADU_CONFIGSERVERS_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SHADU_CONFIGSERVERS_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 团队日志 */
				'daily' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SHADU_DAILY_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SHADU_DAILY_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SHADU_DAILY_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SHADU_DAILY_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SHADU_DAILY_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SHADU_DAILY_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				'url_topn' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_URL_TOPN_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_URL_TOPN_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_URL_TOPN_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_URL_TOPN_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_URL_TOPN_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_URL_TOPN_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 杀毒配置下发配置 */
				'shadu_filedispatch' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SHADU_FILEDISPATH_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SHADU_FILEDISPATH_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SHADU_FILEDISPATH_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SHADU_FILEDISPATH_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SHADU_FILEDISPATH_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SHADU_FILEDISPATH_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 卫士文件下发配置 */
				'weishi_filedispatch' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_WEISHI_FILEDISPATH_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_WEISHI_FILEDISPATH_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_WEISHI_FILEDISPATH_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_WEISHI_FILEDISPATH_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_WEISHI_FILEDISPATH_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_WEISHI_FILEDISPATH_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 安全组件心跳 */
				'safeguard_heart' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SAFEGUARD_HEART_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SAFEGUARD_HEART_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SAFEGUARD_HEART_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SAFEGUARD_HEART_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SAFEGUARD_HEART_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SAFEGUARD_HEART_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),

				/* 杀毒攻防服务 */
				'shadu_attackdefense' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_ATTACKDEFENSE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_ATTACKDEFENSE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_ATTACKDEFENSE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_ATTACKDEFENSE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_ATTACKDEFENSE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_ATTACKDEFENSE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 安全组件攻防服务 */
				'safeguard_attackdefense' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SAFEGUARD_ATTACKDEFENSE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SAFEGUARD_ATTACKDEFENSE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SAFEGUARD_ATTACKDEFENSE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SAFEGUARD_ATTACKDEFENSE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SAFEGUARD_ATTACKDEFENSE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SAFEGUARD_ATTACKDEFENSE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 卫士攻防服务 */
				'weishi_attackdefense' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_WEISHI_ATTACKDEFENSE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_WEISHI_ATTACKDEFENSE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_WEISHI_ATTACKDEFENSE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_WEISHI_ATTACKDEFENSE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_WEISHI_ATTACKDEFENSE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_WEISHI_ATTACKDEFENSE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),

				/* 主防云服务 */
				'activeDef' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_ACTIVEDEF_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_ACTIVEDEF_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_ACTIVEDEF_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_ACTIVEDEF_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_ACTIVEDEF_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_ACTIVEDEF_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 进程项优化 */
				'process' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_PROCESS_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_PROCESS_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_PROCESS_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_PROCESS_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_PROCESS_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_PROCESS_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),

				/* 流程管理 */
				'procedure' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_PROCEDURE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_PROCEDURE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_PROCEDURE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_PROCEDURE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_PROCEDURE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_PROCEDURE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 用户论坛 */
				'bbs' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_BBS_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_BBS_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_BBS_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_BBS_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_BBS_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_BBS_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 统一配置 */
				'service_config' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SERVICE_CONFIG_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SERVICE_CONFIG_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SERVICE_CONFIG_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SERVICE_CONFIG_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SERVICE_CONFIG_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SERVICE_CONFIG_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 第三方提交软件 */
				'software_submit' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_SOFTWARE_SUBMIT_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_SOFTWARE_SUBMIT_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_SOFTWARE_SUBMIT_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_SOFTWARE_SUBMIT_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_SOFTWARE_SUBMIT_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_SOFTWARE_SUBMIT_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* 个人中心 */
				'user_center' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_USERCENTER_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_USERCENTER_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_USERCENTER_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_USERCENTER_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_USERCENTER_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_USERCENTER_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
                                /* 驱动管理 */
				'driver' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_DRIVER_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_DRIVER_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_DRIVER_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_DRIVER_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_DRIVER_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_DRIVER_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
				/* URL保护 */
				'url_protect' => array(
									'mysql_host_m'        =>		$_SERVER['BAIDU_URL_PROTECT_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_URL_PROTECT_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_URL_PROTECT_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_URL_PROTECT_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_URL_PROTECT_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_URL_PROTECT_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' =>		'86400'
									),
					/* 官网二合一 */
				'two_in_one' => array(
									'mysql_host_m'        =>	$_SERVER['BAIDU_TWO_IN_ONE_MYSQL_M'],
									'mysql_host_s'        =>		$_SERVER['BAIDU_TWO_IN_ONE_MYSQL_S'],
									'mysql_user'          =>		$_SERVER['BAIDU_TWO_IN_ONE_MYSQL_USER'],
									'mysql_pass'          =>	 	$_SERVER['BAIDU_TWO_IN_ONE_MYSQL_PASS'],
									'mysql_db'            =>		$_SERVER['BAIDU_TWO_IN_ONE_MYSQL_DB'],
									'mysql_port'          =>		$_SERVER['BAIDU_TWO_IN_ONE_MYSQL_PORT'],
									'mysql_charset'       =>		"utf8",
									'mysql_pre'           =>		"",
									'mysql_cache_outtime' => '86400'
									),
					/* 百度统计 */
				'baidutongji' => array(
									'mysql_host_m' => $_SERVER['BAIDU_TONGJI_MYSQL_M'],
									'mysql_host_s' => $_SERVER['BAIDU_TONGJI_MYSQL_S'],
									'mysql_user' => $_SERVER['BAIDU_TONGJI_MYSQL_USER'],
									'mysql_pass' => "",
									'mysql_db' => $_SERVER['BAIDU_TONGJI_MYSQL_DB'],
									'mysql_port' => $_SERVER['BAIDU_TONGJI_MYSQL_PORT'],
									'mysql_charset' => "utf8",
									'mysql_pre' => "",
									'mysql_cache_outtime' => '86400'
									),
				);

?>