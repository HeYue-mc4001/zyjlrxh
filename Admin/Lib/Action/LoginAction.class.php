<?php
/**
 * 后台登录文件
 * @author hansing
 * 20130415
**/
session_start();
class LoginAction extends Action
{
	/**
	  +----------------------------------------------------------
	  * 初始化函数
	  +----------------------------------------------------------
	 **/
	
	public function _initialize()
	{
		header("Content-Type:text/html; charset=UTF-8");
    }  
	
    /**
     +----------------------------------------------------------
     * 登录操作
     +----------------------------------------------------------
    **/
    public function login()
    {
		$admin=$_SESSION['admin'];
		if(!empty($admin)){
			$this->redirect('index/index');
		}
        $this->display();
    }
    
    /**
     +----------------------------------------------------------
     * 登出操作
     +----------------------------------------------------------
    **/
    public function loginout()
    {
		$_SESSION['admin']='';
	    $this->redirect('login');
    }
    
	/**
     +----------------------------------------------------------
     * 登出操作
     +----------------------------------------------------------
    **/
    public function loginpass()
    {
		$yw_musers=$_POST['account'];
	    $yw_mpassword=md5($_POST['password']);
	    $map['account']=$yw_musers;
	    $map['password']=$yw_mpassword;
	
	    $TheObj=new UserModel();
		$admin=$TheObj->where($map)->find();
		if(!empty($admin)){
			$_SESSION['admin']=$admin;
			$TheObj->id=$admin['id'];
			$TheObj->last_login_time=time();
			$TheObj->last_login_ip=get_client_ip();
			$TheObj->save();
			$TheObj->where("id={$admin['id']}")->setInc('login_count'); 
			$this->redirect('index/index');
		}else{
			echo "<script>alert('用户名或密码错误！');history.go(-1);</script>";
		}	
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