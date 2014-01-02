<?php
/**
 * 后台控制器文件
 * @author hansing
 * 杂志内容管理
 * 20130922
**/
session_start();
class MagazineAction extends Action
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
		
		global $artlist,$magazine,$privilege;
		$artlist = M("article")->field("aid,title")->where("cid = 27")->order("aid desc")->select();
		$magazine = M('magazine');
		$privilege=new PrivilegeModel('role');
    }  
	
    /**
     +----------------------------------------------------------
     * 默认操作
     +----------------------------------------------------------
    **/
    public function index()
    {
		global $magazine,$artlist,$privilege;
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
    	    	$where.=" AND (title like('%".$key."%'))";
    		}else{
    			$where.="(title like('%".$key."%'))";	
    		}
    	}
    	$_SESSION['where']=$where;
    	$count=$magazine->where($where)->count();
    	$p=new Page($count, 20);
    	
    	$magazinelist=$magazine->where($where)->order("id desc")->limit($p->firstRow.','.$p->listRows)->select();
    	$page = $p->show ();
	    
		$this->assign( "page", $page);
    	$this->assign('magazinelist',$magazinelist);
    	$this->assign('artlist',$artlist);
	    $this->display();
    }
    
    /**
     +----------------------------------------------------------
     * 添加杂志图片
     +----------------------------------------------------------
    **/
    public function add()
    {
		global $artlist,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
    	$this->assign('act','添加');
    	$this->assign('action','addaction');
    	$this->assign('artlist',$artlist);
        $this->display();
    }

	/**
     +----------------------------------------------------------
     * 编辑杂志图片
     +----------------------------------------------------------
    **/
    public function edit()
    {
    	global $magazine,$artlist,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
		
		$id = $_GET['id'];
    	$magazineList = $magazine->where("id = $id")->find();
		
		$aid = $magazineList['aid'];
		$a_name = M("article")->where("aid = $aid")->getField("title");
    	
		$this->assign('a_name',$a_name);
		$this->assign('magazine',$magazineList);
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
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_add");
		
		if($_FILES['postpic']['name']){
			import('ORG.Net.UploadFile');
        	$upload = new UploadFile();
        	//设置上传文件大小
        	$upload->maxSize = 3292200;
        	//设置上传文件类型
        	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        	$upload->savePath = 'Public/Uploads/maga/large/'; // 设置附件上传目录
			$upload->thumbRemoveOrigin = true;
        	$upload->saveRule = 'time'; // 附件命名
        	$upload->thumb = true; // 生成缩略图
        	$upload->thumbMaxWidth = '1000'; // 缩略图最大宽度 
        	$upload->thumbMaxHeight = '1288'; // 缩略图最大高度 
        	$upload->thumbPrefix = ''; // 缩略图前缀 
        	$upload->thumbPath = 'Public/Uploads/maga/'.$_POST['aid']."/"; // 缩略图路径 
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
            	$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
            	$info = $upload->getUploadFileInfo(); 
			} 
		}
		global $magazine;//实例化模型类
		$aid = $_POST['aid'];
		$validate = array(
			array('title','require','标题不能为空！',1),
		);
		$magazine->setProperty('_validate',$validate);
		if($magazine->Create()){
			if($info[0]["savename"]){
				$magazine->path=$info[0]["thumbPath"].$info[0]["savename"];
			}
			$magazine->add();
			$pic_arr = $magazine->where("aid=$aid")->order("seq asc,id asc")->select();
			// create doctype 
$dom = new DOMDocument("1.0"); 
// display document in browser as plain text 
// for readability purposes 
header("Content-Type: text/plain"); 
// create root element 
$root = $dom->createElement("content"); 
$dom->appendChild($root); 
// create attribute node 
$width = $dom->createAttribute("width"); 
$root->appendChild($width); 
// create attribute value node 
$widthValue = $dom->createTextNode("1000"); 
$width->appendChild($widthValue); 
// create attribute node 
$h = $dom->createAttribute("height"); 
$root->appendChild($h); 
// create attribute value node 
$hValue = $dom->createTextNode("1288"); 
$h->appendChild($hValue); 
// create attribute node 
$bg = $dom->createAttribute("bgcolor"); 
$root->appendChild($bg); 
// create attribute value node 
$bgValue = $dom->createTextNode("cccccc"); 
$bg->appendChild($bgValue); 
// create attribute node 
$l = $dom->createAttribute("loadercolor"); 
$root->appendChild($l); 
// create attribute value node 
$lValue = $dom->createTextNode("ffffff"); 
$l->appendChild($lValue); 
// create attribute node 
$p = $dom->createAttribute("panelcolor"); 
$root->appendChild($p); 
// create attribute value node 
$pValue = $dom->createTextNode("5d5d61"); 
$p->appendChild($pValue); 
// create attribute node 
$ba = $dom->createAttribute("buttoncolor"); 
$root->appendChild($ba); 
// create attribute value node 
$baValue = $dom->createTextNode("5d5d61"); 
$ba->appendChild($baValue); 
// create attribute node 
$t = $dom->createAttribute("textcolor"); 
$root->appendChild($t); 
// create attribute value node 
$tValue = $dom->createTextNode("ffffff"); 
$t->appendChild($tValue); 
foreach($pic_arr as $k=>$v){
$pic = $v['path'];
// create child element 
$page = $dom->createElement("page"); 
$root->appendChild($page); 
// create attribute node 
$src = $dom->createAttribute("src"); 
$page->appendChild($src); 
// create attribute value node 
$srcValue = $dom->createTextNode("$pic"); 
$src->appendChild($srcValue); 
}

// save and display tree 
echo $dom->saveXML(); 
$path = 'Public/Uploads/maga/'.$_POST['aid']."/Pages.xml";
$dom->save("$path");
			
			$this->redirect('Magazine/index');
		}else{
			$err=$magazine->getError();
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
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_edit");
    	
		if(!empty($_FILES['postpic']['name'])){
    		import('ORG.Net.UploadFile');
        	$upload = new UploadFile();
        	//设置上传文件大小
        	$upload->maxSize = 3292200;
        	//设置上传文件类型
        	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        	$upload->savePath = 'Public/Uploads/maga/date_'.date('Y_m_d').'/'; // 设置附件上传目录
        	$upload->saveRule = 'time'; // 附件命名
        	$upload->thumb = true; // 生成缩略图
        	$upload->thumbMaxWidth = '413'; // 缩略图最大宽度 
        	$upload->thumbMaxHeight = '584'; // 缩略图最大高度 
        	$upload->thumbPrefix = ''; // 缩略图前缀
        	$upload->thumbPath = 'Public/Uploads/maga/'; // 缩略图路径 
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
				$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
				$info = $upload->getUploadFileInfo(); 
			} 
    	}
    	global $magazine;
		$validate=array(
			array("title",'require',"标题不能为空！",1),
		);
		$magazine->setProperty('_validate',$validate);
		if($magazine->Create()){
			if($info[0]["savename"]){
				$magazine->path=$info[0]["savename"];
			}
			$magazine->save();
			$this->redirect('Magazine/index');
		}else{
			$err=$magazine->getError();
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
		
		$TheObj =M('magazine');//实例化模型类
		$id=$_REQUEST['id'];
		$ids=$_REQUEST['ids'];
		if($id){
			$TheObj->delete($id);
		}
		if($ids){
			$TheObj->where("id in($ids)")->delete();
		}
		$this->redirect('Magazine/index');
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