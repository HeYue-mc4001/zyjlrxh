<?php
/**
 * 后台控制器文件
 * @author hansing
 * 会员积分消费项目
 * 20130422
**/
session_start();
class PointsitemAction extends Action
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
		
    	$pointsitem = M('pointsitem');
		$pointsitemlist = $pointsitem->select();
		
		$this->assign("pointsitemlist",$pointsitemlist);
		$this->display();
    }
    
	/**
     +----------------------------------------------------------
     * 添加项目
     +----------------------------------------------------------
    **/
    public function add()
    {
    	global $privilege;
        $privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);	

		$this->assign('action','addaction');
    	$this->assign('act','添加');
        $this->display();
    }
    
	/**
     +----------------------------------------------------------
     * 编辑项目
     +----------------------------------------------------------
    **/
    public function edit()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$pointsitem = M('pointsitem');
    	
		$id = $_REQUEST['id'];
    	$pointsitemdata = $pointsitem->where("id=$id")->find();
	
		$this->assign('pointsitem',$pointsitemdata);
    	$this->assign('act','编辑');
    	$this->assign('action','editaction');
        $this->display('add');
    }
	
    /**
     +----------------------------------------------------------
     * 添加项目数据处理
     +----------------------------------------------------------
    **/
    public function addaction()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_add");
      	
		$TheObj =M('pointsitem');//实例化模型类
		$validate = array(
        	array('name','require','会员组名称不能为空！',1),
			array('value','require','分值不能为空！',1),
			array('discount','number','分值必须为数字！',2),			
      	);
      
		$TheObj->setProperty('_validate',$validate);
		if($TheObj->Create()){
			$TheObj->add();
			$this->redirect('Pointsitem/index');
		}else{
			$err=$TheObj->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
	}
    
	/**
     +----------------------------------------------------------
     * 编辑项目数据处理
     +----------------------------------------------------------
    **/
	public function editaction()
    {
		global $privilege;
		$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_edit");

		$id = $_POST['id'];
		$TheObj =M('pointsitem');//实例化模型类
		$validate = array(
			array('name','require','会员组名称不能为空！',1),
			array('value','require','分值不能为空！',1),
			array('value','number','分值必须为数字！',1),
		);
		$TheObj->setProperty('_validate',$validate);
		if($TheObj->Create()){
			$TheObj->save();
			$this->redirect('Pointsitem/index');
		}else{
			$err=$TheObj->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
	}

    /**
     +----------------------------------------------------------
     * 删除项目
     +----------------------------------------------------------
    **/
    public function delete()
    {
		global $privilege;
		$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);

		$TheObj =D('pointsitem');//实例化模型类
		$TheObj->Create();
		$id=$_REQUEST['id'];
		$ids=$_REQUEST['ids'];
		if($id){
			$TheObj->delete($id);
		}
		if($ids){
			$TheObj->where("id in($ids)")->delete();
		}
		$this->redirect('Pointsitem/index');
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