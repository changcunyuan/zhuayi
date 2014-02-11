var bddiv;

/* data */
data = '<style type="text/css">.bdcs-container, .bdcs-container * {box-sizing:content-box;margin:0;padding:0;float:none;clear:none;overflow:hidden;white-space:nowrap;word-wrap:normal;border:0;background:none;width:auto;height:auto;max-width:none;min-width:none;max-height:none;min-height:none;border-radius:0;box-shadow:none;transition:none;}.bdcs-container table {border-collapse: collapse;border-spacing: 0;}.bdcs-container img {border: 0;display: block;}.bdcs-container ol, .bdcs-container ul {list-style: none;}.bdcs-container li {display: block;list-style: none;}.bdcs-container h1, .bdcs-container h2, .bdcs-container h3, .bdcs-container h4 {font-size: 100%;}.bdcs-container i, .bdcs-container em {font-style: normal;font-weight: normal;}.bdcs-container button, .bdcs-container input, .bdcs-container select, .bdcs-container textarea {font: 12px/1.5 Helvetica, Arial, \5b8b\4f53, sans-serif;}.bdcs-container .clearfix:after {content: \'\';display: block;clear: both;height: 0;}.bdcs-container .clearfix {zoom: 1;}/* diy */.bd_search{margin-bottom:10px;width:255px;border:1px #ccc solid;padding:3px;position:relative;overflow:hidden;}.bd_search .bd_input{float:left;height:22px;line-height:22px;padding:3px;border:0px;font-size:14px; outline: 0;color:#ccc;width:198px;}.bd_search .bd_button{float:right;height:30px;width:50px;font-size:14px;outline: 0;border-left:1px #ccc solid;cursor: pointer;}.bd_select_o{position:absolute;background:#fff;width:208px;z-index:10000;border:1px #ccc solid;border-top:0px;display:none;}.bd_select_o ul{margin-top:4px;}.bd_select_o li{padding:5px;}.bd_select_o li:hover{background:#f2f2f2;}</style><form name="f" action="http://www.baidu.com/s"><div class="bd_search" id="bd_search"><input type="text" name="wd" placeholder="this is a demo" class="bd_input" autocomplete="off" id="bd_input"/><button class="bd_button">搜索</button></div><div class="bd_select_o" id="bd_select_o"><ul id="bd_select_u"></ul></div></form>';

var select_data = '[\
					"\u6211\u662f\u6d4b\u8bd5\u6570\u636e1",\
					"\u6211\u662f\u6d4b\u8bd5\u6570\u636e2",\
					"\u6211\u662f\u6d4b\u8bd5\u6570\u636e3",\
					"\u6211\u662f\u6d4b\u8bd5\u6570\u636e4",\
					"\u6211\u662f\u6d4b\u8bd5\u6570\u636e5"\
					]';
var par_obj;

var is_set_data = 0;
var select_down_i = -1;
var isRight = false;
function init()
{
	/* if obj is empty , return false */
	bddiv = document.getElementById(bddiv_id);
	if (typeof(bddiv) == null)
	{
		return false;
	}

	bddiv.setAttribute('class',bddiv.className+' bdcs-container');

	/* 插入内容 */
	bddiv.innerHTML = data;

	par_obj = document.getElementById("bd_select_o");
	document.getElementById("bd_input").onkeyup = function(){
	    input_data(this);
	}
	document.getElementById("bd_input").onmousedown = function(){
		event =window.event||event;         
		if(event.button ==2)
		{
			isRight = true;
		}
	}

	document.getElementById("bd_input").onpaste = function(){
		if(isRight == true)
		{
			input_data(this);
		}
	}

	document.getElementById("bd_input").oncut = function(){
		if(isRight == true)
		{
			input_data(this);
		}
	}
}

function input_data(obj)
{
	obj.style.color = "#000";
    if (event.keyCode == 40)
    {
    	select_down_i++;
    }

    if (event.keyCode == 38)
    {
    	select_down_i--;
    }

    if (select_down_i > document.getElementById("bd_select_u").getElementsByTagName("li").length - 1)
    {
    	select_down_i = -1;
    	obj.value = obj.data;
    }

    if (select_down_i <= -1)
    {
    	select_down_i = -1;
    }

    console.log(obj.value);
    if (parseInt(event.keyCode) != 40 && parseInt(event.keyCode) != 38)
    {
    	select_down_i = -1 ;

    	if (obj.value == '')
    	{
    		remove_select();
    	}
    	else
    	{
    		set_bd_select_data();
    	}
    }
    else
    {
    	set_bd_select_li_bg(select_down_i);
    	return false;
    }
}


function set_bd_select_li_bg(hit_num)
{
	if (is_set_data == 0)
	{
		return false;
	}
	for( i = 0 ; i < document.getElementById("bd_select_u").getElementsByTagName("li").length; i++)
	{
		if (hit_num == i)
		{
			document.getElementById("bd_select_u").getElementsByTagName("li")[i].style.background = "#f2f2f2";
			set_data(document.getElementById("bd_select_u").getElementsByTagName("li")[i]);

		}
		else
		{
			document.getElementById("bd_select_u").getElementsByTagName("li")[i].style.background = "";
		}	
	}
}

function set_bd_select_data()
{
    var obj = document.getElementById("bd_select_u");
    reset_select_data = eval('(' + select_data + ')');

    obj.innerHTML = '';
    if ( par_obj.style.display == 'block')
    {
        //return false;
    }
   
    for( i = 0; i < reset_select_data.length; i++)
    {
       obj.innerHTML += "<li onclick=\"set_data(this)\">"+reset_select_data[i]+"</li>";
    }
    
    display_select();

    document.getElementById("bd_input").onblur = function(){

        setTimeout(function(){
            remove_select()
        },200);
    }
}


function display_select()
{
    par_obj.style.left = document.getElementById("bd_search").offsetLeft + "px";
    par_obj.style.top = document.getElementById("bd_search").offsetTop + 38 +"px";
    par_obj.style.display = "block";
    is_set_data = 1;
}

function remove_select()
{
    par_obj.style.display = "none";
    is_set_data = 0;
}
function set_data(obj)
{
	if (typeof(document.getElementById("bd_input").data) == "undefined")
	{
		document.getElementById("bd_input").data = document.getElementById("bd_input").value;
	}
    document.getElementById("bd_input").value = obj.innerHTML;
    //remove_select()
}
init();

