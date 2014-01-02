<?php
/**
 * 后台控制器文件
 * @author hansing
 * 幻灯片管理
 * 20130922
**/
session_start();
class EntreAction extends Action
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
		
		global $artlist, $entre, $privilege;
		$artlist = M("article")->field("aid,title")->where("cid = 30")->order("aid desc")->select();
		$entre = M("entre");
		$privilege=new PrivilegeModel('role');
    }  
	
    /**
     +----------------------------------------------------------
     * 默认操作
     +----------------------------------------------------------
    **/
    public function index()
    {
		global $artlist, $entre, $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		import("ORG.Util.Page");
        $where="";
        if($_GET['p']){
    		$where=$_SESSION['where'];
    	}else{
    		$where='';
    		$_SESSION['where']='';
    	}
    	if($_POST['aid']){
    		$aid=$_POST['aid'];
    		if(!$where){
				$where=" aid = $aid";
    		}else{
				$where.=" AND aid = $aid";
    		}
    	}
        if($_POST['key']){
    		$key=$_POST['key'];
    		if($where){
    	    	$where.=" AND (name like('%".$key."%'))";
    		}else{
    			$where.="(name like('%".$key."%'))";	
    		}
    	}
    	$_SESSION['where']=$where;
    	$count=$entre->where($where)->count();
    	$p=new Page($count, 20);
    	
    	$entrelist=$entre->where($where)->order("seq asc,id desc")->limit($p->firstRow.','.$p->listRows)->select();
    	$page = $p->show ();
		foreach($entrelist as $k=>$v){
			$aid = $v['aid'];
			$entrelist[$k]['a_name'] = M("article")->where("aid = $aid")->getField("title");
		}
	    
		$this->assign( "page", $page);
    	$this->assign('entrelist',$entrelist);
    	$this->assign('artlist',$artlist);
	    $this->display();
    }
    
    /**
     +----------------------------------------------------------
     * 添加企业家
     +----------------------------------------------------------
    **/
    public function add()
    {
		global $artlist, $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
    	$this->assign('act','添加');
    	$this->assign('action','addaction');
    	$this->assign('artlist',$artlist);
        $this->display();
    }
	
	/**
     +----------------------------------------------------------
     * 编辑文章
     +----------------------------------------------------------
    **/
    public function edit()
    {
    	global $artlist,$entre,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$id = $_GET['id'];
    	$entreList = $entre->where("id=$id")->find();
		
		$aid = $entreList['aid'];
		$a_name = M("article")->where("aid = $aid")->getField("title");
    	
		$this->assign('a_name',$a_name);
		$this->assign('entre',$entreList);
    	$this->assign('artlist',$artlist);
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
    	global $entre,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_add");
		
		if($_FILES['postpic']['name']){
			import('ORG.Net.UploadFile');
        	$upload = new UploadFile();
        	//设置上传文件大小
        	$upload->maxSize = 3292200;
        	//设置上传文件类型
        	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        	$upload->savePath = 'Public/Uploads/entre/'; // 设置附件上传目录
			$upload->saveRule = 'time';
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
            	$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
            	$info = $upload->getUploadFileInfo(); 
			} 
		}
		
		$validate = array(
			array('name','require','姓名不能为空！',1),
		);
		$entre->setProperty('_validate',$validate);
		if($entre->Create()){
			if($info[0]["savename"]){
				$entre->pic=$info[0]["savepath"].$info[0]["savename"];
			}
			$entre->add();
			$this->redirect('Entre/index');
		}else{
			$err=$entre->getError();
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
		global $entre,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_edit");
    	
		if(!empty($_FILES['postpic']['name'])){
    		import('ORG.Net.UploadFile');
        	$upload = new UploadFile();
        	//设置上传文件大小
        	$upload->maxSize = 3292200;
        	//设置上传文件类型
        	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        	$upload->savePath = 'Public/Uploads/slide/'; // 设置附件上传目录
			$upload->saveRule = 'time'; 
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
				$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
				$info = $upload->getUploadFileInfo(); 
			} 
    	}
		$validate=array(
			array('name','require','姓名不能为空！',1),
		);
		$entre->setProperty('_validate',$validate);
		if($entre->Create()){
			if($info[0]["savename"]){
				$entre->pic=$info[0]["savepath"].$info[0]["savename"];
			}
			$entre->save();
			$this->redirect('Entre/index');
		}else{
			$err=$entre->getError();
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
		
		$TheObj =M('article');//实例化模型类
		$id=$_REQUEST['id'];
		$ids=$_REQUEST['ids'];
		if($id){
			$TheObj->delete($id);
		}
		if($ids){
			$TheObj->where("aid in($ids)")->delete();
		}
		$this->redirect('Article/index');
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