<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>首页——职业经理人协会</title></head><link rel="stylesheet" type="text/css" href="__PUBLIC__/images/style.css" /><link rel="stylesheet" type="text/css" href="__PUBLIC__/images/css.css" /><script type="text/javascript" src="__PUBLIC__/js/ScrollPic.js"></script><script src="__PUBLIC__/js/jquery.js" type="text/javascript"></script><script src="__PUBLIC__/js/jquery.KinSlideshow-1.1.js" type="text/javascript"></script><script type="text/javascript">$(function(){
	$("#KinSlideshow").KinSlideshow({
			moveStyle:"down",
			isHasTitleFont:true,
			titleBar:{titleBar_height:30,titleBar_bgColor:"#08355c",titleBar_alpha:0.5},
			titleFont:{TitleFont_size:12,TitleFont_color:"#FFFFFF",TitleFont_weight:"normal"},
			btn:{btn_bgColor:"#FFFFFF",btn_bgHoverColor:"#1072aa",btn_fontColor:"#000000",btn_fontHoverColor:"#FFFFFF",btn_borderColor:"#cccccc",btn_borderHoverColor:"#1188c0",btn_borderWidth:1}
	});
})
</script><script type="text/javascript">function init(mod){
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
    </div><?php if(is_array($categorylist)): $j = 0; $__LIST__ = $categorylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($j % 2 );++$j;?><div id="zzjs_nav<?php echo ($j+1); ?>" class="headt" style="display:none"><?php if(is_array($cate['subcate'])): $i = 0; $__LIST__ = $cate['subcate'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><a href="__APP__/<?php echo ($cate["module"]); ?>/column?pcid=<?php echo ($sub["cid"]); ?>"><?php echo ($sub["cname"]); ?></a> |<?php endforeach; endif; else: echo "" ;endif; ?></div><?php endforeach; endif; else: echo "" ;endif; ?><div id="zzjs_nav9" class="headt" style="display:none"><a href="__ROOT__/member.php/index/contacts">人脉</a> | <a href="__ROOT__/member.php/index/group">圈子</a> | <a href="__ROOT__/member.php/index/mblog">微博</a> | <a href="__ROOT__/member.php/index/message">消息</a> | <a href="__ROOT__/member.php/index/webim">聊天室</a> | <a href="__ROOT__/member.php/index/setcenter">个人设置</a></div></div><div id="main" class="p_t5"><div id="m_l"><div class="list_"><div id="photo_wall"><div id="KinSlideshow" style="visibility:hidden; float:left;"><?php if(is_array($img_list)): $i = 0; $__LIST__ = $img_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i;?><a href="__APP__/info/content?aid=<?php echo ($img["aid"]); ?>" target="_blank"><img src="__ROOT__/<?php echo ($img["pic"]); ?>" alt="<?php echo ($img["title"]); ?>" width="361" height="287" /></a><?php endforeach; endif; else: echo "" ;endif; ?></div></div><div id="news" class="m_l5 bd bg"><div class="b_h"><span class="f_l"><img src="__PUBLIC__/images/news.gif" />要闻推荐</span><a href="__APP__/info"><span class="f_r">更多&gt;&gt;</span></a></div><div class="b_m m_l5 f_l"><table width="100%" border="0"><?php if(is_array($infolist)): $i = 0; $__LIST__ = $infolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$info): $mod = ($i % 2 );++$i;?><tr><td width="75%"><a href="__APP__/info/content?aid=<?php echo ($info["aid"]); ?>" title="<?php echo ($info["title"]); ?>"><li><?php echo (msubstr($info["title"],0,14)); echo ($info["is_new"]); ?></li></a></td><td width="25%" align="right"><span class="f_r"><?php echo (date("Y-m-d",$info["dateline"])); ?></span></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table></div></div></div><div class="list_ f_l m_t5"><div id="ad_1" class="m_t5"><a href="<?php echo ($advdata['link']); ?>" target="_blank"><img src="__ROOT__/<?php echo ($advdata['path']); ?>" width="767" height="120" /></a></div></div><div class="list_ f_l m_t5"><div id="" class="nav_ bd bg"><div class="b_h"><span class="f_l">最新活动</span><a href="__APP__/active"><span class="f_r">更多&gt;&gt;</span></a></div><div class="b_m m_l5 f_l"><table width="100%" border="0"><?php if(is_array($actlist)): $i = 0; $__LIST__ = $actlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$act): $mod = ($i % 2 );++$i;?><tr><td width="75%"><a href="__APP__/active/content?aid=<?php echo ($act["aid"]); ?>" title="<?php echo ($act["title"]); ?>"><li><?php echo (msubstr($act["title"],0,12)); echo ($act["is_new"]); ?></li></a></td><td width="25%" align="right"><span class="f_r"><?php echo (date("Y-m-d",$act["dateline"])); ?></span></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table></div></div><div id="" class="m_l5 bd bg nav_"><div class="b_h"><span class="f_l">热门榜单</span><a href="__APP__/list"><span class="f_r">更多&gt;&gt;</span></a></div><div class="b_m m_l5 f_l"><table width="100%" border="0"><?php if(is_array($li_list)): $i = 0; $__LIST__ = $li_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($i % 2 );++$i;?><tr><td width="75%"><a href="__APP__/list/content?aid=<?php echo ($li["aid"]); ?>" title="<?php echo ($li["title"]); ?>"><li><?php echo (msubstr($li["title"],0,12)); echo ($li["is_new"]); ?></li></a></td><td width="25%" align="right"><span class="f_r"><?php echo (date("Y-m-d",$li["dateline"])); ?></span></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table></div></div></div><div class="list_ f_l m_t5"><div id="" class="nav_ bd bg"><div class="b_h"><span class="f_l">JOB频道</span><span class="f_r">更多&gt;&gt;</span></div><div class="b_m m_l5 f_l"><img src="__PUBLIC__/images/building.jpg" width="300" /><!--<table width="100%" border="0"><?php if(is_array($artclelist)): $i = 0; $__LIST__ = $artclelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$art): $mod = ($i % 2 );++$i;?><tr><td width="75%"><li><?php echo (msubstr($art["title"],0,12)); echo ($art["is_new"]); ?></li></td><td width="25%" align="right"><span class="f_r"><?php echo (date("Y-m-d",$art["dateline"])); ?></span></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table>--></div></div><div id="" class="m_l5 bd bg nav_"><div class="b_h"><span class="f_l">培训专区</span><a href="__APP__/train"><span class="f_r">更多&gt;&gt;</span></a></div><div class="b_m m_l5 f_l"><table width="100%" border="0"><?php if(is_array($tra_list)): $i = 0; $__LIST__ = $tra_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tra): $mod = ($i % 2 );++$i;?><tr><td width="75%"><a href="__APP__/train/content?aid=<?php echo ($tra["aid"]); ?>" title="<?php echo ($tra["title"]); ?>"><li><?php echo (msubstr($tra["title"],0,12)); echo ($tra["is_new"]); ?></li></a></td><td width="25%" align="right"><span class="f_r"><?php echo (date("Y-m-d",$tra["dateline"])); ?></span></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table></div></div></div><div class="list_ f_l m_t5"><div id="" class="bd bg nav_"><div class="b_h"><span class="f_l">行业资讯</span><a href="__APP__/info"><span class="f_r">更多&gt;&gt;</span></a></div><div class="b_m m_l5 f_l"><table width="100%" border="0"><?php if(is_array($trade_list)): $i = 0; $__LIST__ = $trade_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$trade): $mod = ($i % 2 );++$i;?><tr><td width="75%"><a href="__APP__/info/content?aid=<?php echo ($trade["aid"]); ?>" title="<?php echo ($trade["title"]); ?>"><li><?php echo (msubstr($trade["title"],0,12)); echo ($trade["is_new"]); ?></li></a></td><td width="25%" align="right"><span class="f_r"><?php echo (date("Y-m-d",$trade["dateline"])); ?></span></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table></div></div><div id="" class="m_l5 bd bg nav_"><div class="b_h"><span class="f_l">评选投票</span><a href="__APP__/info"><span class="f_r">更多&gt;&gt;</span></a></div><div class="b_m m_l5 f_l"><table width="100%" border="0"><?php if(is_array($vote_list)): $i = 0; $__LIST__ = $vote_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vote): $mod = ($i % 2 );++$i;?><tr><td width="75%"><a href="__APP__/list/content?aid=<?php echo ($vote["aid"]); ?>" title="<?php echo ($vote["title"]); ?>"><li><?php echo (msubstr($vote["title"],0,12)); echo ($vote["is_new"]); ?></li></a></td><td width="25%" align="right"><span class="f_r"><?php echo (date("Y-m-d",$vote["dateline"])); ?></span></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table></div></div></div></div><div id="m_r"><div id="notice" class="r_b bd f_r"><div class="b_h"><span class="f_l"><img src="__PUBLIC__/images/ann.gif" />公告</span><a href="__APP__/index/notices"><span class="f_r">更多</span></a></div><div class="b_m m_l5"><marquee direction=up height=160 width=234 id=m onmouseout=m.start() onMouseOver=m.stop() scrollamount=1 align="center"><h4><?php echo ($noticelist[0]['name']); ?></h4><?php echo ($noticelist[0]['description']); ?></marquee></div></div><div id="" class="r_b m_t5 img_link t-a-c f_r"><a href="<?php echo ($advdatas[0]['link']); ?>" target="_blank"><img src="__ROOT__/<?php echo ($advdatas[0]['path']); ?>" /></a></div><div id="video" class="r_b m_t5 bd f_r"><div class="b_h"><span class="f_l">视频</span><a href="__APP__/index/videos"><span class="f_r">更多</span></a></div><div class="b_m m_l5"><embed src="<?php echo ($videodata['path']); ?>" quality="high" width="225" height="180" align="middle" allowscriptaccess="always" type="application/x-shockwave-flash" /></embed /></div></div><div id="" class="r_b m_t5 img_link t-a-c f_r"><a href="<?php echo ($advdatas[1]['link']); ?>" target="_blank"><img src="__ROOT__/<?php echo ($advdatas[1]['path']); ?>" /></a></div><div id="" class="r_b m_t5 img_link t-a-c f_r"><a href="<?php echo ($advdatas[2]['link']); ?>" target="_blank"><img src="__ROOT__/<?php echo ($advdatas[2]['path']); ?>" /></a></div><div id="" class="r_b m_t5 img_link t-a-c f_r"><a href="<?php echo ($advdatas[3]['link']); ?>" target="_blank"><img src="__ROOT__/<?php echo ($advdatas[3]['path']); ?>" /></a></div></div><div id="roll"><DIV class="rollphotos"><DIV class=FixTitle><font class="f_l m_l5">图片展示</font></DIV><DIV class=blk_29><DIV class=LeftBotton id=LeftArr></DIV><DIV class=Cont id=ISL_Cont_1><!-- 图片列表 begin --><?php if(is_array($info_img)): $i = 0; $__LIST__ = $info_img;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><DIV class=box><A class=imgBorder href="__APP__/info/content?aid=<?php echo ($vo['aid']); ?>" target=_blank><IMG height=90 alt="<?php echo ($vo['title']); ?>" src="__ROOT__/<?php echo ($vo['pic']); ?>" width=120 
