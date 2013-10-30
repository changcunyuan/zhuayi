<?php
/*
 * taobaoke.php     Zhuayi 淘宝客
 *
 * @copyright    (C) 2005 - 2010  zhuayi
 * @licenes      http://www.zhuayi.net
 * @lastmodify   2010-10-28
 * @author       zhuayi
 * @QQ			 2179942
 */

class taobaoke extends http
{

	/* API地址,沙箱环境 */
	public $ap_url = 'http://gw.api.taobao.com/router/rest';
	
	/* 应用名称 */
	public $api_name;

	public $app_key;

	public $app_secret;

	private $version = '2.0';

	private $format = 'json';

	private $sign_method = 'md5';

	private $cache_outtime = '86400';

	/* 淘宝帐号 */
	public $nick = '何夕';

	public $taobao_cookie = 'ck1=W8rspjqj7rbPSA%3D%3D; miid=205951391434925211; uc2=pt=WvZh8Uk%2Fmr%2BK7nn0xKTKydn0pFR4er%2Fg55YOJAE%3D&wuf=http%3A%2F%2Fservice.aliyun.com%2Fsupport%2Fonlinecs%2Focs.htm%3FsourceId%3D1225141339%26outToken%3DF9uTEwfa0WZqwlYd7uhSDd7ktU1bxzW3DhsFte2irF8krzv5JPltSA66rmiLpcAz; cna=+6iyCHBChBcCASBb9nK4RcTC; v=0; tk_trace=oTRxOWSBNwn9dPyscxqAz9fIO7IIrg7GPWjEmwYJxLhtCHOPLZYH6o8muLTKLR4RI%2BcD0wFvvtcpeVDjmuMfGGwbsn6Y%2Fhoo%2B4Z2DxagQOvg%2BmefI3AT7%2FdKa%2B2nIO2gJzkl6He%2B7CLGhI9STtOK5crMkpqz9lp%2BIB6AjIubBEp2lSSvkjpic%2FuxZABSXY0hpS2kb6PthKrt6PCoFYd6xGO1g5y7MGJ%2Fx8sJC9xE8amwQKMTtbnt2%2BPCMsstFWYv7dCVikf8odVcAGidulV6JU9Cq9Bz0wTM55tdYnX9sAzw; lastgetwwmsg=MTM0NTczMjE4NQ%3D%3D; tg=0; _cc_=V32FPkk%2Fhw%3D%3D; t=1b4a01887b20ca15435b0eef70668136; unb=83412048; _nk_=%5Cu5B89%5Cu9759%5Cu5426; _l_g_=Ug%3D%3D; cookie1=BxVWiznDL84ZPXGR8Ji2hk9kBc3jY%2FEDMZiNhvTL9jc%3D; cookie2=7d4eeb1d4696990b4bd7ea2a599ff055; cookie17=W8rspjqj7rY%3D; tracknick=%5Cu5B89%5Cu9759%5Cu5426; sg=%E5%90%A68f; mt=ci=1_1; lzstat_uv=31244113642937193701|2581747@2938535@2581759; lzstat_ss=3181989437_13_1345761065_2581747|1833145778_10_1345761039_2938535|1672720417_10_1345761065_2581759|3388913081_2_1345760110_2879138|3948066602_0_1345760163_2938538; _tb_token_=e3935833df346; l=%E5%AE%89%E9%9D%99%E5%90%A6::1345736257461::11; x=e%3D1%26p%3D*%26s%3D0%26c%3D1%26f%3D0%26g%3D0%26t%3D0; uc1=lltime=1345731279&cookie14=UoLYsvBJZYGUqg%3D%3D&existShop=false&cookie16=U%2BGCWk%2F74Mx5tgzv3dWpnhjPaQ%3D%3D&cookie21=UIHiLt3xSw%3D%3D&tag=7&cookie15=Vq8l%2BKCLz3%2F65A%3D%3D; mpp=t%3D1%26m%3D%26h%3D1345733624509%26l%3D1345733621232';

	/* 淘宝客PID */
	public $pid;

	/**
	 * 构 造 函 数
	 *
	 * @author zhuayi
	 */
	function __construct()
	{
		global $cache;
		parent::__construct();

		$this->cache = &$cache;
	}

	/* 系统参数 */
	function system_par()
	{
		$arr['sign_method'] = $this->sign_method;
		$arr['timestamp'] = date("Y-m-d H:i:s");
		$arr['format'] = $this->format;
		$arr['app_key'] = $this->app_key;
		$arr['v'] = $this->version;
		return $arr;
	}

