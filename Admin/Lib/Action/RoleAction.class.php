<?php
/**
 * 后台控制器文件
 * @author hansing
 * 用户组管理
 * 20130408
**/
session_start();
class RoleAction extends Action
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
    	$role =new Model('role');
    	$count=$role->order('seq,id asc')->count();
    	$p=new Page($count, 20);
    	$role_list=$role->limit($p->firstRow.','.$p->listRows)->order('seq,id asc')->select(); 
    	$role_user=new Model('role_user');
		$this->assign('role_list',$role_list);
		$page = $p->show ();
		
		$this->assign( "page", $page );
		$this->assign('role_user',$role_user);
		$this->display();
    }

    /**
     +----------------------------------------------------------
     * 添加用户组
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
     * 编辑用户组
     +----------------------------------------------------------
    **/
    public function edit()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$role=M('role');
    	$id=$_REQUEST['id'];
    	$role1=$role->where("id=$id")->find();
    	$privilege=explode(',',$role1['actionlist']);
    	
		$this->assign('role',$role1);
    	$this->assign('privilege',$privilege);
    	$this->assign('act','编辑');
    	$this->assign('action','editaction');
        $this->display('add');
    }

    /**
     +----------------------------------------------------------
     * 添加数据处理
     +----------------------------------------------------------
    **/
    public function addaction()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_add");

		$TheObj =M('role');//实例化模型类
		$validate = array(
			array('name','require','用户组名称不能为空！',1),
			array('name','','用户组名称已存在！',0,'unique',1),
		);
		$TheObj->setProperty('_validate',$validate);
		if($TheObj->Create()){
			$TheObj->actionlist=implode(',', $_POST['actionlist']);
			$TheObj->add();
			$this->redirect('Role/index');
		}else{
			$err=$TheObj->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}	  
    }

    /**
     +----------------------------------------------------------
     * 编辑数据处理
     +----------------------------------------------------------
    **/
    public function editaction()
    {
		global $privilege;
		$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_edit");
		
		$TheObj =M('role');//实例化模型类
		$validate=array(
	      	array('name','',"用户组名称已存在！",0,'unique',2),
	      	array("name",'require',"用户组名称不能为空！",1),
		);
		$TheObj->setProperty('_validate',$validate);
		if($TheObj->Create()){
			$TheObj->actionlist=implode(',',$_POST['actionlist']);
			$TheObj->save();
			$this->redirect('Role/index');
		}else{
			$err=$TheObj->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}  
    }

    /**
     +----------------------------------------------------------
     * 删除数据处理
     +----------------------------------------------------------
    **/
    public function delete()
    {
		global $privilege;
		$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$TheObj =M('role');//实例化模型类
		$TheObj->Create();
		$id=$_REQUEST['id'];
		$ids=$_REQUEST['ids'];
		if($id){
			$TheObj->delete($id);
		}
		if($ids){
			$TheObj->where("id in($ids)")->delete();
		}
	  
		$this->redirect('Role/index');
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