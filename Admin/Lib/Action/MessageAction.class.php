<?php
/**
 * 后台控制器文件
 * @author hansing
 * 留言管理
 * 20130415
 * 20130416
**/
session_start();
class MessageAction extends Action
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
    	
		$message = M('message');
    	$reply = M('reply');
    	
		import("ORG.Util.Page");
    	$count=$message->order("id desc")->count();
    	$p=new Page($count, 20);
    	
    	$messagelist=$message->order("id desc")->limit($p->firstRow.','.$p->listRows)->select();
    	$page = $p->show ();
	    $this->assign( "page", $page);
    	$this->assign('messagelist',$messagelist);
    	$this->assign('reply',$reply);
	    $this->display();
    }

    /**
     +----------------------------------------------------------
     * 留言详细操作
     +----------------------------------------------------------
    **/
    public function detail()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_index");
    	
		$message=new Model('message');
    	$reply=new Model('reply');
    	$user=M('user');
    	$id=$_GET['id'];
    	$messageone=$message->where("id=$id")->find();
    	if($messageone['is_new']==1){
    		$message->is_new=0;
    		$message->where("id=$id")->save();
		}
		if($messageone['is_checked']==1){
			$messageone['is_checked'] = "已通过审核";
		}else{
			$messageone['is_checked'] = "尚未审核【<a href=\"__URL__/audit/id/".$messageone['id']."\">审核</a>】";
		}
    	$replylist=$reply->where("mid=$id")->order('id desc')->select();
    	$this->assign('replylist',$replylist);
    	$this->assign('message',$messageone);
    	$this->assign('action','addaction');
    	$this->assign('user',$user);
        $this->display();
    }
	
	/**
     +----------------------------------------------------------
     * 新留言检查
     +----------------------------------------------------------
    **/
    public function checkmessage()
    {
    	$message=new Model('message');
    	$count=$message->where("is_new=1")->order("id desc")->count();
    	if($count>0){
    		echo $count;
    	}
    }
    
	/**
     +----------------------------------------------------------
     * 编辑回复操作
     +----------------------------------------------------------
    **/
    public function edit()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$reply=new Model('reply');
    	$id=$_GET['id'];
    	$replylist=$reply->where("id=$id")->find();
    	
		$this->assign('replylist',$replylist);
    	$this->assign('act','编辑');
    	$this->assign('action','editaction');
        $this->display();
    }
    /**
     +----------------------------------------------------------
     * 添加回复数据处理
     +----------------------------------------------------------
    **/
    public function addaction()
    {
    	global $privilege;
		$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_add");

		$reply=new Model('reply');//实例化模型类
		$validate = array(
        	array('content','require','文章内容不能为空！',1),
		);
		$auto = array(
			array('createtime','time',1,'function'),
			array('updatetime','time',1,'function'),
			array('uid',$_SESSION['admin']['id'],1),
		);
		$reply->setProperty('_validate',$validate);
		$reply->setProperty('_auto',$auto);
		if($reply->Create()){
			$reply->add();
			$this->redirect('Message/detail?id='.$_REQUEST['mid']);
		}else{
			$err=$reply->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
    }
	
	/**
    +----------------------------------------------------------
    * 编辑回复数据处理
    +----------------------------------------------------------
    */
    public function editaction()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_edit");
		
		$reply=new Model('reply');//实例化模型类
		$validate = array(
			array('content','require','文章内容不能为空！',1),
		);
		$auto = array(
			array('updatetime','time',2,'function'),
			array('uid',$_SESSION['admin']['id'],1),
		);
		$reply->setProperty('_validate',$validate);
		$reply->setProperty('_auto',$auto);
		if($reply->Create()){
			$reply->save();
			$this->redirect('Message/detail?id='.$_REQUEST['mid']);
		}else{
			$err=$reply->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
	}
	
	/**
     +----------------------------------------------------------
     * 删除留言数据处理
     +----------------------------------------------------------
    **/
    public function delete()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$TheObj = M('message');//实例化模型类
		$reply = M('reply');
		$id=$_REQUEST['id'];
		$ids=$_REQUEST['ids'];
		if($id){
			$TheObj->delete($id);
			$reply->where("mid = $id")->delete();//同时删除关联回复，下同
		}
		if($ids){
			$TheObj->where("id in($ids)")->delete();
			$reply->where("mid in($ids)")->delete();
		}
		$this->redirect('Message/index');
    }
	
	/**
     +----------------------------------------------------------
     * 删除回复数据处理
     +----------------------------------------------------------
    **/
    public function replydelete()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$TheObj =M('reply');//实例化模型类
		$id = $_REQUEST['id'];
		$mid = $_REQUEST['mid'];
		if($id){
			$TheObj->delete($id);
		}
		$this->redirect('Message/detail?id='.$mid);
    }
	
	/**
     +----------------------------------------------------------
     * 留言审核处理
     +----------------------------------------------------------
    **/
    public function audit()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);

		$TheObj =M('message');//实例化模型类
		$id=$_REQUEST['id'];
		if($id){
			$TheObj->id=$id;
	  		$TheObj->is_checked=1;
	  		$TheObj->save();
		}
		$this->redirect('Message/detail?id='.$id);
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