	function get_sign($array)
	{
		/* 合并参数 */
		ksort($array);

		/* 计算签名 */
		$string = $this->app_secret;

		foreach ($array as $key=>$val)
		{
			$string .= "$key$val";
			$array[urlencode($key)] = urlencode($val);
		}
		$string .= $this->app_secret;

		return strtoupper(md5($string));

	}

	function run($array = array(),$method = 'get')
	{
		$cache_key = "taobao-".md5(json_encode($array));
		$reset = $this->cache->get($cache_key);
		if ($reset === false)
		{
			$array = array_merge($array,$this->system_par());

			/* 合并参数 */
			$array['sign'] = $this->get_sign($array);

			if ($method == 'get')
			{
				$reset = $this->get($this->ap_url,$array);
			}
			else
			{
				$this->post($this->ap_url,$array);
			}

			if ($this->status > 0)
			{
				throw new Exception($this->error, -1);
			}
			$this->results = str_replace('	','',$this->results);
			$reset = str_replace("\n","",$this->results);
			$reset = json_decode($reset,true);

			if (!is_array($reset))
			{
				throw new Exception("接口返回数据异常!", -1);
			}
			/* 判断是否调用失败 */
			if (isset($reset['error_response']))
			{
				throw new Exception($reset['error_response']['msg'], $reset['error_response']['code']);
			}
			else
			{
				$this->cache->set($cache_key,$reset,$this->cache_outtime);
			}
			
		}
		
		return $reset;

	}

	/* 获取后台供卖家发布商品的标准商品类目 */
	function get_itemcats_by_parent_cid($parent_cid = 0)
	{
		$arr['method'] = 'taobao.itemcats.get';
		$arr['fields'] = 'cid,parent_cid,name,is_parent';
		$arr['parent_cid'] = $parent_cid;
		return $this->run($arr);
	
	}

	/* 获取淘宝客商品列表 */
	function get_item_list_by_cid($cid,$page_no = 1,$sort,$sevendays_return,$cash_ondelivery,$mall_item)
	{
		$arr['method'] = 'taobao.taobaoke.items.get';
		$arr['fields'] = 'num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location,volume';
		$arr['nick'] = $this->nick;
		$arr['pid'] = $this->pid;
		$arr['cid'] = $cid;
		$arr['page_no'] = $page_no;
		$arr['sort'] = $sort;
		$arr['sevendays_return'] = $sevendays_return;
		$arr['cash_ondelivery'] = $cash_ondelivery;
		$arr['mall_item'] = $mall_item;
		return $this->run($arr,'post');
	}

	/* 获取淘宝客商品列表 */
	function get_item_list_by_keyword($keyword,$page_no = 1,$sort,$sevendays_return,$cash_ondelivery,$mall_item,$keyword)
	{
		$arr['method'] = 'taobao.taobaoke.items.get';
		$arr['fields'] = 'num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location,volume';
		$arr['nick'] = $this->nick;
		//$arr['pid'] = $this->pid;
		//$arr['cid'] = $cid;
		$arr['page_no'] = $page_no;
		$arr['sort'] = $sort;
		$arr['sevendays_return'] = $sevendays_return;
		$arr['cash_ondelivery'] = $cash_ondelivery;
		$arr['mall_item'] = $mall_item;
		$arr['keyword'] = $keyword;
		return $this->run($arr,'post');
	}

	/* 批量获取商品信息 */
	function get_item_list_by_num_iids($ids)
	{
		if (is_array($ids))
		{
			foreach ($ids as $key=>$val)
			{
				$ids[$key] = intval($val);
			}
			$ids = implode(',',$ids);
		}
		else
		{
			$ids = intval($ids);
		}

		$arr['method'] = 'taobao.items.list.get';
		$arr['num_iids'] = $ids;
		$arr['fields'] = 'detail_url,num_iid,title,nick,type,cid,seller_cids,input_pids,input_str,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,approve_status,postage_id,product_id,auction_point,property_alias,prop_img,video,outer_id,is_virtual';
		return $this->run($arr,'post');
	}

	/* 得到单个商品信息 */
	function get_item_by_num_iid($ids)
	{
		if (is_array($ids))
		{
			foreach ($ids as $key=>$val)
			{
				$ids[$key] = intval($val);
			}
			$ids = implode(',',$ids);
		}
		else
		{
			$ids = intval($ids);
		}
		$arr['method'] = 'taobao.item.get';
		$arr['fields'] = 'detail_url,num_iid,title,nick,type,cid,seller_cids,props,input_pids,input_str,desc,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,approve_status,postage_id,product_id,auction_point,property_alias,item_img,prop_img,sku,video,outer_id,is_virtual';
		$arr['num_iid'] = '12665891639,16888700603';//$ids;
		return $this->run($arr,'post');
	}

