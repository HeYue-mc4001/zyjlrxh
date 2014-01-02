<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title><?php if(($tid) == ""): ?>群聊<?php else: ?>话题<?php endif; ?>——会员中心</title></head><link rel="stylesheet" type="text/css" href="__PUBLIC__/member/images/style.css" /><script type="text/javascript" src="__PUBLIC__/js/ScrollPic.js"></script><script type="text/javascript">function init(num){
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
			list_nav.style.backgroundImage = "url(__PUBLIC__/member/images/cir_h" + obj + ".png)";
		}else{
			sub_nav.style.display = "none";
			list_nav.style.backgroundImage = "url(__PUBLIC__/member/images/cir_" + i + ".png)";
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
</script><body onLoad="init('<?php echo ($num); ?>')"><div id="warp"><div id="header"><div class="header_menu"><ul><li><a href="#"><img src="__PUBLIC__/member/images/header_write.png" /></a></li><li><a href="#"><img src="__PUBLIC__/member/images/header_upload.png" /></a></li><li><a href="#"><img src="__PUBLIC__/member/images/header_qzone.png" /></a></li></ul></div></div><div id="main" class="p_t5 f_l"><?php if($tid){ ?><div>主题：<?php echo ($topic['content']); ?></div><?php } if(is_array($chat_list)): $i = 0; $__LIST__ = $chat_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$chat): $mod = ($i % 2 );++$i;?><div><?php echo ($chat["author"]["loginname"]); ?>说：<?php echo ($chat["content"]); ?>【<?php echo (date("Y-m-d H:i",$chat["posttime"])); ?>】<?php if(($power) == "1"): ?><a href="__URL__/addtopic?tid=<?php echo ($chat["id"]); ?>">设为话题</a><?php endif; ?></div><?php endforeach; endif; else: echo "" ;endif; ?><div><table width="100%" border="0"><tr><form action="__APP__/index/addchat"  method="post"><td colspan="2">                            聊天内容：<textarea name="content" style="width:480px;height:45px;overflow:auto;margin:8px;border:#000 1px solid;"></textarea><br /><input type="hidden" name="cid" value="<?php echo ($cid); ?>" /><input type="hidden" name="tid" value="<?php echo ($tid); ?>" /><input type="hidden" name="num" value="1" /></td><td align="left" valign="middle"><input type="submit" name="Submit" value="发布" style="width:50px;height:50px;" /></td></form></tr></table></div><div class="list_ f_l m_b8">&nbsp;</div></div></div></body></html>