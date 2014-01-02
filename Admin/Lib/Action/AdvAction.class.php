<?php
/**
 * 后台控制器文件
 * @author hansing
 * 幻灯片管理
 * 20130922
**/
session_start();
class AdvAction extends Action
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
		
		global $cate,$adv,$cids,$privilege;
		$cate =new CategoryModel();
		$adv=new Model('adv');
		$privilege=new PrivilegeModel('role');
    }  
	
    /**
     +----------------------------------------------------------
     * 默认操作
     +----------------------------------------------------------
    **/
    public function index()
    {
		global $adv,$cate,$cids,$privilege;
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
    	    	$where.=" AND (title like('%".$key."%'))";
    		}else{
    			$where.="(title like('%".$key."%'))";	
    		}
    	}
    	$_SESSION['where']=$where;
    	$count=$adv->where($where)->count();
    	$p=new Page($count, 20);
    	
    	$advlist=$adv->where($where)->order("seq asc,id desc")->limit($p->firstRow.','.$p->listRows)->select();
    	$page = $p->show ();
		$n = time();
		foreach($advlist as $k=>$value){
			if($value['dates'] && $value['dates']>$n){
				$advlist[$k]['status'] = "未投放";
			}elseif($value['datee'] && $value['datee']<$n){
				$advlist[$k]['status'] = "已过期";
			}else{
				$advlist[$k]['status'] = "投放中";
			}
			if($value['position']==2){
				$advlist[$k]['type'] = "方形";
			}else{
				$advlist[$k]['type'] = "横幅";
			}
		}
	    
		$this->assign( "page", $page);
    	$this->assign('advlist',$advlist);
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
     * 添加文章
     +----------------------------------------------------------
    **/
    public function add()
    {
		global $cate,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$catelist = $cate->where("pcid = 0")->select();
		$pos = $_GET['pos'];
		
    	$this->assign('pos',$pos);
    	$this->assign('act','添加');
    	$this->assign('action','addaction');
    	$this->assign('catelist',$catelist);
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
     * 编辑文章
     +----------------------------------------------------------
    **/
    public function edit()
    {
    	global $adv,$cateoption,$privilege,$cate;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$id=$_GET['id'];
		$pos = $_GET['pos'];
    	$advList=$adv->where("id=$id")->find();
    	$this->getCategoryOption($advList['cid']);
		$catelist = $cate->where("pcid = 0")->select();
		$cid_arr = explode(",",$advList['cids']);
    	
		$this->assign('adv',$advList);
    	$this->assign('cid_arr',$cid_arr);
    	$this->assign('catelist',$catelist);
    	$this->assign('cateoption',$cateoption);
    	$this->assign('pos',$pos);
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
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_add");
		
		if($_FILES['postpic']['name']){
			import('ORG.Net.UploadFile');
        	$upload = new UploadFile();
        	//设置上传文件大小
        	$upload->maxSize = 3292200;
        	//设置上传文件类型
        	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
			$upload->uploadReplace = true;//同名覆盖
        	$upload->savePath = 'Public/Uploads/adv/'; // 设置附件上传目录 
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
            	$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
            	$info = $upload->getUploadFileInfo(); 
			} 
		}
		global $adv;//实例化模型类
		$validate = array(
			array('title','require','标题不能为空！',1),
		);
		$adv->setProperty('_validate',$validate);
		$_POST['dates'] = strtotime($_POST['dates']);
		$_POST['datee'] = strtotime($_POST['datee']);
		if($_POST['datee']<$_POST['dates']){
			echo "<script>alert('广告投放结束时间一定要大于开始时间！');history.go(-1);</script>";
		}else{
			if($adv->Create()){
				if($info[0]["savename"]){
					$adv->path=$info[0]["savepath"].$info[0]["savename"];
				}
				$adv->cids=",".implode(',', $_POST['cid']).",";
				$adv->add();
				$this->redirect('Adv/index');
			}else{
				$err=$adv->getError();
				echo "<script>alert('".$err."');history.go(-1);</script>";
			}
		}
    }
    
	/**
     +----------------------------------------------------------
     * 编辑文章数据处理
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
			$upload->uploadReplace = true;//同名覆盖
        	$upload->savePath = 'Public/Uploads/slide/'; // 设置附件上传目录 
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
				$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
				$info = $upload->getUploadFileInfo(); 
			} 
    	}
    	global $adv;
		$validate=array(
			array("title",'require',"标题不能为空！",1),
		);
		$adv->setProperty('_validate',$validate);
		$_POST['dates'] = strtotime($_POST['dates']);
		$_POST['datee'] = strtotime($_POST['datee']);
		if($_POST['datee']<$_POST['dates']){
			echo "<script>alert('广告投放结束时间一定要大于开始时间！');history.go(-1);</script>";
		}else{
			if($adv->Create()){
				if($info[0]["savename"]){
					$adv->path=$info[0]["savepath"].$info[0]["savename"];
				}
				$adv->cids=",".implode(',', $_POST['cid']).",";
				$adv->save();
				$this->redirect('Adv/index');
			}else{
				$err=$adv->getError();
				echo "<script>alert('".$err."');history.go(-1);</script>";
			}
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
		
		$TheObj =M('adv');//实例化模型类
		$id=$_REQUEST['id'];
		$ids=$_REQUEST['ids'];
		if($id){
			$TheObj->delete($id);
		}
		if($ids){
			$TheObj->where("id in($ids)")->delete();
		}
		$this->redirect('Adv/index');
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