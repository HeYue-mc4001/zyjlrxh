<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>杂志订单管理</title><style type="text/css">body{
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
			window.location.href="__APP__/Article/delete/ids/"+ids;
		}
	}
	if(a=='delete'){
		if(confirm('请确认是否删除！')){
			window.location.href="__APP__/Article/delete/id/"+id;
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
--></script></head><body><table width="100%" border="0" cellpadding="0" cellspacing="0" style="word-break:break-all;word-wrap:break-word;table-layout: auto;"><tr><td width="17" height="29" valign="top" background="__ROOT__/Public/admin/images/mail_leftbg.gif"><img src="__ROOT__/Public/admin/images/left-top-right.gif" width="17" height="29" /></td><td width="" height="29" valign="top" background="__ROOT__/Public/admin/images/content-bg.gif"><table width="100%" height="29" border="0" cellpadding="0" cellspacing="0" class="left_topbg" id="table2"><tr><td height="29"><div class="titlebt">杂志订单</div></td></tr></table></td><td width="16" valign="top" background="__ROOT__/Public/admin/images/mail_rightbg.gif"><img src="__ROOT__/Public/admin/images/nav-right-bg.gif" width="16" height="29" /></td></tr><tr><td height="71" valign="middle" background="__ROOT__/Public/admin/images/mail_leftbg.gif">&nbsp;</td><td valign="top" bgcolor="#F7F8F9"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" ><tr><!--<td width="80" height="40" align="center" valign="middle"><button onClick="location.href='__APP__/Article/add'">添加文章</button></td>--><td >&nbsp;</td><td width="80" valign="middle">关键字:</td><form method="post" action="__APP__/Order/index"><td width="220" valign="middle"><input name='key' type="text" value="" style="width:200px" /></td><!--<td width="200" valign="middle"><select name="cateid" style="width:200px"><option value="0">--所有栏目--</option><?php $cate->getCategoryOption(); ?></select></td>--><td width="50" valign="middle"><input type="submit" value="搜索" /></td></form></tr></table><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_list" style="word-break:break-all;word-wrap:break-word;table-layout:fixed;"><tr><!--<th width="40" align="center" valign="middle"><input type="checkbox" name="checkbox11" value="checkbox" onClick="checkAll(this,'checkbox')"></th>--><th width="40" align="center" valign="middle">ID</th><th width="80" height="35" align="center" valign="middle">联系人</th><th width="120" align="center" valign="middle">电话</th><th width="140" align="center" valign="middle">订单时间</th><th width="200" align="center" valign="middle">地址</th><th width="" align="center" valign="middle">订阅详情</th><!--<th width="150" height="35" align="center" valign="middle">操作</th>--></tr><?php if(is_array($orderlist)): $i = 0; $__LIST__ = $orderlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr onMouseOver="this.className='relow'" onMouseOut="this.className='row'" class="row"><!--<td align="center" valign="middle" ><input type="checkbox" name="checkbox" value="<?php echo ($vo['aid']); ?>" onClick="checkDeleteStatus('checkbox')"></td>--><td align="center" valign="middle" ><?php echo ($vo['id']); ?></td><td height="35" align="center" valign="middle" ><?php echo ($vo['name']); ?>&nbsp;</td><td align="center" valign="middle"><?php echo ($vo['phone']); ?></td><td align="center" valign="middle"><?php echo (date("Y-m-d H:i",$vo['datetime'])); ?></td><td align="center" valign="middle"><?php echo ($vo['address']); ?>&nbsp;</td><td align="center" valign="middle"><?php if(is_array($vo['list'])): $i = 0; $__LIST__ = $vo['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i; echo ($i); ?>、<?php echo ($sub['mname']); ?>&nbsp*&nbsp;<?php echo ($sub['num']); ?><br /><?php endforeach; endif; else: echo "" ;endif; ?></td><!--<td height="35" align="center" valign="middle"><a href="__APP__/Article/edit/aid/<?php echo ($vo['aid']); ?>" style="border:none;"><img src="__ROOT__/Public/admin/images/ico_edit.gif" width="12" height="12" alt="编辑" title="编辑" style="border:none;" /></a> | <img src="__ROOT__/Public/admin/images/ico_del.gif" width="12" height="12" alt="删除" title="删除" onClick="doAction('delete',<?php echo ($vo['aid']); ?>)" style="cursor:pointer"></td>--></tr><?php endforeach; endif; else: echo "" ;endif; ?></table><table width="100%" border="0" cellpadding="0" cellspacing="0" ><tr><td height="35" style="text-align:left; padding-left:10px" width="100px"><!--<div style=" float:left"><input type="button" id="DeleteCheckboxButton" value="删 除" disabled="disabled" onClick="doAction('deleteAll')"></div>--></td><td style="text-align:center; "><div><?php echo ($page); ?></div></td></tr><tr><td height="3" colspan="2"></td></tr></table></td><td background="__ROOT__/Public/admin/images/mail_rightbg.gif">&nbsp;</td></tr><tr><td valign="middle" background="__ROOT__/Public/admin/images/mail_leftbg.gif"><img src="__ROOT__/Public/admin/images/buttom_left2.gif" width="17" height="17" /></td><td height="17" valign="top" background="__ROOT__/Public/admin/images/buttom_bgs.gif"><img src="__ROOT__/Public/admin/images/buttom_bgs.gif" width="17" height="17" /></td><td background="__ROOT__/Public/admin/images/mail_rightbg.gif"><img src="__ROOT__/Public/admin/images/buttom_right2.gif" width="16" height="17" /></td></tr></table></body></html>