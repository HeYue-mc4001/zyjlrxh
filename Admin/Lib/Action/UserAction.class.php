<?php
/**
 * 后台控制器文件
 * @author hansing
 * 用户管理
 * 20130105
 * 20130408
**/
session_start();
class UserAction extends Action {
	
	/**
	 +----------------------------------------------------------
	 * 初始化函数
	 +----------------------------------------------------------
	**/
	public function _initialize()
	{
        header("Content-Type:text/html; charset=utf-8");
		
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
    	$user =new Model('User');
    	$role_user=new Role_userModel();
    	$role=M('role');
    	$where="";
        if($_GET['p']){
			$where=$_SESSION['where'];
    	}else{
			$where='';
			$_SESSION['where']='';
    	}
    	if($_POST['searchrole']>0){
    		$roleId=$_POST['searchrole'];
			$userIdList=$role_user->field('user_id')->where("role_id = $roleId")->select();
			foreach ($userIdList as $v){
				$userId[]=$v['user_id'];
			}
			$ids=implode(',', $userId);
    		if($where==""){    			
    			$where="id in($ids)";
    		}else{
    			$where.=" AND id in($ids)";
    		}	
    	}
        if($_POST['searchdid']>0){
    		$did=$_POST['searchdid'];
    		if($where==""){
    			$where="department_id=$did";
    		}else{
    			$where.=" AND department_id=$did";
    		}	
    	}
    	$_SESSION['where']=$where;
    	$count=$user->where($where)->count();
    	$p=new Page($count, 20);
    	
    	//查询
    	$users_list=$user->where($where)->order('id asc')->limit($p->firstRow.','.$p->listRows)->select(); 
    	$department=M('department');
    	foreach ($users_list as $key => $value){
    		$did=$users_list[$key]['department_id'];
    		$users_list[$key]['depname']=$department->where("did=$did")->find();
    	}
    	$departmentList=$department->select();
    	$roleList=$role->select();
		
		$this->assign('departmentList',$departmentList);
		$this->assign('roleList',$roleList);	
		$this->assign('users_list',$users_list);//根据模板变量赋值
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('role_user',$role_user);
		$this->display();
	}

    /**
     +----------------------------------------------------------
     * 添加用户
     +----------------------------------------------------------
    **/
    public function add()
    {
    	global $privilege;
        $privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$role=M('role');
    	$roleList=$role->select();
    	$department=M('department');
    	$departmentList=$department->select();
    	
    	$this->assign('roleList',$roleList);
    	$this->assign('departmentList',$departmentList);
    	$this->assign('act','添加');
        $this->display();
    }
    /**
     +----------------------------------------------------------
     * 编辑用户
     +----------------------------------------------------------
    **/
    public function edit()
    {
    	global $privilege;
        $privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$role=M('role');
    	$roleList=$role->select();
    	
    	$department=M('department');
    	$departmentList=$department->select();
    	
    	$id=$_GET['id'];
    	$userdb=D('User');
    	$user=$userdb->where("id = $id ")->find();
    	$did=$user['department_id'];
    	$this->assign("ifdepartment$did","checked");
    	$role_user=new Role_userModel();
    	$roleIdList=$role_user->where("user_id=$id")->select();
    	foreach($roleIdList as $va){
    		$rid=$va['role_id'];
    		$this->assign("ifrole$rid","checked");
    	}
    	
		$this->assign('roleList',$roleList);
    	$this->assign('departmentList',$departmentList);
		$this->assign('user',$user);
    	$this->assign('act','编辑');
        $this->display();
    }
	
	/**
     +----------------------------------------------------------
     * 编辑用户密码
     +----------------------------------------------------------
    */
    public function editpwd()
    {
		$id=$_SESSION['admin']['id'];
    	$TheObj=D('User');
        if($_POST['id']){
			if($TheObj->Create()){
				if($_POST['password1']==""){
	  		
				}else{
					if($_POST['password1']==$_POST['password2']){
						$TheObj->password = md5($_POST['password1']);
						$TheObj->save();
		        		$this->redirect('User/index');
	  				}else{
	  		     		echo "<script>alert('密码不一致');history.go(-1);</script>";
	  				}
	  			}
			}else{
				$err=$TheObj->getError();
				echo "<script>alert('".$err."');history.go(-1);</script>";
			}
        }
		
		$this->assign('id',$id);
        $this->display();
    }
    
	/**
     +----------------------------------------------------------
     * 添加用户数据处理
     +----------------------------------------------------------
    **/
    public function addaction()
    {
		global $privilege;
		$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_add");
		
		$TheObj =D('User');//实例化模型类
		$TheObj->addUser();
		$role_user=new Role_userModel();
		if($TheObj->Create()){
			$user_id = $TheObj->add();
			$role=$_POST['role'];
			$data['user_id']=$user_id;
			foreach ($role as $value){
				$data['role_id']=$value;
				$role_user->addRole_User($value, $user_id);
			}
			$this->redirect('User/index');
		}else{
			$err=$TheObj->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}  
	}

    /**
     +----------------------------------------------------------
     * 编辑用户数据处理
     +----------------------------------------------------------
    **/
    public function editaction()
    {
		global $privilege;
		$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_edit");
		
		$TheObj =D('User');//实例化模型类
		$TheObj->editUser();
		$role_user=new Role_userModel();
		if($TheObj->Create()){
			if($_POST['password1']==""){
	  		
			}else{
				$TheObj->password = md5($_POST['password1']);
			}
			$TheObj->save();
			$user_id=$_POST['id'];
			$role=$_POST['role'];
			$data['user_id']=$user_id;
			$role_user->where("user_id=$user_id")->delete();
			foreach ($role as $value){
				$data['role_id']=$value;
				$role_user->addRole_User($value, $user_id);
			}
			
			$this->redirect('User/index');
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
		
		$TheObj =D('User');//实例化模型类
		$TheObj->Create();
		$id=$_REQUEST['id'];
		$ids=$_REQUEST['ids'];
		if($id){
			$TheObj->delete($id);
		}
		if($ids){
			$TheObj->where("id in($ids)")->delete();
		}
	  
		$this->redirect('User/index');
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