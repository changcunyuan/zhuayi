<?php

function getedit($FCKeditor,$count='',$tables='',$width='100%',$height=300)
{

	$str = '<script type="text/JavaScript" charset="utf-8" src="/keditor/kindeditor-min.js"></script>'."\n";
	$str .= '<script type="text/JavaScript" charset="utf-8" src="/keditor/lang/zh_CN.js"></script>'."\n";
	$str .= '<script>'."\n";
	$str .= 'var editor;'."\n";
	$str .= "	KindEditor.ready(function(K) {editor = K.create('#".$FCKeditor."',{themeType:'simple',newlineTag:'br'})});\n";
	$str .= '</script>'."\n";
	$str .= '<textarea id="'.$FCKeditor.'" name="'.$FCKeditor.'" style="width:'.$width.'; height:'.$height.'px;">'.
$count.'</textarea>';

	return $str;
}
?>