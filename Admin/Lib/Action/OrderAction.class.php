<?php
/**
 * 后台控制器文件
 * @author yuzhenhao
 * 文章管理
 * 20130408
**/
session_start();
class OrderAction extends Action
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
		
		global $cate,$order,$cids,$privilege;
		$cate =new CategoryModel();
		$order=new Model('order');
		$privilege=new PrivilegeModel('role');
    }  
	
    /**
     +----------------------------------------------------------
     * 默认操作
     +----------------------------------------------------------
    **/
    public function index()
    {
		global $order,$cate,$cids,$privilege;
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
    	    	$where.=" AND (name like('%".$key."%') or phone like('%".$key."%'))";
    		}else{
    			$where.="(name like('%".$key."%') or phone like('%".$key."%'))";	
    		}
    	}
    	$_SESSION['where']=$where;
    	$count=$order->where($where)->order("id desc")->count();
    	$p=new Page($count, 20);
    	
    	$orderlist=$order->where($where)->order("id desc")->limit($p->firstRow.','.$p->listRows)->select();
    	$page = $p->show ();
		$article = M('article');
		foreach($orderlist as $k=>$v){
			$goods = $v['goods'];
			$good_arr = explode(",",$goods);
			foreach($good_arr as $key=>$value){
				$good = explode("-",$value);
				$mid = $good[0];
				$mname = $article->where("aid = $mid")->getField('title');
				$list[$key]['mname'] = $mname;
				$list[$key]['num'] = $good[1];
			}
			$orderlist[$k]['list'] = $list;
		}
	    
		$this->assign( "page", $page);
    	$this->assign('orderlist',$orderlist);
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