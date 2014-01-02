<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>榜单频道——职业经理人协会</title></head><link rel="stylesheet" type="text/css" href="__PUBLIC__/images/style_t.css" /><link rel="stylesheet" type="text/css" href="__PUBLIC__/images/css.css" /><script type="text/javascript" src="__PUBLIC__/js/ScrollPic.js"></script><script src="__PUBLIC__/js/jquery.js" type="text/javascript"></script><script src="__PUBLIC__/js/jquery.KinSlideshow-1.1.js" type="text/javascript"></script><script type="text/javascript">$(function(){
	$("#KinSlideshow").KinSlideshow({
			moveStyle:"down",
			titleBar:{titleBar_height:30,titleBar_bgColor:"#08355c",titleBar_alpha:0.5},
			titleFont:{TitleFont_size:12,TitleFont_color:"#FFFFFF",TitleFont_weight:"normal"},
			btn:{btn_bgColor:"#FFFFFF",btn_bgHoverColor:"#1072aa",btn_fontColor:"#000000",btn_fontHoverColor:"#FFFFFF",btn_borderColor:"#cccccc",btn_borderHoverColor:"#1188c0",btn_borderWidth:1}
	});
})
</script><style>.a,.b,.c,.d{text-align:center;}
.b, .c,.d{ display:none;}
#body em{ font-style:normal; font-size:36px;}
.buttons{
	border-bottom:solid #d1c8b8 4px;
	display:block;
	padding:8px;
	width:80px;-moz-border-radius: 1em 4em 1em 4em;
	border-radius: 1em 4em 1em 4em;
	text-align:center;
	margin:1px;
	background:#4b7975;
	text-decoration:none; color:#FFFFFF;
	float:left;font-family:Georgia, "Times New Roman", Times, serif; font-size:14px
}
.buttonHover{background:#86b8b4;border-bottom:solid #FF0000 4px;}
a.buttons:hover{background:#86b8b4;border-bottom:solid #FF0000 4px;}
#body {
	background:#919b9d;
	text-align:justify;
	overflow:hidden;
	color:#fff;
	padding:20px;
	-moz-border-radius: 1em 4em 1em 4em;
	border-radius: 1em 4em 1em 4em;	
	height:auto;
	width:450px;
	height:180px;
	overflow:hidden;
}
#wrap{
	text-align:left;
	overflow:hidden;
	width:500px;
	height:auto;
}
</style><script type="text/javascript">// <![CDATA[
$(document).ready(function() { 
$("#first-tab").addClass('buttonHover');
});
function navigate_tabs(container, tab)
{	
	$(".b").css('display' , 'none');
	$(".c").css('display' , 'none');
	$(".a").css('display' , 'none');
	$(".d").css('display' , 'none');
	$("#first-tab").removeClass('buttonHover');
	$("#second-tab").removeClass('buttonHover');
	$("#third-tab").removeClass('buttonHover');
	$("#fourth-tab").removeClass('buttonHover');
	$("#"+tab).addClass('buttonHover');
	$("."+container).show('slow');
}
// ]]></script><script type="text/javascript">function init(mod){
	mod = mod.toLowerCase();
	var checked = new Array()
	checked["index"] = 1;
	<?php if(is_array($categorylist)): $i = 0; $__LIST__ = $categorylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i;?>checked["<?php echo ($cate["module"]); ?>"] = <?php echo ($i+1); ?>;<?php endforeach; endif; else: echo "" ;endif; ?>	if(mod){
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
</script><body onLoad="init('<?php echo (MODULE_NAME); ?>')"><div id="header"><div id="logo"><img src="__PUBLIC__/images/zyjlr_logo.png" /></div><div id="login"><?php if($_SESSION['member']){ ?><div class="f_r">你好，<?php echo ($_SESSION['member']['loginname']); ?>！&nbsp;<a href="__ROOT__/member.php/login/loginout?url_self=__SELF__">退出</a></div><?php }else{ ?><form action="__ROOT__/member.php/login/loginpass" method="post" ><div class="f_r">昵称<input type="text" name="name" />&nbsp;<input type="radio" name="poro" id="per" value="2" /><label for="per">个</label>&nbsp;<input type="submit" value="登录"><br />密码<input type="password" name="password" />&nbsp;
						<input type="radio" name="poro" id="org" value="1" /><label for="org">团</label>&nbsp;
<input type="hidden" name="url_self" value="__SELF__" />&nbsp;<input type="button" onClick="location.href='__ROOT__/member.php/login/register'" value="注册" /></div></form><?php } ?></div><div id="search"><form action="__ROOT__/index.php/index/search" method="post" ><div class="f_r"><select name="cateid" style="width:90px;height:22px;"><option value="0">&nbsp;&nbsp;全部</option><?php if(is_array($categorylist)): $i = 0; $__LIST__ = $categorylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate_s): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cate_s['cid']); ?>">&nbsp;&nbsp;<?php echo ($cate_s['cname']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select><input type="text" name="keys" style="width:200px;" />&nbsp;
<input type="submit" value="搜索"></div></form></div><div id="navigation"><div id="nav_box"><li id="list_1" onMouseOver="javascript:toggle_nav(1)" style="background:url(__PUBLIC__/images/nav_l.png);"><a href="__ROOT__/index.php">首页</a></li><?php if(is_array($categorylist)): $i = 0; $__LIST__ = $categorylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i;?><li id="list_<?php echo ($i+1); ?>" onMouseOver="javascript:toggle_nav(<?php echo ($i+1); ?>)"><a href="__APP__/<?php echo ($cate["module"]); ?>"><?php echo ($cate["cname"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?><li id="list_9" onMouseOver="javascript:toggle_nav(9)" style="background:url(__PUBLIC__/images/nav_r.png);"><a href="__ROOT__/member.php">会员中心</a></li></div></div><div id="zzjs_nav1" class="headt" style="display:block">                欢迎来到职业经理人协会网站……
    </div><?php if(is_array($categorylist)): $j = 0; $__LIST__ = $categorylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($j % 2 );++$j;?><div id="zzjs_nav<?php echo ($j+1); ?>" class="headt" style="display:none"><?php if(is_array($cate['subcate'])): $i = 0; $__LIST__ = $cate['subcate'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><a href="__APP__/<?php echo ($cate["module"]); ?>/column?pcid=<?php echo ($sub["cid"]); ?>"><?php echo ($sub["cname"]); ?></a> |<?php endforeach; endif; else: echo "" ;endif; ?></div><?php endforeach; endif; else: echo "" ;endif; ?><div id="zzjs_nav9" class="headt" style="display:none"><a href="__ROOT__/member.php/index/contacts">人脉</a> | <a href="__ROOT__/member.php/index/group">圈子</a> | <a href="__ROOT__/member.php/index/mblog">微博</a> | <a href="__ROOT__/member.php/index/message">消息</a> | <a href="__ROOT__/member.php/index/webim">聊天室</a> | <a href="__ROOT__/member.php/index/setcenter">个人设置</a></div></div><div id="main" class="p_t5"><div id="m_l"><div class="list_"><div id="KinSlideshow" style="visibility:hidden; float:left;"><?php if(is_array($slidelist)): $i = 0; $__LIST__ = $slidelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sl): $mod = ($i % 2 );++$i;?><a href="<?php echo ($sl["link"]); ?>" target="_blank"><img src="__ROOT__/<?php echo ($sl["path"]); ?>" alt="<?php echo ($sl["title"]); ?>" width="1024" height="500" /></a><?php endforeach; endif; else: echo "" ;endif; ?></div></div><div class="list_ f_l m_t5"><div id="" class="nav_ bd bg"><div class="b_h"><span class="f_l">认证名单</span><a href="__URL__/column?pcid=29" target="_blank"><span class="f_r">更多&gt;&gt;</span></a></div><div class="b_m m_l5 f_l"><table width="100%" border="0"><?php if(is_array($article_n)): $i = 0; $__LIST__ = $article_n;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a_n): $mod = ($i % 2 );++$i;?><tr><td width="75%"><a href="__APP__/list/content?aid=<?php echo ($a_n["aid"]); ?>" title="<?php echo ($a_n["title"]); ?>" target="_blank"><li><?php echo (msubstr($a_n["title"],0,40)); echo ($a_n["is_new"]); ?></li></a></td><td width="25%" align="right"><span class="f_r"><?php echo (date("Y-m-d",$a_n["dateline"])); ?></span></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table></div></div><div id="" class="m_l5 bg nav_"><div id="wrap"><a href="javascript:navigate_tabs('a','first-tab');" title="<?php echo ($article_v[0]['title']); ?>" class="buttons" id="first-tab"><?php echo (msubstr($article_v[0]['title'],0,4)); if(empty($article_v[0]['title'])): ?>暂无内容<?php endif; ?></a><a href="javascript:navigate_tabs('b','second-tab');" title="<?php echo ($article_v[1]['title']); ?>" class="buttons" id="second-tab"><?php echo (msubstr($article_v[1]['title'],0,4)); if(empty($article_v[1]['title'])): ?>暂无内容<?php endif; ?></a><a href="javascript:navigate_tabs('c','third-tab');" title="<?php echo ($article_v[2]['title']); ?>" class="buttons" id="third-tab"><?php echo (msubstr($article_v[2]['title'],0,4)); if(empty($article_v[2]['title'])): ?>暂无内容<?php endif; ?></a><a href="javascript:navigate_tabs('d','fourth-tab');" title="<?php echo ($article_v[3]['title']); ?>" class="buttons" id="fourth-tab"><?php echo (msubstr($article_v[3]['title'],0,4)); if(empty($article_v[3]['title'])): ?>暂无内容<?php endif; ?></a><a href="__URL__/column?pcid=31" class="buttons" id="fourth-tab">更多&gt;&gt;</a><br clear="all" /><div id="body" align="center"><div class="a"><em><?php echo (substr($article_v[0]['description'],0,6)); ?></em><?php echo (msubstr($article_v[0]['description'],2,200)); if(!empty($article_v[0]['title'])): ?><a href="__APP__/list/content?aid=<?php echo ($article_v[0]['aid']); ?>" target="_blank"><font style="color:#f00;">【查看原文】</font></a><?php endif; ?></div><div class="b"><em><?php echo (substr($article_v[1]['description'],0,6)); ?></em><?php echo (msubstr($article_v[1]['description'],2,200)); if(!empty($article_v[1]['title'])): ?><a href="__APP__/list/content?aid=<?php echo ($article_v[1]['aid']); ?>" target="_blank"><font style="color:#f00;">【查看原文】</font></a><?php endif; ?></div><div class="c"><em><?php echo (substr($article_v[2]['description'],0,6)); ?></em><?php echo (msubstr($article_v[2]['description'],2,200)); if(!empty($article_v[2]['title'])): ?><a href="__APP__/list/content?aid=<?php echo ($article_v[2]['aid']); ?>" target="_blank"><font style="color:#f00;">【查看原文】</font></a><?php endif; ?></div><div class="d"><em><?php echo (substr($article_v[3]['description'],0,6)); ?></em><?php echo (msubstr($article_v[3]['description'],2,200)); if(!empty($article_v[3]['title'])): ?><a href="__APP__/list/content?aid=<?php echo ($article_v[3]['aid']); ?>" target="_blank"><font style="color:#f00;">【查看原文】</font></a><?php endif; ?></div></div></div></div></div></div><div id="roll"><DIV class="rollphotos"><DIV class=FixTitle><font class="f_l m_l5">优秀企业家</font><SPAN><A href="__URL__/column?pcid=30" target=_blank>更多&gt;&gt;</A></SPAN></DIV><DIV class=blk_29><DIV class=LeftBotton id=LeftArr></DIV><DIV class=Cont id=ISL_Cont_1><!-- 图片列表 begin --><?php if(is_array($entrelist)): $i = 0; $__LIST__ = $entrelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><DIV class=box><A class=imgBorder href="__URL__/entre?id=<?php echo ($vo['id']); ?>" target=_blank><IMG height=120 alt="<?php echo ($vo['company']); ?>" src="__ROOT__/<?php echo ($vo['pic']); ?>" width=90 
border=0></A><P><A href="__URL__/entre?id=<?php echo ($vo['id']); ?>" title="<?php echo ($vo['company']); ?>-<?php echo ($vo['position']); ?>"
target=_blank><?php echo ($vo['name']); ?></A></P></DIV><?php endforeach; endif; else: echo "" ;endif; ?><!-- 图片列表 end --></DIV><DIV class=RightBotton id=RightArr></DIV></DIV><SCRIPT language=javascript type=text/javascript><!--//--><![CDATA[//><!--
		var scrollPic_02 = new ScrollPic();
		scrollPic_02.scrollContId   = "ISL_Cont_1"; //内容容器ID
		scrollPic_02.arrLeftId      = "LeftArr";//左箭头ID
		scrollPic_02.arrRightId     = "RightArr"; //右箭头ID

		scrollPic_02.frameWidth     = 908;//显示框宽度
		scrollPic_02.pageWidth      = 152; //翻页宽度

		scrollPic_02.speed          = 10; //移动速度(单位毫秒，越小越快)
		scrollPic_02.space          = 10; //每次移动像素(单位px，越大越快)
		scrollPic_02.autoPlay       = true; //自动播放
		scrollPic_02.autoPlayTime   = 3; //自动播放间隔时间(秒)

		scrollPic_02.initialize(); //初始化
							
		//--><!]]></SCRIPT></DIV></div><div class="list_ f_l m_b8">&nbsp;</div></div><div id="footer" class=""><div class="f_l p_t5 b_t w100"><table><tr><td width="300" rowspan="3" align="right"><img src="__PUBLIC__/images/logo.png" /></td><td><span>版权所有：浙江省职业经理人协会  网站备案号：浙ICP备*********号 </span></td></tr><tr><td><span>联系地址：杭州凤起路290号三华园3号楼  邮编：310016 </span></td></tr><tr><td><span>技术支持：杭州和悦网络科技有限公司  电话：0571-88390676</span></td></tr></table></div></div></body></html>