	/* 获取卖家UID */
	function get_user_id_by_num_iid($num_iid)
	{
		$num_iid = intval($num_iid);
		if (empty($num_iid))
		{
			throw new Exception("参数错误", -1);
		}

		$array['tmail'] = false;
		$url = "http://item.taobao.com/item.htm?id={$num_iid}";
		$this->get($url);

		/* 判断是否天猫商品 */
		if ($this->http_status == '302' && strpos($this->Location,'tmall') !== false)
		{
			$array['tmail'] = true;
			$url = "http://detail.tmall.com/item.htm?id={$num_iid}&tbpm=3";
			$this->get($url);
			preg_match('/spuId=([0-9]+)/i', $this->results,$spuId);
			$array['spuId'] = $spuId[1];
		}
		preg_match('/userid=([0-9]+)/i', $this->results,$list);

		if (empty($list[1]))
		{
			return false;
		}
		$array['userid'] = $list[1];

		return $array;
	}

	/* 查询tmail的评价 */
	function get_tmail_comment_list_by_num_iid($num_iid,$user_info)
	{
		$num_iid = intval($num_iid);
		if (empty($num_iid))
		{
			throw new Exception("参数错误!", -1);
		}
		if (empty($user_info['spuId']) || empty($user_info['userid']))
		{
			throw new Exception("tmail用户获取失败!", -1);
		}
		$url = "http://rate.tmall.com/list_detail_rate.htm?itemId={$num_iid}&spuId={$user_info['spuId']}&sellerId={$user_info['userid']}&order=0&forShop=1&append=0&currentPage=1&callback=jsonp1345732669988";

		$this->referer = 'http://detail.tmall.com/';
		$this->get($url);

		$this->results = trim(iconv('gbk','utf-8',$this->results));
		$this->results = str_replace('jsonp1345732669988(','',$this->results);
		$this->results = substr($this->results, 0,strlen($this->results)-1);
		$this->results = json_decode($this->results,true);
		$reset_tmp = array();
		if (!is_array($this->results['rateDetail']['rateList']))
		{
			return $reset_tmp;
		}
		$reset = $this->results['rateDetail']['rateList'];

		$reset_tmp = array();
		foreach ($reset as $key=>$val)
		{
			$reset_tmp[$key]['content'] = $val['rateContent'];
			$reset_tmp[$key]['userid'] = $val['displayUserNumId'];
			$reset_tmp[$key]['nick'] = $val['displayUserNick'];
			$reset_tmp[$key]['date'] = $val['rateDate'];
		}
		return $reset_tmp;
	}

	/* 取普通淘宝商品评论接口 */
	function get_goods_comment_list_by_num_iid($num_iid,$user_info)
	{
		$num_iid = intval($num_iid);
		if (empty($num_iid))
		{
			throw new Exception("参数错误!", -1);
		}
		if (empty($user_info['userid']))
		{
			throw new Exception("用户获取失败!", -1);
		}

		$url = "http://rate.taobao.com/feedRateList.htm?userNumId={$user_info['userid']}&auctionNumId={$num_iid}&siteID=7&currentPageNum=1&rateType=&orderType=sort_weight&showContent=1&attribute=&callback=jsonp_reviews_list";
		$this->referer = 'http://nvzhuang.taobao.com/';
		$this->get($url);

		$this->results = trim(iconv('gbk','utf-8',$this->results));
		$this->results = str_replace('jsonp_reviews_list(','',$this->results);
		$this->results = substr($this->results, 0,strlen($this->results)-1);
		$reset = json_decode($this->results,true);
		$reset_tmp = array();
		if (!is_array($reset['comments']))
		{
			return $reset_tmp;
		}
		$reset = $reset['comments'];
		foreach ($reset as $key=>$val)
		{
			$reset_tmp[$key]['content'] = $val['content'];
			$reset_tmp[$key]['userid'] = $val['user']['userId'];
			$reset_tmp[$key]['nick'] = $val['user']['nick'];
			$reset_tmp[$key]['date'] = str_replace('.','-',$val['date']);
		}
		return $reset_tmp;
	}

