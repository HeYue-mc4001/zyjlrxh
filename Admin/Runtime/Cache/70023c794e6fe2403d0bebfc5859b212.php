<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title><?php echo ($act); ?>杂志图片</title><style type="text/css">body{
padding:0px;
}
.titlebt {
	font-size: 12px;
	line-height: 26px;
	font-weight: bold;
	color: #000000;
	background-image: url(__ROOT__/Public/admin/images/top_bt.jpg);
	background-repeat: no-repeat;
	display: block;
	text-indent: 15px;
	padding-top: 5px;
}
.table_list
{
	border:#000 1px solid;
}

.table_list th
{
 border:#000 1px solid;
 border-top:none;
border-left:none;
}
.table_list td
{
border:#000 1px dotted;
border-top:none;
border-left:none;
padding-left:10px;
}
</style></head><body><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="17" valign="top" background="__ROOT__/Public/admin/images/mail_leftbg.gif"><img src="__ROOT__/Public/admin/images/left-top-right.gif" width="17" height="29" /></td><td valign="top" background="__ROOT__/Public/admin/images/content-bg.gif"><table width="760" height="31" border="0" cellpadding="0" cellspacing="0" class="left_topbg" id="table2"><tr><td height="31"><div class="titlebt"><?php echo ($act); ?>杂志图片</div></td></tr></table></td><td width="16" valign="top" background="__ROOT__/Public/admin/images/mail_rightbg.gif"><img src="__ROOT__/Public/admin/images/nav-right-bg.gif" width="16" height="29" /></td></tr><tr><td valign="middle" background="__ROOT__/Public/admin/images/mail_leftbg.gif">&nbsp;</td><td valign="top" bgcolor="#F7F8F9"><form action="__APP__/Magazine/<?php echo ($action); ?>" method="post" name="addart" enctype="multipart/form-data" ><input type="hidden" name="act" value="$act"><table width="100%" border="0" align="center" cellpadding="0"
	cellspacing="0"><tr><td width="4%" height="30" align="center"></td><td></td></tr></table><table width="100%" border="0" align="center" cellpadding="0"
	cellspacing="0" class="table_list"><tr class="row"><td width="200" align="right"><strong>图片标题：</strong></td><td height="35"><input name="title" type="text" style="width: 200px" value="<?php echo ($magazine['title']); ?>" >&nbsp;<span style="color:red">*</span></td></tr><?php if($magazine['path']){ ?><tr class="row"><td width="200" align="right"><strong>图片：</strong></td><td height="35"><img src="__PUBLIC__/Uploads/maga/<?php echo ($magazine['path']); ?>" width="300" id="nowpost" /></td></tr><?php }?><tr class="row"><td width="200" align="right"><strong>上传图片：</strong></td><td height="35"><input name="postpic" type="file" style="width: 200px"  > &nbsp;&nbsp;&nbsp; <span style="color:red">为空即不修改</span></td></tr><tr class="row"><td width="200" align="right"><strong>所属杂志：</strong></td><td height="35"><select name="aid"><?php if($a_name){ ?><option value="<?php echo ($magazine['aid']); ?>"><?php echo ($a_name); ?></option><?php } if(is_array($artlist)): $i = 0; $__LIST__ = $artlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a_l): $mod = ($i % 2 );++$i;?><option value="<?php echo ($a_l['aid']); ?>"><?php echo ($a_l['title']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></td></tr><tr class="row"><td align="right"><strong>排序：</strong></td><td height="35"><input name="seq" type="text" style="width: 200px" value="<?php echo ($magazine['seq']); ?>">&nbsp;<span style="color:#333">默认为0，数字越小排在越前面</span></td></tr></table><table width="100%" border="0" align="center" cellpadding="0"
	cellspacing="0"><tr><td width=""></td><td height="35"  style="text-align:center;"><input type="hidden" name="id" value="<?php echo ($magazine['id']); ?>" /><input
			type="submit" name="button" id="button" value="提交">&nbsp;&nbsp;&nbsp;&nbsp;
            <input
			type="button" onClick="window.history.go(-1)" value="返回" /></td></tr><tr><td height="3" background="__ROOT__/Public/admin/images/20070907_03.gif"></td></tr></table></form></td><td background="__ROOT__/Public/admin/images/mail_rightbg.gif">&nbsp;</td></tr><tr><td valign="bottom" background="__ROOT__/Public/admin/images/mail_leftbg.gif"><img src="__ROOT__/Public/admin/images/buttom_left2.gif" width="17" height="17" /></td><td background="__ROOT__/Public/admin/images/buttom_bgs.gif"><img src="__ROOT__/Public/admin/images/buttom_bgs.gif" width="17" height="17"></td><td valign="bottom" background="__ROOT__/Public/admin/images/mail_rightbg.gif"><img src="__ROOT__/Public/admin/images/buttom_right2.gif" width="16" height="17" /></td></tr></table></body></html>