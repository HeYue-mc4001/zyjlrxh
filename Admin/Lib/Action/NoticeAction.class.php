<?php
/**
 * 后台控制器文件
 * @author hansing
 * 奖项类型管理
 * 20130925
**/
session_start();
class NoticeAction extends Action
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
    	    	$where.=" AND (name like('%".$key."%'))";
    		}else{
    			$where.="(name like('%".$key."%'))";	
    		}
    	}
		$notice = M("Notice");
    	$_SESSION['where']=$where;
    	$count=$notice->where($where)->count();
    	$p=new Page($count, 20);
    	
    	$noticelist=$notice->where($where)->order("id desc")->limit($p->firstRow.','.$p->listRows)->select();
    	$page = $p->show ();
	    
		$this->assign( "page", $page);
    	$this->assign('noticelist',$noticelist);
	    $this->display();
    }
    
    /**
     +----------------------------------------------------------
     * 添加奖项类型
     +----------------------------------------------------------
    **/
    public function add()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
    	$this->assign('act','添加');
    	$this->assign('action','addaction');
        $this->display();
    }
	
	/**
     +----------------------------------------------------------
     * 编辑奖项类型
     +----------------------------------------------------------
    **/
    public function edit()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$notice = M("notice");
		$id=$_GET['id'];
    	$noticeList=$notice->where("id=$id")->find();
    	
		$this->assign('notice',$noticeList);
    	$this->assign('act','编辑');
    	$this->assign('action','editaction');
        $this->display('add');
	}
   
	/**
     +----------------------------------------------------------
     * 添加文章数据处理
     +----------------------------------------------------------
    */
    public function addaction()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_add");
		
		$notice = M("notice");//实例化模型类
		$validate = array(
			array('name','require','标题不能为空！',1),
		);
		$notice->setProperty('_validate',$validate);
		if($notice->Create()){
			$notice->add();
			$this->redirect('Notice/index');
		}else{
			$err=$notice->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
    }
    
	/**
     +----------------------------------------------------------
     * 编辑文章数据处理
     +----------------------------------------------------------
    **/
    public function editaction()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_edit");
    	
		$notice = M("notice");
		$validate=array(
			array("name",'require',"标题不能为空！",1),
		);
		$notice->setProperty('_validate',$validate);
		if($notice->Create()){
			$notice->save();
			$this->redirect('Notice/index');
		}else{
			$err=$notice->getError();
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
		
		$TheObj =M('notice');//实例化模型类
		$id=$_REQUEST['id'];
		$ids=$_REQUEST['ids'];
		if($id){
			$TheObj->delete($id);
		}
		if($ids){
			$TheObj->where("id in($ids)")->delete();
		}
		$this->redirect('Notice/index');
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