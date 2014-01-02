<?php
/**
 * 后台控制器文件
 * @author hansing
 * 会员积分管理
 * 20130422
 * 20130423
**/
session_start();
class PointsAction extends Action
{
	/**
	 +----------------------------------------------------------
	 * 初始化函数
	 +----------------------------------------------------------
	**/
	public function _initialize()
	{
		header("Content-Type:text/html; charset=UTF-8");

		$admin = $_SESSION['admin'];
		if(empty($admin)){
			$this->redirect('Login/login');
		    exit();
		}
		
		global $privilege;
		$privilege=new PrivilegeModel('role');
    }  
	
    /**
     +----------------------------------------------------------
     * 默认操作
     +----------------------------------------------------------
    **/
    public function index()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		import("ORG.Util.Page");
        $member = M("member");
		$group = M("group");
		$grouplist = $group->select();
		
		$where = "";
        if($_GET['p']){
    		$where = $_SESSION['where'];
    	}else{
    		$where = '';
    		$_SESSION['where'] = '';
    	}
        if($_POST['key']){
    		$key=$_POST['key'];
    		if($where){
    	    	$where.=" AND (nickname like('%".$key."%') or realname like('%".$key."%'))";
    		}else{
    			$where.="(nickname like('%".$key."%') or realname like('%".$key."%'))";	
    		}
    	}
		if($_POST['start']){
    		$t1 = strtotime($_POST['start']);
			if($_POST['end']){
				$t2 = strtotime($_POST['end']) + 86400;
				if($t1 < $t2){
					if($where){
    	    			$where.=" AND (changetime >= $t1 AND changetime <= $t2)";
    				}else{
    					$where.="(changetime >= $t1 AND changetime <= $t2)";	
    				}
				}
			}else{
				if($where){
    	    		$where.=" AND (changetime >= $t1)";
    			}else{
    				$where.="(changetime >= $t1)";	
    			}
			}
    	}else{
			if($_POST['end']){
				$t2 = strtotime($_POST['end']) + 86400;
				if($t2){
					if($where){
    	    			$where.=" AND (changetime <= $t2)";
    				}else{
    					$where.="(changetime <= $t2)";	
    				}
				}
			}
		}
		if($_POST['gid']){
			$gid = $_POST['gid'];
			if($where){
    	    	$where.=" AND (gid = $gid)";
    		}else{
    			$where.="(gid = $gid)";	
    		}
		}
		if($_POST['is_checked']){
			$is_checked = $_POST['is_checked'] - 1;
			if($where){
    	    	$where.=" AND (is_checked = $is_checked)";
    		}else{
    			$where.="(is_checked = $is_checked)";	
    		}
		}
    	$_SESSION['where']=$where;
    	$count=$member->where($where)->count();
    	$p=new Page($count, 20);
    	
    	$memberlist=$member->field('id,nickname,realname,point,lastchange,changetime')->where($where)->limit($p->firstRow.','.$p->listRows)->select();
		$record = M("pointsrecord");
		$pointsitem = M("pointsitem");
		foreach($memberlist as $key=>$value){
			if($value['lastchange'] < 0){
				$itemid = $record->where("mid = ".$value['id'])->getField("itemid");
				$item = $pointsitem->where("id = $itemid")->getField("name");
				$memberlist[$key]['lastchange'] = $memberlist[$key]['lastchange']."-".$item;
				if($value['changetime']){
					$memberlist[$key]['lastchange'] = $memberlist[$key]['lastchange']." @".date("Y-m-d H:i",$value['changetime']);
				}
			}elseif($value['lastchange'] > 0){
				$memberlist[$key]['lastchange'] = $memberlist[$key]['lastchange']."-充值";
				if($value['changetime']){
					$memberlist[$key]['lastchange'] = $memberlist[$key]['lastchange']." @".date("Y-m-d H:i",$value['changetime']);
				}
			}else{
				$memberlist[$key]['lastchange'] = $memberlist[$key]['lastchange']."-暂无记录";
			}
		}
    	$page = $p->show ();
	    
