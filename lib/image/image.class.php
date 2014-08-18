<?php
/**
 * image class 图 片 操 作 类
 *
 * $this->load_class('image',true);
 *
 *  下 载 图 片
 * $this->image->show('http://ww3.sinaimg.cn/bmiddle/88ee4465jw1dkj2cwnlh4j.jpg');
 *
 *  上 传 图 片 
 * $this->image->show($_FILES['imgFile']);
 *
 *  缩 放 图 片 ，如 果 宽 和 高 都 输 入 则 先 等 比 例 缩 小  然 后 不 够 的 像 素 填 充 白 色
 * $this->image->zomm(300);
 *
 *  打 水 印 
 * $this->image->mark();
 *
 * 合并图片
 * $hebing[] = 'http://ww3.sinaimg.cn/bmiddle/640b9a98tw1dkyy1peeycj.jpg';
 * $hebing[] = 'http://ww4.sinaimg.cn/large/68f5e3afjw1dkxp60rfd9j.jpg';
 * $this->image->hebing($hebing);
 *  保 存 图 片
 * $this->image->save('/cache/1');
 * @package default
 * @author zhuayi
 *
*/

class image extends http
{

	/* 水印图片 */
	public $mark;
	
	/* 水印位置 */
	public $mark_type;
	

	/**
	 * 构 造 函 数
	 *
	 * @author zhuayi
	 */
	function __construct()
	{
		global $file;

		/* 加载缓存类 */
		$this->file = & $file;

		$this->temp = ini_get('upload_tmp_dir');


		parent::__construct();
	}
	
	/**
	 * 获取图片二进制数据
	 *
	 * @author zhuayi
	 */
	function show($file,$referer = '')
	{
		$this->file_from = false;

		$this->width = $this->height =  $this->type = '';
		if (is_array($file))
		{
			if ($file['error'] > 0)
			{
				throw new Exception("上传图片失败,可能图片大小超出限制,只允许上传".intval(($this->max_length/1024/1024))."M以内的图片", -1);
			}
			
			$filename = $file['tmp_name'];
			$this->file_from = true;
			$this->h = trim(substr(strrchr(strtolower($file['name']),'.'),1,100));
		}
		else
		{
			$filename = preg_replace('#\?(.*)|&(.*)|#','',$file);

			/* 取文件后缀 */
			$this->h = trim(substr(strrchr(strtolower($filename),'.'),1,100));
		}

		/* 第一层简单判断后缀*/

		// $upload_allowext = explode('|',$this->allowext);
		
		// if (!in_array($this->h,$upload_allowext))
		// {
		// 	throw new Exception("图片格式不正确，请您输入.png、.jpg、.jpeg格式的图片！", -1);
		// }
		 
		/* 下载文件数据,并判断是否图片 */
		$this->get_file_data($filename,$referer);

		/* 获取文件数据 */
		return $this;
	}

	function get_file_data($filename,$referer)
	{
		$opts = array(
						'http'=>array('method'=>"GET",'timeout'=>20)  
					);  
		
		/* 如果是外部图片增加,来路 */
		if (!empty($referer))
		{
			//$refer_url = parse_url($filename);
			$opts['http']['header'] = "Referer:http://".$referer;
		}
		
		$context = stream_context_create($opts); 
		$this->file_data = file_get_contents($filename,false, $context);
		
		if (empty($this->file_data) || $this->file_data === false)
		{
			throw new Exception("图片数据获取失败 <!-- {$filename} -->", -1);
		}

		$this->filename = tempnam($this->temp,'zhuayi');
		
		if (empty($this->filename))
		{
			throw new Exception("创建临时文件失败", -1);
		}
		
		/*  把图片写入到临时文件 */
		file_put_contents($this->filename,$this->file_data);

		/* 取文件信息 */
		$reset = $this->info();

		if ($reset === false)
		{
			throw new Exception("图片格式不正确，请您输入.png、.jpg、.jpeg格式的图片！", -1);
		}
		/* 获取文件长度 */
		$this->size = abs(filesize($this->filename));
		$this->type = $reset['mime'];
		$this->width = $reset[0];
		$this->height = $reset[1];

		switch($reset[2])
		{ 
			case 1:
			$this->h = '.gif';
			break; 
			case 2:
			$this->h = '.jpg';
			break; 
			case 3:
			$this->h = '.png';
			break; 
			default:
			throw new Exception("图片格式不正确，请您输入.png、.jpg、.jpeg格式的图片！", -1);
			
			break;
		}


	}


	/**
	 * 获 取 图 片 信 息
	 *
	 * @author zhuayi
	 */
	function info($filename = '')
	{
		if (empty($filename))
		{
			return getimagesize($this->filename);
		}
		else
		{
			return getimagesize($filename);
		}
	}

	/**
	 * 返回图像资源句柄
	 *
	 * @author zhuayi
	 */
	function create($type,$filename)
	{
		switch($type)
		{ 
			case 1:
			return imagecreatefromgif($filename);
			break; 
			case 2:
			return imagecreatefromjpeg($filename);
			break; 
			case 3:
			return imagecreatefrompng($filename);
			break; 
			default:
			return -1;
		}
	}

