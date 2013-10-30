<?php
/**
 *  邮件发送类
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 *
 * ------------------------------------------------
 * $this->load_class('email',true);
 *
 * // 发送一般字符串邮件 
 * $this->email->send('2179942@qq.com','title','body');
 * 
 * // 发送邮件模板
 * $emaildata['username'] = $_POST['username'];
 * $emaildata['nick'] = $_POST['nick'];
 * $emaildata['webname'] = $this->webname;
 * $emaildata['weburl'] = $this->weburl;
 * $emaildata['password'] = $password;
 * $emaildata['ip'] = $this->load_fun('get_ip');
 * $this->email->send('2179942@qq.com','title',array('/plugins/email/tpl/email.html'=>$emaildata));
 *
 * -------------------------------------------------
 */

include_once dirname(__FILE__).'/phpmailer.class.php';

class email extends phpmailer
{

	public $Username;

	public $FromName;

	function __construct($foo = null)
	{
		$this->SetLanguage('zn');
		$this->IsSMTP();

	}

	/**
	 * send 发送邮件
	 *
	 * @param string $email 邮件地址
	 * @param string $title 邮件标题
	 * @param string || array  $body 邮件内容
	 */
	function send($email,$title,$body)
	{
		$body = $this->exists_body($body);

		if ($body['status'] == 0)
		{
			$body = $body['msg'];
		}
		else
		{
			throw new Exception($body['msg'], -1);
		}

		$this->SetFrom($this->Username,$this->FromName);
		$this->Subject = $title;
		$this->MsgHTML(stripcslashes($body));

		if (is_array($email))
		{
			foreach ($email as $val)
			{
				$this->AddAddress($val);
			}
		}
		else
		{
			$this->AddAddress($email);
		}
		
		if (!parent::Send())
		{
			throw new Exception($this->ErrorInfo, -1);
		}
		else
		{
			return output::json('0','OK');
		}
	}

	//添加附件
	function file($file)
	{
		$this->AddAttachment($file);
		return $this;
	}

	/**
	 * body 检查body 是否数组，如果是数组，则表示是要载入模版
	 *
	 * @param string || array  $body 邮件内容
	 */
	function exists_body($body = '')
	{
		if (is_array($body))
		{
			$file = array_keys($body);
			$file = $file[0];
			
			$data = array_values($body);
			$data = $data[0];
			
			$file = ZHUAYI_ROOT.'/'.$file;
			
			/* 检查文件是否存在 */
			if (!file_exists($file))
			{
				throw new Exception("tpl error...", -1);
			}
			
			$body = file_get_contents($file);
			
			foreach ($data as $key=>$val)
			{
				$body  = str_replace('{'.$key.'}',$val,$body);
			}
		}

		return output::arrays('0',$body);
	}
}

 ?>