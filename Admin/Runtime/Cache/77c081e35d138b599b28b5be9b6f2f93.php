<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>杂志内容管理</title><style type="text/css">body{
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
}
a
{
color:blue;
text-decoration:none;
}
tr .relow
{
color:#03C;
}
tr .relow a
{
color:#03C;
}
tr .relow a:hover
{
color:red;
}
</style><script type="text/javascript"><!--
function doAction(a,id){
	if(a=='deleteAll'){
		if(confirm('请确认是否删除所选！')){
			ids=getCheckedIds('checkbox');
			window.location.href="__APP__/Magazine/delete/ids/"+ids;
		}
	}
	if(a=='delete'){
		if(confirm('请确认是否删除！')){
			window.location.href="__APP__/Magazine/delete/id/"+id;
		}
	}
}


//全选/取消
function checkAll(o,checkBoxName){
	var oc = document.getElementsByName(checkBoxName);
	for(var i=0; i<oc.length; i++) {
		if(o.checked){
			oc[i].checked=true;	
		}else{
			oc[i].checked=false;	
		}
	}
	checkDeleteStatus(checkBoxName)
}

//检查有选择的项，如果有删除按钮可操作
function checkDeleteStatus(checkBoxName){
	var oc = document.getElementsByName(checkBoxName);
	for(var i=0; i<oc.length; i++) {
		if(oc[i].checked){
			document.getElementById('DeleteCheckboxButton').disabled=false;
			return;
		}
	}
	document.getElementById('DeleteCheckboxButton').disabled=true;
}

//获取所有被选中项的ID组成字符串
function getCheckedIds(checkBoxName){
	var oc = document.getElementsByName(checkBoxName);
	var CheckedIds = "";
	for(var i=0; i<oc.length; i++) {
		if(oc[i].checked){
			if(CheckedIds==''){
				CheckedIds = oc[i].value;	
			}else{
				CheckedIds +=","+oc[i].value;	
			}
			
		}
	}
	return CheckedIds;
}
--></script></head><body><table width="100%" border="0" cellpadding="0" cellspacing="0" style="word-break:break-all;word-wrap:break-word;table-layout: auto;"><tr><td width="17" height="29" valign="top" background="__ROOT__/Public/admin/images/mail_leftbg.gif"><img src="__ROOT__/Public/admin/images/left-top-right.gif" width="17" height="29" /></td><td width="" height="29" valign="top" background="__ROOT__/Public/admin/images/content-bg.gif"><table width="100%" height="29" border="0" cellpadding="0" cellspacing="0" class="left_topbg" id="table2"><tr><td height="29"><div class="titlebt">杂志管理</div></td></tr></table></td><td width="16" valign="top" background="__ROOT__/Public/admin/images/mail_rightbg.gif"><img src="__ROOT__/Public/admin/images/nav-right-bg.gif" width="16" height="29" /></td></tr><tr><td height="71" valign="middle" background="__ROOT__/Public/admin/images/mail_leftbg.gif">&nbsp;</td><td valign="top" bgcolor="#F7F8F9"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" ><tr><td width="120" height="40" align="center" valign="middle"><button onClick="location.href='__APP__/Magazine/add'">添加杂志图片</button></td><td >&nbsp;</td><td width="80" valign="middle">关键字:</td><form method="post" action="__APP__/Slide/index"><td width="220" valign="middle"><input name='key' type="text" value="" style="width:200px" /></td><td width="200" valign="middle"><select name="aid" style="width:200px"><option value="0">--所有杂志--</option><?php if(is_array($artlist)): $i = 0; $__LIST__ = $artlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a_l): $mod = ($i % 2 );++$i;?><option value="<?php echo ($a_l['aid']); ?>"><?php echo ($a_l['title']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></td><td width="50" valign="middle"><input type="submit" value="搜索" /></td></form></tr></table><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_list" style="word-break:break-all;word-wrap:break-word;table-layout:fixed;"><tr><th width="40" align="center" valign="middle"><input type="checkbox" name="checkbox11" value="checkbox" onClick="checkAll(this,'checkbox')"></th><th width="40" align="center" valign="middle">ID</th><th width="" height="35" align="center" valign="middle">图片</th><th width="150" height="35" align="center" valign="middle">图片标题</th><th width="150" align="center" valign="middle">所属杂志</th><th width="100" align="center" valign="middle">所属板块</th><th width="50" align="center" valign="middle">排序</th><th width="50" height="35" align="center" valign="middle">操作</th></tr><?php if(is_array($magazinelist)): $i = 0; $__LIST__ = $magazinelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr onMouseOver="this.className='relow'" onMouseOut="this.className='row'" class="row"><td align="center" valign="middle" ><input type="checkbox" name="checkbox" value="<?php echo ($vo['id']); ?>" onClick="checkDeleteStatus('checkbox')"></td><td align="center" valign="middle" ><?php echo ($vo['id']); ?></td><td height="35" align="center" valign="middle" ><img src="__PUBLIC__/Uploads/maga/<?php echo ($vo['path']); ?>" height="120" /></td><td height="35" align="center" valign="middle" ><?php echo ($vo['title']); ?>&nbsp;</td><td align="center" valign="middle"><?php echo ($vo['a_name']); ?>&nbsp;</td><td align="center" valign="middle"><?php echo ($vo['type']); ?>&nbsp;</td><td align="center" valign="middle"><?php echo ($vo['seq']); ?>&nbsp;</td><td height="35" align="center" valign="middle"><a href="__APP__/Magazine/edit/id/<?php echo ($vo['id']); ?>/" style="border:none;"><img src="__ROOT__/Public/admin/images/ico_edit.gif" width="12" height="12" alt="编辑" title="编辑" style="border:none;" /></a> | <img src="__ROOT__/Public/admin/images/ico_del.gif" width="12" height="12" alt="删除" title="删除" onClick="doAction('delete',<?php echo ($vo['id']); ?>)" style="cursor:pointer"></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table><table width="100%" border="0" cellpadding="0" cellspacing="0" ><tr><td height="35" style="text-align:left; padding-left:10px" width="100px"><div style=" float:left"><input type="button" id="DeleteCheckboxButton" value="删 除" disabled="disabled" onClick="doAction('deleteAll')"></div></td><td style="text-align:center; "><div><?php echo ($page); ?></div></td></tr><tr><td height="3" colspan="2"></td></tr></table></td><td background="__ROOT__/Public/admin/images/mail_rightbg.gif">&nbsp;</td></tr><tr><td valign="middle" background="__ROOT__/Public/admin/images/mail_leftbg.gif"><img src="__ROOT__/Public/admin/images/buttom_left2.gif" width="17" height="17" /></td><td height="17" valign="top" background="__ROOT__/Public/admin/images/buttom_bgs.gif"><img src="__ROOT__/Public/admin/images/buttom_bgs.gif" width="17" height="17" /></td><td background="__ROOT__/Public/admin/images/mail_rightbg.gif"><img src="__ROOT__/Public/admin/images/buttom_right2.gif" width="16" height="17" /></td></tr></table></body></html>