	/**
	 * zoom 缩 放 图 片
	 * @expand 设置为true时,如果原图宽高小于指定宽高,则填充灰色,居中显示
	 * 
	 * @author zhuayi
	 */
	function zomm($width = 0,$height = 0,$add = false)
	{
		$info = $this->info();
		
		$x  = $y = 0;

		if ($width >0 && $height >0)
		{
			$max_width = $width;
			$max_height = $height;
			
			/* 先判断图片事横的还事树的 */
			if ($info['0'] > $info[1])
			{
				/* 横的 */
				$_width = intval($info[0]*$height/$info['1']);

				$x = 0;
				if ($width > $_width && $width < $this->width)
                {
                    $height = $height * $width / $_width;
                }
                else //裁剪
                {
                	$x = 0-($_width - $width) / 2;
                	$width = $_width;
                }

			}
			else
			{

				$_height = intval($info['1']* $width/$info['0']);
				//$y = 0-($_height - $height) / 2;
				$y = 0;
				if ($height > $_height && $height < $this->height)
                {
                    $width = $width * $height / $_height;
                }
                else
                {
                    $height = $_height;
                }
			}
		}

		
		elseif ($width < $info['0'] && $width > 0 && $height == 0)
		{
			$height = intval($info['1']*$width/$info['0']);
			$max_width = $width;
			$max_height = $height;
		}
		elseif ($width > $info['0'] && $width > 0 && $height == 0)
		{
			if ($add === true)
			{
				$x = intval(($width-$info['0'])/2);
				$max_width = $width;
			}
			else
			{
				$max_width = $info['0'];
			}
			$height = $info['1'];
			$max_height = $height;
			$width = $info['0'];
			
		}
		elseif ($height < $info['0'] && $width==0)
		{

			$width = intval($info['0']*$height/$info['1']);
			$max_width = $width;
			$max_height = $height;
		}
		else
		{
			$this->width = $max_width = $width = $info[0];
			$this->height = $max_height = $height= $info[1];
		}

		$this->width = $max_width;
		$this->height = $max_height;
		
		$image = $this->create($info[2],$this->filename);

		if ($image  == '-1')
		{
			throw new Exception("图片格式不正确，请您输入.png、.jpg、.jpeg格式的图片！", -1);
		}

		$image_p = imagecreatetruecolor($max_width, $max_height);
		$color = imagecolorAllocate($image_p,243,243,243);
		imagefill($image_p,0,0,$color);
		imagecopyresampled($image_p, $image, $x, $y, 0, 0, $width, $height, $info[0], $info[1]);

		$this->save_temp($image_p,$info['2']);

		return $this;
	}

	/**
	 * save_temp pic
	 *
	 * @author zhuayi
	 */
	function save_temp($image_p,$type)
	{
		switch($type)
		{ 
			case 1:
			imagegif($image_p, $this->filename,90);
			break; 
			case 2:
			imagejpeg($image_p, $this->filename,90);
			break; 
			case 3:
			imagepng($image_p, $this->filename);
			break; 
			default:
			throw new Exception("图片格式不正确，请您输入.png、.jpg、.jpeg格式的图片！", -1);
			break;
		}

		$this->file_data = file_get_contents($this->filename);
		$this->size = strlen($this->file_data);
	}

	/**
	 * save 保存图片 
	 *
	 * @author zhuayi
	 */
	function save($filename)
	{
		return file_put_contents($filename,$this->file_data);
	}
	
	/* 合并图片 */
	function merge($merge_image,$x = 0,$y = 0,$width = 0,$height = 0)
	{
		/* 写入文件 */
		$merge_image_data = @file_get_contents($merge_image);
		if (empty($merge_image_data) || $merge_image_data === false)
		{
			throw new Exception("合并图片数据获取失败 <!-- {$merge_image_data} -->", 1);
		}

		$merge_image_filename = tempnam($this->temp,'zhuayi');
		file_put_contents($merge_image_filename,$merge_image_data);

		if ($width > 0)
		{
			$this->zomm($width,$height,$merge_image_filename);
		}
		
		//exit;
		$original_image_info = $this->info();
		$original_image = $this->create($original_image_info[2],$this->filename);

		$merge_image_info = $this->info($merge_image_filename);
		$merge_image = $this->create($merge_image_info[2],$merge_image_filename);

		$width = empty($width)?$merge_image_info[0]:$width;
		$height = empty($height)?$merge_image_info[1]:$height;

		imagecopymerge($original_image, $merge_image,$x, $y, 0, 0, $width,$height,100);

		$this->save_temp($original_image,$original_image_info[2]);

		return $this;
	}

	/* 文字水印 */
	function merge_text($text,$x,$y,$font_size,$color=array())
	{
		$original_image_info = $this->info();
		$original_image = $this->create($original_image_info[2],$this->filename);

		/* 如果坐标X 小于0,则按右侧为准*/
		if ($x < 0)
		{
			$x = 0 - $x - strlen($text)*3*1.5-2;
		}


		list($R,$G,$B) = $color;
		$bg = imagecolorallocate($original_image,$R,$G,$B);
		imagettftext($original_image,$font_size,0,$x,$y,$bg,$_SERVER["DOCUMENT_ROOT"].'/images/Microsoft Yahei.ttf',$text); 
		$this->save_temp($original_image,$original_image_info[2]);
		return $this;
	}
	
}
 
?>