border=0></A><P><A href="__APP__/info/content?aid=<?php echo ($vo['aid']); ?>" 
target=_blank title="<?php echo ($vo['title']); ?>"><?php echo (msubstr($vo['title'],0,5)); ?></A></P></DIV><?php endforeach; endif; else: echo "" ;endif; if(is_array($act_img)): $i = 0; $__LIST__ = $act_img;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><DIV class=box><A class=imgBorder href="__APP__/active/content?aid=<?php echo ($vo1['aid']); ?>" target=_blank><IMG height=90 alt="<?php echo ($vo1['title']); ?>" src="__ROOT__/<?php echo ($vo1['pic']); ?>" width=120 
border=0></A><P><A href="__APP__/active/content?aid=<?php echo ($vo1['aid']); ?>" 
target=_blank title="<?php echo ($vo1['title']); ?>"><?php echo (msubstr($vo1['title'],0,5)); ?></A></P></DIV><?php endforeach; endif; else: echo "" ;endif; if(is_array($tra_img)): $i = 0; $__LIST__ = $tra_img;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?><DIV class=box><A class=imgBorder href="__APP__/train/content?aid=<?php echo ($vo2['aid']); ?>" target=_blank><IMG height=90 alt="<?php echo ($vo2['title']); ?>" src="__ROOT__/<?php echo ($vo2['pic']); ?>" width=120 
border=0></A><P><A href="__APP__/train/content?aid=<?php echo ($vo2['aid']); ?>" 
target=_blank title="<?php echo ($vo2['title']); ?>"><?php echo (msubstr($vo2['title'],0,5)); ?></A></P></DIV><?php endforeach; endif; else: echo "" ;endif; ?><!-- 图片列表 end --></DIV><DIV class=RightBotton id=RightArr></DIV></DIV><SCRIPT language=javascript type=text/javascript><!--//--><![CDATA[//><!--
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