	/* 商品评价查询接口 */
	function get_item_comment_list_by_num_iid($num_iid)
	{
		$cache_key = "get_item_comment_list_by_num_iid-{$num_iid}";
		$reset = $this->cache->get($cache_key);

		if ($reset === false)
		{
			/* 先获取用户信息 */
			$user_info = $this->get_user_id_by_num_iid($num_iid);
			if (empty($user_info['userid']))
			{
				throw new Exception("用户获取失败!", -1);
			}
			$num_iid = intval($num_iid);

			if ($user_info['tmail'] === false)
			{

				$reset = $this->get_goods_comment_list_by_num_iid($num_iid,$user_info);
				
			}
			else
			{
				
				$reset =  $this->get_tmail_comment_list_by_num_iid($num_iid,$user_info);
			}
			if (is_array($reset))
			{
				$this->cache->set($cache_key,$reset,$this->cache_outtime);
			}
		}
		return $reset;
		
	}

	/* 获取用户头像 */
	function get_user_avatar_by_nick($nick)
	{
		$arr['method'] = 'taobao.user.get';
		$arr['fields'] = 'avatar';
		$arr['nick'] = $nick;
		return $this->run($arr,'post');
	}

	/* 淘宝商品转换 **** 已废弃 */
	function get_items_convert_by_num_ids($ids)
	{
		return $this->get_item_list_by_num_iids($ids);
		if (is_array($ids))
		{
			foreach ($ids as $key=>$val)
			{
				$ids[$key] = intval($val);
			}
			$ids = implode(',',$ids);
		}
		else
		{
			$ids = intval($ids);
		}

		$arr['method'] = 'taobao.taobaoke.items.convert';
		$arr['nick'] = $this->nick;
		$arr['fields'] = 'num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location,volume';
		$arr['num_iids'] = $ids;
		return $this->run($arr);
	}

	/* 查看是否有优惠 */
	function get_items_promotion_by_id($id)
	{
		$arr['method'] = 'taobao.ump.promotion.get';
		$arr['item_id'] = $id;
		
		return $this->run($arr);
	}


	/* 获取用户信息 */
	function get_user_info($session)
	{
		$arr['method'] = 'taobao.user.buyer.get';
		if (empty($this->access_token))
		{
			$arr['session'] = $session;
		}
		$arr['fields'] = 'nick,sex,buyer_credit,avatar,has_shop,vip_info';
		return $this->run($arr);
	}

	/* 根据商铺地址获取商品列表 */
	function get_item_ids_by_shop_url($shop_url)
	{
		$shop_url = htmlspecialchars_decode($shop_url);

		if (empty($shop_url))
		{
			throw new Exception("参数错误!", -1);
		}

		$this->cookie = $this->taobao_cookie;
		$this->get($shop_url)->links('item.htm?id=');
		$this->results = array_unique($this->results);

		foreach ($this->results as $val)
		{
			if (strpos($val,'atpanel') === false)
			{
				preg_match('/id=([0-9]+)/i', $val,$list);
				$taobao_num_iids[$val] = $list[1];
			}
		}
		return $taobao_num_iids = array_unique($taobao_num_iids);
	}

	/* */
	function get_item_list_by_ids($taobao_num_iids)
	{
		/* 分组,每20个一组 */
		$taobao_url = array();
		$i = $j =1;
		foreach ($taobao_num_iids as $key=>$val)
		{
			if ( $i % 20 == 0)
			{
				$j++;	
			}
			$taobao_url[$j][$key] = $val;
			
			$i++;
		}
		
		$goods_list = array();
	
		foreach ($taobao_url as $val)
		{
			$reset_tmp = array();
			$taobao_url_tmp = array();
			$taobao_url_tmp = array_flip($val);
			$reset = $this->get_items_convert_by_num_ids($val);
			
			$reset = $reset['items_list_get_response']['items']['item'];

			foreach ($reset as $key=>$val2)
			{
				$reset_tmp[$taobao_url_tmp[$val2['num_iid']]] = $val2;
			}
	
			if (is_array($reset_tmp))
			{
				$goods_list = array_merge($goods_list,$reset_tmp);
			}
		}
		return $goods_list;
	}

	/* 获取商铺信息 */
	function get_shop_info_by_nick($nick)
	{
		$arr['method'] = 'taobao.shop.get';
		$arr['nick'] = $nick;
		$arr['fields'] = 'sid,cid,title,nick,desc,bulletin,pic_path,created,modified';
		return $this->run($arr);
	}


	

}
 
?>