<?php
/**
 * 后台控制器文件
 * @author hansing
 * 留言管理
 * 20130415
 * 20130416
**/
session_start();
class OrganizationAction extends Action
{
	/**
	 +----------------------------------------------------------
	 * 初始化函数
	 +----------------------------------------------------------
	**/
	public function _initialize()
	{
		header("Content-Type:text/html; charset=UTF-8");
	
		$admin=$_SESSION['admin'];
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
        $where="";
        if($_GET['p']){
    		$where=$_SESSION['where'];
    	}else{
    		$where='';
    		$_SESSION['where']='';
    	}
        if($_POST['key']){
    		$key=$_POST['key'];
    		if($where){
    	    	$where.=" AND (loginname like('%".$key."%') or realname like('%".$key."%'))";
    		}else{
    			$where.="(loginname like('%".$key."%') or realname like('%".$key."%'))";	
    		}
    	}
		$Organization = M("Organization");
    	$_SESSION['where'] = $where;
    	$count=$Organization->where($where)->count();
    	$p=new Page($count, 20);
    	
    	$orglist=$Organization->where($where)->order("id desc")->limit($p->firstRow.','.$p->listRows)->select();
    	$page = $p->show ();
		foreach($orglist as $k=>$v){
			if($v['status']==0){
				$orglist[$k]['status'] = "未审核";
			}else{
				$orglist[$k]['status'] = "已审核";
			}
		}
	    
		$this->assign( "page", $page);
    	$this->assign('orglist',$orglist);
	    $this->display();
    }

    /**
    +----------------------------------------------------------
    * 会员详细资料和审核
    +----------------------------------------------------------
    */
    public function detail()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$Organization=M('Organization');
    	$id = $_REQUEST['id'];
    	$org = $Organization->where("id=$id")->find();
		if($org['status']==0){
			$org['audit'] = "尚未审核&nbsp;&nbsp;【<a href=\"__URL__/audit/id/".$org['id']."\">通过审核</a>】";
		}else{
			$org['audit'] = "已经审核";
		}
		if($org['ecp']==1){
			$org['check'] = "checked";
		}
		
		$this->assign('org',$org);
        $this->display();
    }
	
	/**
    +----------------------------------------------------------
    * 编辑会员数据处理
    +----------------------------------------------------------
    */
    public function editaction()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_index");
		
		if($_FILES['postpic']['name']){
			import('ORG.Net.UploadFile');
        	$upload = new UploadFile();
        	//设置上传文件大小
        	$upload->maxSize = 3292200;
        	//设置上传文件类型
        	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        	$upload->savePath = 'Public/member/icon/'; // 设置附件上传目录
			$upload->saveRule = time;
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
            	$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
            	$info = $upload->getUploadFileInfo(); 
			} 
		}
		$member = M("Organization");//实例化模型类
		$validate = array(
			array('loginname','require','登录账号不能为空！',1),
			array('loginname','','帐号名称已经存在！',0,'unique',1),
			array('realname','require','企业名称不能为空！',1),
	    	array('password','password1','密码不一致！',3,'confirm'),
	    	array('linkemail','email','联系人邮箱格式不符合要求。',1),
		);
		$member->setProperty('_validate',$validate);
		if($member->Create()){
			if($info[0]["savename"]){
				$member->icon=$info[0]["savepath"].$info[0]["savename"];
			}
			if($_POST['password']){
				$member->loginpwd = md5($_POST['password']);
			}
			$member->save();
			$this->redirect('Organization/detail?id='.$_POST['id']);
		}else{
			$err=$member->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
	}
	
	/**
     +----------------------------------------------------------
     * 删除会员数据处理
     +----------------------------------------------------------
    **/
    public function delete()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$TheObj = M('Organization');//实例化模型类
		$id=$_REQUEST['id'];
		$ids=$_REQUEST['ids'];
		if($id){
			$TheObj->delete($id);
		}
		if($ids){
			$TheObj->where("id in($ids)")->delete();
		}
		$this->redirect('Organization/index');
    }
	
	/**
     +----------------------------------------------------------
     * 会员审核处理
     +----------------------------------------------------------
    **/
    public function audit()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_index");

		$TheObj =M('Organization');//实例化模型类
		$id=$_REQUEST['id'];
		if($id){
			$TheObj->id=$id;
	  		$TheObj->status=1;
	  		$TheObj->save();
		}
		$this->redirect('Organization/detail?id='.$id);
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