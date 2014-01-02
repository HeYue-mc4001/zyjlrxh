<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>消息——会员中心</title></head><link rel="stylesheet" type="text/css" href="__PUBLIC__/member/images/style.css" /><script type="text/javascript" src="__PUBLIC__/js/ScrollPic.js"></script><script type="text/javascript">function init(num){
	if(num){
		toggle_nav(num);
	}else{
		toggle_nav(1);
	}
}
</script><script type="text/javascript">function fetch_object(idname){
	var my_obj = document.getElementById(idname);
	return my_obj;
}

function toggle_nav(obj){
	for (i = 1; i<=6; i++ ){
		var sub_nav = fetch_object("zzjs_nav" + i);
		var list_nav = fetch_object("list_" + i);
		if (obj == i){
			sub_nav.style.display = "block";
			list_nav.style.backgroundImage = "url(__PUBLIC__/member/images/me_h" + obj + ".png)";
		}else{
			sub_nav.style.display = "none";
			list_nav.style.backgroundImage = "url(__PUBLIC__/member/images/me_" + i + ".png)";
		}
	}
}
</script><script language="javascript">//创建一个showhidediv的方法，直接跟ID属性
function showhidediv(id,pre){
	id = pre + id;
	var sbtitle=document.getElementById(id);
	if(sbtitle){
		if(sbtitle.style.display=='block'){
			sbtitle.style.display='none';
		}else{
			sbtitle.style.display='block';
		}
	}
}
</script><body onLoad="init('<?php echo ($num); ?>')"><div id="warp"><div id="header"><div class="header_menu"><ul><li><a href="#"><img src="__PUBLIC__/member/images/header_write.png" /></a></li><li><a href="#"><img src="__PUBLIC__/member/images/header_upload.png" /></a></li><li><a href="#"><img src="__PUBLIC__/member/images/header_qzone.png" /></a></li></ul></div></div><div id="main" class="p_t5 f_l"><div class="main_left"><ul class="ml_menu"><li><img src="__PUBLIC__/member/images/menu_top.png" /></li><li><a href="__URL__/message"><img src="__PUBLIC__/member/images/menu_message.png" /></a></li><li><a href="__URL__/contacts"><img src="__PUBLIC__/member/images/menu_contacts.png" /></a></li><li><a href="__URL__/circle"><img src="__PUBLIC__/member/images/menu_community.png" /></a></li><li><a href="__URL__/mblog"><img src="__PUBLIC__/member/images/menu_weibo.png" /></a></li><li><a href="__URL__/"><img src="__PUBLIC__/member/images/menu_chatroom.png" /></a></li><li><img src="__PUBLIC__/member/images/menu_bottom.png" /></li></ul></div><div id="m_l" style="float:left;width:800px;"><div id="navigation"><div id="nav_box"><li><img src="__PUBLIC__/member/images/t_bg_message.png" /></li><li id="list_1" onclick="javascript:toggle_nav(1)" style="background:url(__PUBLIC__/member/images/me_1.png) center no-repeat;width:130px;height:80px;">&nbsp;&nbsp;&nbsp;&nbsp;</li><li id="list_2" onclick="javascript:toggle_nav(2)" style="background:url(__PUBLIC__/member/images/me_2.png) center no-repeat;width:130px;height:80px;">&nbsp;&nbsp;&nbsp;&nbsp;</li><li id="list_3" onclick="javascript:toggle_nav(3)" style="background:url(__PUBLIC__/member/images/me_3.png) center no-repeat;width:130px;height:80px;">&nbsp;&nbsp;&nbsp;&nbsp;</li><li id="list_4" onclick="javascript:toggle_nav(4)" style="background:url(__PUBLIC__/member/images/me_4.png) center no-repeat;width:130px;height:80px;">&nbsp;&nbsp;&nbsp;&nbsp;</li><li id="list_5" onclick="javascript:toggle_nav(5)" style="background:url(__PUBLIC__/member/images/me_5.png) center no-repeat;width:130px;height:80px;">&nbsp;&nbsp;&nbsp;&nbsp;</li></div></div><div id="zzjs_nav1" class="contain" style="display:block"></div><div id="zzjs_nav2" class="contain" style="display:block"></div><div id="zzjs_nav3" class="contain" style="display:block"></div><div id="zzjs_nav4" class="contain" style="display:none"><table width="100%" border="0"><tr><th width="180">发件人</th><th width="">主题</th><th width="120">时间</th><th width="80">状态</th><th width="80">管理</th></tr><?php if(is_array($let_list)): $i = 0; $__LIST__ = $let_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$let): $mod = ($i % 2 );++$i;?><tr><td><?php echo ($let["mem"]["loginname"]); ?></td><td><a href="__URL__/readletter?lid=<?php echo ($let["id"]); ?>"><?php echo ($let["title"]); ?></a></td><td><?php echo (date("Y-m-d H:i",$let["posttime"])); ?></td><td><?php if(($let['status']) == "1"): ?>已读<?php else: ?>未读<?php endif; ?></td><td><a href="__URL__/delletter?lid=<?php echo ($let["id"]); ?>">删除</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table></div><div id="zzjs_nav5" class="contain" style="display:none"></div></div><div class="list_ f_l m_b8">&nbsp;</div></div></div></body></html>