		$this->assign("grouplist",$grouplist);
		$this->assign( "page", $page);
    	$this->assign('memberlist',$memberlist);
	    $this->display();
    }
    
    /**
     +----------------------------------------------------------
     * 会员详细信息
     +----------------------------------------------------------
    **/
    public function detail()
    {
		global $cateoption,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$id = $_GET['id'];
		$member = M("member");
		$group = M("group");
		$memberdata = $member->where("id = $id")->find();
		switch($memberdata['gender']){
			case 1:
				$memberdata['gender'] = '男';
				break;
			case 2:
				$memberdata['gender'] = '女';
				break;
			default:
				$memberdata['gender'] = '保密';
		}
		if($memberdata['icon']){
			$memberdata['icon'] = '<img src="__ROOT__/'.$memberdata['icon'].'" />';
		}else{
			$memberdata['icon'] = "TA还没有上传过头像！";
		}
		if($memberdata['is_checked']==1){
			$memberdata['is_checked'] = "审核通过";
		}else{
			$memberdata['is_checked'] = "尚未审核【<a href=\"__URL__/audit/id/".$memberdata['id']."\">审核</a>】";
		}
		$memberdata['gid'] = $group->where("id = ".$memberdata['gid'])->getField("name");
		
    	$this->assign('member',$memberdata);
        $this->display();
    }
	
	/**
     +----------------------------------------------------------
     * 会员审核处理
     +----------------------------------------------------------
    **/
    public function audit()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);

		$TheObj = M('member');//实例化模型类
		$id = $_GET['id'];
		$ids = $_REQUEST['ids'];
		if($id){
			$TheObj->id=$id;
	  		$TheObj->is_checked=1;
	  		$TheObj->save();
			$this->redirect('Member/detail?id='.$id);
		}
		if($ids){
	  		$TheObj->is_checked=1;
	  		$TheObj->where("id in($ids)")->save();
			$this->redirect('Member/index');
		}
    }
	
	/**
     +----------------------------------------------------------
     * 添加会员
     +----------------------------------------------------------
    **/
    public function add()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$group = M("group");
		$grouplist = $group->select();
		
		$memberdata['checked0'] = 'checked="checked"';
		
		$this->assign('member',$memberdata);
		$this->assign('grouplist',$grouplist);
    	$this->assign('act','添加');
    	$this->assign('action','addaction');
        $this->display();
    }
	
    /**
     +----------------------------------------------------------
     * 新增会员信息数据处理
     +----------------------------------------------------------
    **/
    public function addaction()
	{
		if($_FILES['postpic']['name']){
			import('ORG.Net.UploadFile');
        	$upload = new UploadFile();
        	//设置上传文件大小
        	$upload->maxSize = 3292200;
        	//设置上传文件类型
        	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        	$upload->savePath = 'Public/Member/icon/'; // 设置附件上传目录
			$upload->saveRule = time;
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
            	$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
            	$info = $upload->getUploadFileInfo(); 
			} 
		}
		$member = M("Member");//实例化模型类
		$validate = array(
			array('nickname','require','昵称不能为空！',1),
			array('nickname','','帐号名称已经存在！',0,'unique',1),
			array('realname','require','昵称不能为空！',1),
	    	array('password1','require','密码不能为空！',1),
	    	array('password1','password2','密码不一致！',3,'confirm'),
	    	array('email','email','邮箱格式不符合要求。',1),
		);
		$auto = array(
			array('regdate','time',1,'function'),
			array('lastlogin','time',1,'function'),
			array('lastip','get_client_ip',1,'function'),
		);
		$member->setProperty('_validate',$validate);
		$member->setProperty('_auto',$auto);
		if($member->Create()){
			if($info[0]["savename"]){
				$member->icon=$info[0]["savepath"].$info[0]["savename"];
			}
			$member->password = md5($_POST['password1']);
			$member->add();
			$this->redirect('Member/index');
		}else{
			$err=$member->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
    }
    
	/**
     +----------------------------------------------------------
     * 编辑会员数据
     +----------------------------------------------------------
    **/
    public function edit()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$id=$_GET['id'];
    	$member = M("member");
		$group = M("group");
		
		$memberdata = $member->where("id = $id")->find();
		switch($memberdata['gender']){
			case 1:
				$memberdata['checked1'] = 'checked="checked"';
				break;
			case 2:
				$memberdata['checked2'] = 'checked="checked"';
				break;
			default:
				$memberdata['checked0'] = 'checked="checked"';
		}
		$grouplist = $group->select();
		foreach($grouplist as $key=>$value){
			if($value['id']==$memberdata['gid']){
				$grouplist[$key]["selected"] = 'selected="selected"';
			}
		}
    	
		$this->assign('member',$memberdata);
    	$this->assign('grouplist',$grouplist);
    	$this->assign('act','编辑');
    	$this->assign('action','editaction');
        $this->display('add');
	}

	/**
     +----------------------------------------------------------
     * 编辑会员数据处理
     +----------------------------------------------------------
    **/
    public function editaction()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_edit");
    	
		if($_FILES['postpic']['name']){
			import('ORG.Net.UploadFile');
        	$upload = new UploadFile();
        	//设置上传文件大小
        	$upload->maxSize = 3292200;
        	//设置上传文件类型
        	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        	$upload->savePath = 'Public/Member/icon/'; // 设置附件上传目录
			$upload->saveRule = time;
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
            	$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
            	$info = $upload->getUploadFileInfo(); 
			} 
		}
		$member = M("Member");//实例化模型类
		$validate = array(
			array('nickname','require','昵称不能为空！',1),
			array('nickname','','帐号名称已经存在！',0,'unique',1),
			array('realname','require','昵称不能为空！',1),
	    	array('password1','password2','密码不一致！',3,'confirm'),
	    	array('email','email','邮箱格式不符合要求。',1),
		);
		$member->setProperty('_validate',$validate);
		if($member->Create()){
			if($info[0]["savename"]){
				$member->icon=$info[0]["savepath"].$info[0]["savename"];
			}
			if($_POST['password1']){
				$member->password = md5($_POST['password1']);
			}
			$member->save();
			$this->redirect('Member/detail?id='.$_POST['id']);
		}else{
			$err=$member->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
	}
    
	/**
     +----------------------------------------------------------
     * 删除文章数据处理
     +----------------------------------------------------------
    **/
    public function delete()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$TheObj =M('member');//实例化模型类
		$id=$_REQUEST['id'];
		$ids=$_REQUEST['ids'];
		if($id){
			$TheObj->delete($id);
		}
		if($ids){
			$TheObj->where("id in($ids)")->delete();
		}
		$this->redirect('Member/index');
    }
    
	/**
     +----------------------------------------------------------
     * 探针模式
     +----------------------------------------------------------
    **/
    public function checkEnv()
    {
        load('pointer',THINK_PATH.'/Tpl/Autoindex');//载入探针函数
        $env_table = check_env();//根据当前函数获取当前环境
        echo $env_table;
    }
}
?>