<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>登录页面</title></head><link rel="stylesheet" type="text/css" href="__PUBLIC__/images/style.css" /><link rel="stylesheet" type="text/css" href="__PUBLIC__/images/css.css" /><script type="text/javascript" src="__PUBLIC__/js/ScrollPic.js"></script><script type="text/javascript">function init(mod){
	mod = mod.toLowerCase();
	var checked = new Array()
	checked["index"] = 1;
	<?php if(is_array($categorylist)): $i = 0; $__LIST__ = $categorylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i;?>checked["<?php echo ($cate["module"]); ?>"] = <?php echo ($i+1); ?>;<?php endforeach; endif; else: echo "" ;endif; ?>	checked["login"] = 9;
	
	if(mod){
		toggle_nav(checked[mod]);
	}else{
		toggle_nav(1);
	}
}
</script><script type="text/javascript">function fetch_object(idname){
	var my_obj = document.getElementById(idname);
	return my_obj;
}

function toggle_nav(obj){
	for (i = 1; i<=9; i++ ){
		var sub_nav = fetch_object("zzjs_nav" + i);
		var list_nav = fetch_object("list_" + i);
		if (obj == i){
			sub_nav.style.display = "block";
			list_nav.style.backgroundImage = "url(__PUBLIC__/images/nav_ch.png)";
		}else if(i == 1){
			sub_nav.style.display = "none";
			list_nav.style.backgroundImage = "url(__PUBLIC__/images/nav_l.png)";
		}else if(i == 9){
			sub_nav.style.display = "none";
			list_nav.style.backgroundImage = "url(__PUBLIC__/images/nav_r.png)";
		}else{
			sub_nav.style.display = "none";
			list_nav.style.backgroundImage = "url(__PUBLIC__/images/nav_m.png)";
		}
	}
}
</script><body onLoad="init('<?php echo (MODULE_NAME); ?>')"><div id="header"><div id="logo"><img src="__PUBLIC__/images/zyjlr_logo.png" /></div><div id="login"><?php if($_SESSION['member']){ ?><div class="f_r">你好，<?php echo ($_SESSION['member']['loginname']); ?>！&nbsp;<a href="__ROOT__/member.php/login/loginout?url_self=__SELF__">退出</a></div><?php }else{ ?><form action="__ROOT__/member.php/login/loginpass" method="post" ><div class="f_r">昵称<input type="text" name="name" />&nbsp;密码<input type="password" name="password" />&nbsp;<input type="radio" name="poro" id="per" value="2" /><label for="per">个</label><input type="radio" name="poro" id="org" value="1" /><label for="org">团</label>&nbsp;
<input type="hidden" name="url_self" value="__SELF__" /><input type="submit" value="登录">&nbsp;<input type="button" onClick="location.href='__ROOT__/member.php/login/register'" value="注册" /></div></form><?php } ?></div><div id="search"><form action="__ROOT__/index.php/index/search" method="post" ><div class="f_r"><select name="cateid" style="width:90px;height:22px;"><option value="0">&nbsp;&nbsp;全部</option><?php if(is_array($categorylist)): $i = 0; $__LIST__ = $categorylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate_s): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cate_s['cid']); ?>">&nbsp;&nbsp;<?php echo ($cate_s['cname']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select><input type="text" name="keys" style="width:200px;" />&nbsp;
<input type="submit" value="搜索"></div></form></div><div id="navigation"><div id="nav_box"><li id="list_1" onMouseOver="javascript:toggle_nav(1)" style="background:url(__PUBLIC__/images/nav_l.png);"><a href="__ROOT__">首页</a></li><?php if(is_array($categorylist)): $i = 0; $__LIST__ = $categorylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i;?><li id="list_<?php echo ($i+1); ?>" onMouseOver="javascript:toggle_nav(<?php echo ($i+1); ?>)"><a href="__ROOT__/index.php/<?php echo ($cate["module"]); ?>"><?php echo ($cate["cname"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?><li id="list_9" onMouseOver="javascript:toggle_nav(9)" style="background:url(__PUBLIC__/images/nav_r.png);"><a href="__ROOT__/member.php">会员中心</a></li></div></div><div id="zzjs_nav1" class="headt" style="display:block">                欢迎来到职业经理人协会网站……
    </div><?php if(is_array($categorylist)): $j = 0; $__LIST__ = $categorylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($j % 2 );++$j;?><div id="zzjs_nav<?php echo ($j+1); ?>" class="headt" style="display:none"><?php if(is_array($cate['subcate'])): $i = 0; $__LIST__ = $cate['subcate'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><a href="__ROOT__/index.php/<?php echo ($cate["module"]); ?>/column?pcid=<?php echo ($sub["cid"]); ?>"><?php echo ($sub["cname"]); ?></a> |<?php endforeach; endif; else: echo "" ;endif; ?></div><?php endforeach; endif; else: echo "" ;endif; ?><div id="zzjs_nav9" class="headt" style="display:none"><a href="__ROOT__/member.php/index/contacts">人脉</a> | <a href="__ROOT__/member.php/index/group">圈子</a> | <a href="__ROOT__/member.php/index/mblog">微博</a> | <a href="__ROOT__/member.php/index/message">消息</a> | <a href="__ROOT__/member.php/index/webim">聊天室</a> | <a href="__ROOT__/member.php/index/setcenter">个人设置</a></div></div><div id="main" class="p_t5"><div id="m_l" style="float:right;width:100%"><div class="list_ f_l m_t5" style="width:100%;"><div id="ad_1" class="m_t5" style="padding:20px 30px;background:#eee;"><form method="post" action="__APP__/login/loginpass"><table width="100%" border="0"><tr><td width="50%" height="35" align="center">&nbsp;
            			
                    </td><td width="50%" align="center" rowspan="6"><img src="__PUBLIC__/images/server.png"  /></td></tr><tr><td width="" height="25" align="center">            			账号：<input type="text" name="name" style="width:200px;" /></td></tr><tr><td width="" height="25" align="center">            			密码：<input type="password" name="password" style="width:200px;"  /></td></tr><tr><td width="" height="25" align="center"><input type="radio" name="poro" id="per" value="2" /><label for="per">个人会员&nbsp;&nbsp;&nbsp;&nbsp;</label><input type="radio" name="poro" id="org" value="1" /><label for="org">团体会员</label></td></tr><tr><td width="" height="25" align="center"><input type="submit" name="Submit" value="登录" />&nbsp;
						<input type="button" onClick="location.href='__ROOT__'" value="取消" /></td></tr><tr><td width="" align="center">            			还不是会员？&nbsp;马上<a href="__URL__/register"><font color="#FF0000">注册</font></a></td></tr></table></form></div></div></div><div class="list_ f_l m_b8">&nbsp;</div></div><div id="footer" class=""><div class="f_l p_t5 b_t w100"><table><tr><td width="300" rowspan="3" align="right"><img src="__PUBLIC__/images/logo.png" /></td><td><span>版权所有：浙江省职业经理人协会  网站备案号：浙ICP备*********号 </span></td></tr><tr><td><span>联系地址：杭州凤起路290号三华园3号楼  邮编：310016 </span></td></tr><tr><td><span>技术支持：杭州和悦网络科技有限公司  电话：0571-88390676</span></td></tr></table></div></div></body></html>