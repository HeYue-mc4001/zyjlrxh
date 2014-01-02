<?php
/**
 * 后台控制器文件
 * @author yuzhenhao
 * 培训管理
 * 20130408
**/
session_start();
class TrainAction extends Action
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
		
		global $cate,$train,$cids,$privilege;
		$cate = new CategoryModel();
		$train = new Model('train');
		$privilege=new PrivilegeModel('role');
    }  
	
    /**
     +----------------------------------------------------------
     * 默认操作
     +----------------------------------------------------------
    **/
    public function index()
    {
		global $train,$cate,$cids,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		import("ORG.Util.Page");
        $where="";
        if($_GET['p']){
    		$where=$_SESSION['where'];
    	}else{
    		$where='';
    		$_SESSION['where']='';
    	}
    	if($_POST['cateid']){
    		$cid=$_POST['cateid'];
    		$cids=$cid;
    		$this->getAllCategory($cid);
    		if(!$where){
				$where=" cid in ($cids)";
    		}else{
				$where.=" AND cid in ($cids)";
    		}
    	}
        if($_POST['key']){
    		$key=$_POST['key'];
    		if($where){
    	    	$where.=" AND (title like('%".$key."%') or content like('%".$key."%'))";
    		}else{
    			$where.="(title like('%".$key."%') or content like('%".$key."%'))";	
    		}
    	}
    	$_SESSION['where']=$where;
    	$count = $train->where($where)->order("seq asc,lastpost desc")->count();
    	$p=new Page($count, 20);
    	
    	$trainlist = $train->where($where)->order("seq asc,lastpost desc")->limit($p->firstRow.','.$p->listRows)->select();
    	$page = $p->show ();
	    
		$this->assign( "page", $page);
    	$this->assign('trainlist',$trainlist);
    	$this->assign('cate',$cate);
	    $this->display();
    }
    
	/**
     +----------------------------------------------------------
     * 获取所有子栏目
     +----------------------------------------------------------
    **/
    protected function getAllCategory($cid = 0)
    {
		global $cate,$cids;
		$category_arr = $cate->where("pcid = $cid")->order("seq,cid")->select();
		
		foreach ( $category_arr as $category ){
			if(!$cids){
				$cids.=$category['cid'];		
			}else{
				$cids.=",".$category['cid'];	
			}
			$this->getAllCategory($category['cid']);
		}
    }    
    
    /**
     +----------------------------------------------------------
     * 添加培训项目
     +----------------------------------------------------------
    **/
    public function add()
    {
		global $cateoption,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$this->getCategoryOption(0,3);
    	$this->assign('act','添加');
    	$this->assign('action','addaction');
    	$this->assign('cateoption',$cateoption);
        $this->display();
    }
	
	/**
     +----------------------------------------------------------
     * 获取栏目选择框
     * @param int $pcid 默认父栏目
     * @param int $cid 以该ID为顶层开始显示
     * @param int $level
     +----------------------------------------------------------
    **/
    protected function getCategoryOption($pcid=0,$cid = 0,$level = 0)
    {
		global $cate,$cateoption;
		$category_arr = $cate->where("pcid=$cid")->order("seq,cid")->select();
		for($lev = 0; $lev < $level * 2 - 1; $lev ++) {
			$level_nbsp .= "　";
		}
		if ($level++){
			$level_nbsp .= "┝";
		}
		foreach ( $category_arr as $category ) {
			$cid = $category['cid'];
			$cname = $category['cname'];
			$selected = $pcid==$cid?'selected':'';
			$cateoption.= "<option value=\"".$cid."\" ".$selected.">".$level_nbsp . " " . $cname."</option>\n";
			$this->getCategoryOption($pcid, $cid, $level );
		}
    }
	
	/**
     +----------------------------------------------------------
     * 编辑项目
     +----------------------------------------------------------
    **/
    public function edit()
    {
    	global $train,$cateoption,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$aid=$_GET['aid'];
    	$trainList=$train->where("aid=$aid")->find();
    	$this->getCategoryOption($trainList['cid'],3);
    	
		$this->assign('train',$trainList);
    	$this->assign('cateoption',$cateoption);
    	$this->assign('act','编辑');
    	$this->assign('action','editaction');
        $this->display('add');
	}
   
	/**
     +----------------------------------------------------------
     * 添加项目数据处理
     +----------------------------------------------------------
    */
    public function addaction()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_add");
		
		if($_FILES['postpic']['name']){
			import('ORG.Net.UploadFile');
        	$upload = new UploadFile();
        	//设置上传文件大小
        	$upload->maxSize = 3292200;
        	//设置上传文件类型
        	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        	$upload->savePath = 'Public/Uploads/date_'.date('Y_m_d').'/'; // 设置附件上传目录 
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
            	$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
            	$info = $upload->getUploadFileInfo(); 
			} 
		}
		global $train;//实例化模型类
		$validate = array(
			array('title','require','项目标题不能为空！',1),
		);
		$auto = array(
			array('lastpost','time',1,'function'),
			array('dateline','time',1,'function'),
			array('uid',$_SESSION['admin']['id'],1),
		);
		$train->setProperty('_validate',$validate);
		$train->setProperty('_auto',$auto);
		$_POST['duedate'] = strtotime($_POST['duedate']);
		if($train->Create()){
			if($info[0]["savename"]){
				$train->pic=$info[0]["savepath"].$info[0]["savename"];
			}
			$train->add();
			$this->redirect('Train/index');
		}else{
			$err=$train->getError();
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
    	
		if(!empty($_FILES['postpic']['name'])){
    		import('ORG.Net.UploadFile');
        	$upload = new UploadFile();
        	//设置上传文件大小
        	$upload->maxSize = 3292200;
        	//设置上传文件类型
        	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        	$upload->savePath = 'Public/Uploads/date_'.date('Y_m_d').'/'; // 设置附件上传目录 
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
				$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
				$info = $upload->getUploadFileInfo(); 
			} 
    	}
    	global $train;
		$validate=array(
			array("title",'require',"项目标题不能为空！",1),
		);
		$auto = array(
			array('lastpost','time',2,'function'),
		);
		$train->setProperty('_validate',$validate);
		$train->setProperty('_auto',$auto);
		$_POST['duedate'] = strtotime($_POST['duedate']);
		if($train->Create()){
			if($info[0]["savename"]){
				$train->pic=$info[0]["savepath"].$info[0]["savename"];
			}
			$train->save();
			$this->redirect('Train/index');
		}else{
			$err=$train->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}  
    }
    
	/**
     +----------------------------------------------------------
     * 删除项目数据处理
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
		$this->redirect('Train/index');
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