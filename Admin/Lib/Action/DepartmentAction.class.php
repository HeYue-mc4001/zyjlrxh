<?php
/**
 * 后台控制器文件
 * @author hansing
 * 部门管理
 * 20130408
**/
session_start();
class DepartmentAction extends Action
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
    	$dep =new Model('department');
		$count=$dep->order('seq,did asc')->count();
    	$p=new Page($count, 20);
    	$dep_list=$dep->limit($p->firstRow.','.$p->listRows)->order('seq,did asc')->select(); 
    	$user=new Model('user');
		
		$this->assign('dep_list',$dep_list);
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('user',$user);
		$this->display();
	}

	/**
     +----------------------------------------------------------
     * 添加部门
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
     * 编辑部门操作
     +----------------------------------------------------------
    */
    public function edit()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$department=M('department');
    	$did=$_REQUEST['did'];
    	$dep=$department->where("did=$did")->find();
    	
		$this->assign('dep',$dep);
    	$this->assign('act','编辑');
    	$this->assign('action','editaction');
        $this->display('add');
    }
    
	/**
     +----------------------------------------------------------
     * 添加部门数据处理
     +----------------------------------------------------------
    **/
    public function addaction()
    {
		global $privilege;
		$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_add");
      
		$TheObj =M('department');//实例化模型类
		$validate = array(
			array('name','require','部门名称不能为空！',1),
			array('name','','部门名称已存在！',0,'unique',1),
		);
		$TheObj->setProperty('_validate',$validate);
		if($TheObj->Create()){
			$TheObj->add();
			$this->redirect('Department/index');
		}else{
			$err=$TheObj->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
	}

    /**
	 +----------------------------------------------------------
     * 编辑部门数据处理
     +----------------------------------------------------------
    **/
    public function editaction()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_edit");
      
		$TheObj =M('department');//实例化模型类
		$validate=array(
			array('name','',"部门名称已存在！",0,'unique',2),
			array("name",'require',"部门名称不能为空！",1),
		);
		$TheObj->setProperty('_validate',$validate);
		if($TheObj->Create()){
			$TheObj->save();
			$this->redirect('Department/index');
		}else{
			$err=$TheObj->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}	  
    }
    
	/**
     +----------------------------------------------------------
     * 删除用户数据处理
     +----------------------------------------------------------
    **/
    public function delete()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$TheObj =M('department');//实例化模型类
		$id=$_REQUEST['id'];
		$ids=$_REQUEST['ids'];
		if($id){
			$TheObj->delete($id);
		}
		if($ids){
			$TheObj->where("did in($ids)")->delete();
		}
	  
		$this->redirect('Department/index');
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