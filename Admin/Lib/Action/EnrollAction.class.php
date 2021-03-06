<?php
/**
 * 后台控制器文件
 * @author yuzhenhao
 * 文章管理
 * 20130408
**/
session_start();
class EnrollAction extends Action
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
		
		global $cate,$enroll,$cids,$privilege;
		$cate =new CategoryModel();
		$enroll=new Model('enroll');
		$privilege=new PrivilegeModel('role');
    }  
	
    /**
     +----------------------------------------------------------
     * 默认操作
     +----------------------------------------------------------
    **/
    public function index()
    {
		global $enroll,$cate,$cids,$privilege;
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
    	    	$where.=" AND (linkman like('%".$key."%') or phone like('%".$key."%'))";
    		}else{
    			$where.="(linkman like('%".$key."%') or phone like('%".$key."%'))";	
    		}
    	}
    	$_SESSION['where']=$where;
    	$count=$enroll->where($where)->order("id desc")->count();
    	$p=new Page($count, 20);
    	
    	$enrolllist=$enroll->where($where)->order("id desc")->limit($p->firstRow.','.$p->listRows)->select();
    	$page = $p->show ();
	    
		$this->assign( "page", $page);
    	$this->assign('enrolllist',$enrolllist);
    	$this->assign('cate',$cate);
	    $this->display();
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