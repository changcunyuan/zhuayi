<?php
/**
 * --------------------------------------
 * tree.php     ZCMS 数组生成树
 * 
 * @copyright    (C) 2005 - 2010  ZCMS
 * @licenes      http://www.zcms.cc
 * @lastmodify   2010-11-5
 * @author       zhuayi  
 * @QQ			 2179942
 * --------------------------------------
 * @array        要转换的数组
 * @parent       比较键值,一般为父类ID
 * @f            树的初始数
 * @gap          树枝间隔，一般用全角空格代替，这个根据页面自行设定
 * @branches     树杈，这个好理解吧
 *
 * $show['menu_list'] = $this->load_fun('tree',$show['menu_list'],'parent_id');
 */
function tree($array,$parent,$f=0,$fields='title',$gap='　',$branches='├─',$index_id='id')
{
	$ge = '└─';
	/* 如果传入通配符，那么把所有间隔负设置为空 */
	if ($gap == '^')
	{
		$gap ='';
	}
	if ($branches == '^')
	{
		$branches ='';
		$ge = '^';
	}
	
	$tree = '';
	foreach ($array as $key=>$val)
	{
		if ($val[$parent] == $f)
		{
			$val[$fields] = $gap.$branches.$val[$fields];
			$tree[] = $val;			
			$tree_f = tree($array,$parent,$val[$index_id],$fields,$gap.$gap,$ge);
			if (is_array($tree_f))
			{
				foreach ($tree_f as $vals)
				{
					$tree[] = $vals;
				}
			}
		}
	}
	return $tree;
}


?>