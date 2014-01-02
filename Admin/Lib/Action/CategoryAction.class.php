<?php
/**
 * 后台控制器文件
 * @author hansing
 * 20130410
**/
session_start();
class CategoryAction extends Action
{
	/**
	 +----------------------------------------------------------
	 * 初始化函数
	 +----------------------------------------------------------
	 */
	public function _initialize()
	{
		header("Content-Type:text/html; charset=UTF-8");
	
		$admin=$_SESSION['admin'];
		if(empty($admin)){
			$this->redirect('Login/login');
		    exit();
		}
		
		global $cate,$privilege;
		$cate =new Model('category');
		$privilege=new PrivilegeModel('role');
		global $catelist,$cateoption;
		$catelist="";
		$cateoption="";     
    }  
	
    /**
     +----------------------------------------------------------
     * 默认操作
     +----------------------------------------------------------
    **/
    public function index()
    {
		global $catelist,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$this->getCategoryList();
    	$this->assign('list',$catelist);
	    $this->display();
    }
    
	/**
     +----------------------------------------------------------
     * 获取内容列表
     +----------------------------------------------------------
    **/
    protected function getCategoryList($cid = 0, $level = 0)
    {
		global $cate,$catelist;
	    $category_arr = $cate->where("pcid = $cid")->order("seq,cid")->select();
	    for($lev=0;$lev<$level*2-1;$lev++){
			$level_nbsp .= "&nbsp;&nbsp;";
		}
	    $level++;
		
		$article = M("Article");
		if($category_arr){
			foreach ( $category_arr as $category ){
				$cid = $category['cid'];
				$pcid= $category['pcid'];
				$cname = $category['cname'];
				$category['posts'] = $article->where("cid = $cid")->count();
				if($cate->where("pcid = $cid")->order("seq,cid")->find()){
					$level_icon = "<font style=\"font-size:18px;font-family:wingdings\">1</font>";
				}else{
					$level_icon = "<font style=\"font-size:18px;font-family:wingdings\">0</font>";
				}
				$id="titleof".$cid;
				$class="itemsof".$pcid;
				$nclass="itemsof".$cid;
				if($category['listtpl']==0){
					$liststyle="文字列表";
				}else{
					$liststyle="图文列表";
				}
				$catelist.= "
<tr onMouseOver=\"this.className='relow ".$class."' \" onMouseOut=\"this.className='row ".$class."' \" class=\"row $class \" name=\"$nclass \" id=\"$id\">
    <td height=\"26\" align=\"center\" ><input type=\"checkbox\" name=\"checkbox\" value=\"$cid\" onClick=\"checkDeleteStatus('checkbox')\" /></td>
	<td height=\"26\" ><a href=\"javascript: slide('".$nclass."','".$id."'); \"> " . $level_nbsp . $level_icon. " " . $cname . "</a>&nbsp;&nbsp;(cid: $cid)</td>	
	<td height=\"26\" align=\"center\" >" . $liststyle . "&nbsp;</td>
	<td height=\"26\" align=\"center\" >" . $category['posts'] . "&nbsp;</td>
	<td height=\"26\" align=\"center\" >" . $category['seq'] . "&nbsp;</td>
	<td height=\"26\" align=\"center\"> 
		<a href='__APP__/Category/add/cid/" . $cid . "'>添加子栏目</a> | 
		<a href='__APP__/Category/edit/cid/" . $cid . "'>修改</a> | 
		<a href=\"javascript:doAction('delete'," . $cid . ")\">删除</a></td>
</tr> ";
				$this->getCategoryList ( $cid, $level );
			}
		}
	}    
    
    /**
     +----------------------------------------------------------
     * 添加栏目
     +----------------------------------------------------------
    **/
    public function add()
    {
		global $cateoption,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$pcid=$_REQUEST['cid'];
    	$this->getCategoryOption($pcid);
    	
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
     * 编辑栏目
     +----------------------------------------------------------
    **/
    public function edit()
    {
    	global $cate,$cateoption,$privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
    	
		$cid=$_REQUEST['cid'];
    	$catelist=$cate->where("cid=$cid")->find();
    	$this->getCategoryOption($catelist['pcid']);
    	
		$this->assign('cate',$catelist);
    	$this->assign('cateoption',$cateoption);
    	$this->assign('act','编辑');
    	$this->assign('action','editaction');
        $this->display('add');
    }
    /**
    +----------------------------------------------------------
    * 添加栏目数据处理
    +----------------------------------------------------------
    */
    public function addaction()
    {
		global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_add");

		global $cate;//实例化模型类
		$validate = array(
			array('cname','require','栏目名称不能为空！',1),
			array('module','require','模块代码不能为空！',1),
		);
		$cate->setProperty('_validate',$validate);
		if($cate->Create()){
			$cate->add();
			$this->redirect('Category/index');
		}else{
			$err=$cate->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
    }
    
	/**
     +----------------------------------------------------------
     * 编辑栏目数据处理
     +----------------------------------------------------------
    **/
    public function editaction()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_edit");
        
		$cate=new CategoryModel();
		$validate=array(
      		array("cname",'require',"栏目名称不能为空！",1),
      		array('cid','checkpcid','不能以自己栏目为父栏目',1,'callback',2),
		);
		$cate->setProperty('_validate',$validate);
		if($cate->Create()){
			$cate->save();
			$this->redirect('Category/index');
		}else{
			$err=$cate->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}  
    }
    
	/**
     +----------------------------------------------------------
     * 删除栏目数据处理
     +----------------------------------------------------------
    **/
    public function delete()
    {
    	global $privilege;
    	$privilege->checkPrivilege($_SESSION['admin']['id'], MODULE_NAME."_".ACTION_NAME);
     
		$TheObj =M('category');//实例化模型类
		$id=$_REQUEST['id'];
		$ids=$_REQUEST['ids'];
		if($id){
			$TheObj->delete($id);
		}
		if($ids){
			$TheObj->where("cid in($ids)")->delete();
		}
		$this->redirect('Category/index');
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