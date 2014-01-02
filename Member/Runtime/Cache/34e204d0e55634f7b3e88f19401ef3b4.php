<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>微博——会员中心</title></head><link rel="stylesheet" type="text/css" href="__PUBLIC__/member/images/style.css" /><script type="text/javascript" src="__PUBLIC__/js/ScrollPic.js"></script><script type="text/javascript">function init(num){
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
	for (i = 1; i<=4; i++ ){
		var sub_nav = fetch_object("zzjs_nav" + i);
		var list_nav = fetch_object("list_" + i);
		if (obj == i){
			sub_nav.style.display = "block";
			list_nav.style.backgroundImage = "url(__PUBLIC__/member/images/mb_h" + obj + ".png)";
		}else{
			sub_nav.style.display = "none";
			list_nav.style.backgroundImage = "url(__PUBLIC__/member/images/mb_" + i + ".png)";
		}
	}
}
</script><script language="javascript">//创建一个showhidediv的方法，直接跟ID属性
function showhidediv(id){
	id = "c_" + id;
	var sbtitle=document.getElementById(id);
	if(sbtitle){
		if(sbtitle.style.display=='block'){
			sbtitle.style.display='none';
		}else{
			sbtitle.style.display='block';
		}
	}
}
</script><body onLoad="init('<?php echo ($num); ?>')"><div id="warp"><div id="header"><div class="header_menu"><ul><li><a href="#"><img src="__PUBLIC__/member/images/header_write.png" /></a></li><li><a href="#"><img src="__PUBLIC__/member/images/header_upload.png" /></a></li><li><a href="#"><img src="__PUBLIC__/member/images/header_qzone.png" /></a></li></ul></div></div><div id="main" class="p_t5 f_l"><div class="main_left"><ul class="ml_menu"><li><img src="__PUBLIC__/member/images/menu_top.png" /></li><li><a href="__URL__/message"><img src="__PUBLIC__/member/images/menu_message.png" /></a></li><li><a href="__URL__/contacts"><img src="__PUBLIC__/member/images/menu_contacts.png" /></a></li><li><a href="__URL__/circle"><img src="__PUBLIC__/member/images/menu_community.png" /></a></li><li><a href="__URL__/mblog"><img src="__PUBLIC__/member/images/menu_weibo.png" /></a></li><li><a href="__URL__/"><img src="__PUBLIC__/member/images/menu_chatroom.png" /></a></li><li><img src="__PUBLIC__/member/images/menu_bottom.png" /></li></ul></div><div id="m_l" style="float:left;width:800px;"><div id="navigation"><div id="nav_box"><li><img src="__PUBLIC__/member/images/t_bg_weibo.png" /></li><li id="list_1" onclick="javascript:toggle_nav(1)" style="background:url(__PUBLIC__/member/images/mb_1.png) center no-repeat;width:150px;height:80px;">&nbsp;&nbsp;&nbsp;&nbsp;</li><li id="list_2" onclick="javascript:toggle_nav(2)" style="background:url(__PUBLIC__/member/images/mb_2.png) center no-repeat;width:150px;height:80px;">&nbsp;&nbsp;&nbsp;&nbsp;</li><li id="list_3" onclick="javascript:toggle_nav(3)" style="background:url(__PUBLIC__/member/images/mb_3.png) center no-repeat;width:150px;height:80px;">&nbsp;&nbsp;&nbsp;&nbsp;</li><li id="list_4" onclick="javascript:toggle_nav(4)" style="background:url(__PUBLIC__/member/images/mb_4.png) center no-repeat;width:150px;height:80px;">&nbsp;&nbsp;&nbsp;&nbsp;</li></div></div><div id="zzjs_nav1" class="contain" style="display:block"><?php if(is_array($mblog_new)): $i = 0; $__LIST__ = $mblog_new;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$new): $mod = ($i % 2 );++$i;?><div class="mblog"><table border="0" cellpadding="10" cellspacing="0" width="100%"><tr><td width="80" valign="top"><img class="icon" src="__PUBLIC__/member/icon/1.jpg" /></td><td valign="top"><div class="nick"><?php echo ($new["owner"]["loginname"]); ?></div><div class="m_con"><?php echo ($new["comment"]); ?></div><div class="op"><div class="f_l"><?php echo (date("Y-m-d",$new["parttime"])); ?></div><div class="f_r w80" onclick="showhidediv(<?php echo ($new["id"]); ?>)">评论(<?php echo ($new["com_num"]); ?>)</div><div class="f_r w80" onclick="window.location.href='__URL__/colmblog?bid=<?php echo ($new["id"]); ?>'">收藏</div></div><div class="m_com f_l w600" id="c_<?php echo ($new["id"]); ?>"><table ><tr><td width="60"></td><td width="440"></td><td width="100"></td></tr><tr><form method="post" action="__APP__/index/addmblog"><td colspan="2"><textarea name="comment" style="width:480px;height:45px;overflow:auto;margin:8px;"></textarea><input type="hidden" name="bid" value="<?php echo ($new["id"]); ?>" /><input type="hidden" name="num" value="1" /></td><td align="left" valign="middle"><input type="submit" name="Submit" value="发布" style="width:50px;height:50px;" /></td></form></tr><?php if(is_array($new['comments'])): $i = 0; $__LIST__ = $new['comments'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><tr class="floor"><td valign="top"><img src="__PUBLIC__/member/icon/1.jpg" width="60" height="60" /></td><td colspan="2" valign="top"><div class="nick f_l w500"><?php echo ($sub["owner"]["loginname"]); ?></div><div class="m_con f_l w500"><?php echo ($sub["comment"]); ?></div></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table></div></td></tr></table></div><?php endforeach; endif; else: echo "" ;endif; ?></div><div id="zzjs_nav2" class="contain" style="display:block"><table width="100%" border="0"><tr><form method="post" action="__APP__/index/addmblog"><td width="100%" colspan="2"><textarea name="comment" style="width:735px;height:100px;"></textarea><input type="hidden" name="bid" value="0" /><input type="hidden" name="num" value="2" /><input type="submit" name="Submit" value="发布" /></td></form></tr><?php if(is_array($mblog_my)): $i = 0; $__LIST__ = $mblog_my;if( count($__LIST__)==0 ) : echo "暂无微博！" ;else: foreach($__LIST__ as $key=>$my): $mod = ($i % 2 );++$i;?><tr><td width="75%"><li><?php echo (msubstr($my["comment"],0,12)); ?></li></td><td width="25%" align="right"><span class="f_r"><?php echo (date("Y-m-d",$my["parttime"])); ?></span>&nbsp;&nbsp;&nbsp;
                        	<a href="__URL__/delmblog?bid=<?php echo ($my["id"]); ?>&num=2">删除</a></td></tr><?php endforeach; endif; else: echo "暂无微博！" ;endif; ?></table></div><div id="zzjs_nav3" class="contain" style="display:block"><table width="100%" border="0"><?php if(is_array($mblog_com)): $i = 0; $__LIST__ = $mblog_com;if( count($__LIST__)==0 ) : echo "暂无评论！" ;else: foreach($__LIST__ as $key=>$com): $mod = ($i % 2 );++$i;?><tr><td width="75%"><li><?php echo (msubstr($com["comment"],0,12)); ?></li></td><td width="25%" align="right"><span class="f_r"><?php echo (date("Y-m-d",$com["parttime"])); ?></span>&nbsp;&nbsp;&nbsp;
                        	<a href="__URL__/delmblog?bid=<?php echo ($com["id"]); ?>&num=3">删除</a></td></tr><?php endforeach; endif; else: echo "暂无评论！" ;endif; ?></table></div><div id="zzjs_nav4" class="contain" style="display:none"><table width="100%" border="0"><?php if(is_array($mblog_col)): $i = 0; $__LIST__ = $mblog_col;if( count($__LIST__)==0 ) : echo "暂无收藏！" ;else: foreach($__LIST__ as $key=>$col): $mod = ($i % 2 );++$i;?><tr><td width="75%"><li><?php echo (msubstr($col["comment"],0,12)); ?></li></td><td width="25%" align="right"><span class="f_r"><?php echo (date("Y-m-d",$col["parttime"])); ?></span>&nbsp;&nbsp;&nbsp;
                            <a href="__URL__/delcollect?bid=<?php echo ($col["id"]); ?>&num=4">删除</a></td></tr><?php endforeach; endif; else: echo "暂无收藏！" ;endif; ?></table></div></div><div class="list_ f_l m_b8">&nbsp;</div></div></div></body></html>