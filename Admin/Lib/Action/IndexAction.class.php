<?php
/**
 * 后台控制器文件
 * @author hansing
 * 首页
 * 20130415
 **/
session_start();
class IndexAction extends Action
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
		$this->assign("admin",$admin);
		if(empty($admin)){
			$this->redirect('Login/login');
		}
	}  
	
    /**
     +----------------------------------------------------------
     * 默认操作
     +----------------------------------------------------------
    **/
    public function index()
    {
        $this->display();
    }
    
	/**
     +----------------------------------------------------------
     * TOP操作
     +----------------------------------------------------------
    **/
    public function top()
    {
        $this->display();
    }
    
	/**
     +----------------------------------------------------------
     * left操作
     +----------------------------------------------------------
    **/
    public function left()
    {
        $adminleftlist = M("Adminleft")->where("isshow = 1")->select();
		
		$this->assign("list",$adminleftlist);
		$this->display();
    }
    
	/**
     +----------------------------------------------------------
     * MAIN操作
     +----------------------------------------------------------
    **/
    public function main()
    {
    	$article=M('article');
    	$articlelist=$article->order('lastpost desc')->limit('0,8')->select();
    	$articlenum=$article->count();
    	
		$category=M('category');
    	$categorynum=$category->count();
    	
		$user=M('user');
    	$usernum=$user->count();
    	
		$dbname=C("DB_NAME");
    	$dbinfo=mysql_query("SELECT CONCAT(sum(ROUND(((DATA_LENGTH + INDEX_LENGTH - DATA_FREE) / 1024 / 1024),2)),'MB') AS Size FROM INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA like '$dbname'");
    	$dbinfo=mysql_fetch_array($dbinfo);
    	$dbsize=$dbinfo['Size'];
    	
		$this->assign('articles',$articlelist);
    	$this->assign('articlenum',$articlenum);
    	$this->assign('categorynum',$categorynum);
    	$this->assign('usernum',$usernum);
    	$this->assign('dbsize',$dbsize);
    	
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