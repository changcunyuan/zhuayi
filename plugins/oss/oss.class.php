<?php
/**
 * 阿里云 oss 服务 队列服务器
 *
 * @package default
 * @author zhuayi
 **/
require dirname(__FILE__)."/sdk.class.php";

class oss
{

	public $bucket = "zhuayi";
	/**
	 * 构 造 函 数
	 *
	 * @author zhuayi
	 */
	function __construct($fields = array())
	{
		$this->oss_sdk_service = new ALIOSS();
	}


	/* 上传文件 */
	function upload_file_by_content($filename,$content)
	{
		$upload_file_options = array(
			'content' => $content,
			'length' => strlen(content),
			ALIOSS::OSS_HEADERS => array(
				'Expires' => date("Y-m-d H:i:s",time()+1200),
			),
		);
		$response = $this->oss_sdk_service->upload_file_by_content($this->bucket,$filename,$upload_file_options);
		if ($response->status == 200)
		{
			return $response->header['_info']['url'];
		}
		throw new Exception("上传失败", 1);
	}


	/* 写入文件 */
	function write($filename,$content)
	{
		return $this->upload_file_by_content($filename,$content);
	}

	//通过multipart上传整个目录
	function upload_by_dir($dir,$recursive=false)
	{
		$response = $this->oss_sdk_service->create_mtu_object_by_dir($this->bucket,$dir,$recursive);
		var_dump($response);	
	}
	
}

?>
