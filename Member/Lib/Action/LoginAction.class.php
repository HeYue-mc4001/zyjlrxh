<?php
/**
 * 会员控制器文件
 * @author hansing
 * 登录、注册
 * 20130417
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
		global $category;
		$category = M('category');
		$categorylist = $category->where('pcid = 0')->order("seq")->select();
		foreach($categorylist as $key=>$value){
			$pcid = $value['cid'];
			$categorylist[$key]['subcate'] = $category->where("pcid = $pcid")->order("seq")->select();
		}
		
		$this->assign("categorylist",$categorylist);
    } 
	
	/**
     +----------------------------------------------------------
     * 注册页面
     +----------------------------------------------------------
    **/
    public function register()
    {
        $this->display('login:register');
    }
	
	/**
     +----------------------------------------------------------
     * 注册数据处理
     +----------------------------------------------------------
    **/
    public function addregister()
    {
        if($_FILES['postpic']['name']){
			import('ORG.Net.UploadFile');
        	$upload = new UploadFile();
        	//设置上传文件大小
        	$upload->maxSize = 3292200;
        	//设置上传文件类型
        	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        	$upload->savePath = 'Public/member/icon/'; // 设置附件上传目录
			$upload->saveRule = time;
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
            	$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
            	$info = $upload->getUploadFileInfo(); 
			} 
		}
		if($_POST['poro']==2){
			$member = M("personal");//实例化模型类
			$validate = array(
				array('loginname','require','登录账号不能为空！',1),
				array('loginname','','帐号名称已经存在！',0,'unique',1),
				array('realname','require','姓名不能为空！',1),
	    		array('password','require','密码不能为空！',1),
	    		array('password','password1','密码不一致！',3,'confirm'),
	    		array('email','email','邮箱格式不符合要求。',1),
			);
			$auto = array(
				array('parttime','time',1,'function'),
				array('lasttime','time',1,'function'),
				array('ip','get_client_ip',1,'function'),
			);
		}elseif($_POST['poro']==1){
			$member = M("organization");//实例化模型类
			$validate = array(
				array('loginname','require','登录账号不能为空！',1),
				array('loginname','','帐号名称已经存在！',0,'unique',1),
				array('realname','require','企业名称不能为空！',1),
	    		array('password','require','密码不能为空！',1),
	    		array('password','password1','密码不一致！',3,'confirm'),
	    		array('phone','require','固定电话不能为空！',1),
	    		//array('email','email','邮箱格式不符合要求。',1),
			);
			$auto = array(
				array('parttime','time',1,'function'),
				array('lasttime','time',1,'function'),
				array('ip','get_client_ip',1,'function'),
			);		
		}
		$member->setProperty('_validate',$validate);
		$member->setProperty('_auto',$auto);
		if($member->Create()){
			if($info[0]["savename"]){
				$member->icon=$info[0]["savepath"].$info[0]["savename"];
			}
			$member->loginpwd = md5($_POST['password']);
			$member->add();
			$this->redirect('login/login');
		}else{
			$err=$member->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
    }

	/**
     +----------------------------------------------------------
     * 登录操作
     +----------------------------------------------------------
    **/
    public function login()
    {
		$member=$_SESSION['member'];
		if(!empty($member)){
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
		$_SESSION['member']='';
		$_SESSION['poro']='';
		if($url_self = $_GET['url_self']){
	    	redirect($url_self);
		}else{
			$this->redirect('login/login');
		}
    }
    
	/**
     +----------------------------------------------------------
     * 登入操作
     +----------------------------------------------------------
    **/
    public function loginpass()
    {
		$loginname = $_POST['name'];
	    $password = md5($_POST['password']);
	    $map['loginname']=$loginname;
	    $map['loginpwd']=$password;
		if($_POST['poro']==1){
			$TheObj = M("organization");
			$member = $TheObj->where($map)->find();
			if(!empty($member)){
				$_SESSION['member'] = $member;
				$_SESSION['member']['poro'] = 1;
				$TheObj->id = $member['id'];
				$TheObj->lasttime = time();
				$TheObj->ip = get_client_ip();
				$TheObj->save();
				$TheObj->where("id={$member['id']}")->setInc('loginnum'); 
				if($url_self = $_POST['url_self']){
					redirect($url_self);
				}else{
					$this->redirect('index/index');
				}
			}else{
				echo "<script>alert('账户或密码错误！');history.go(-1);</script>";
			}
		}elseif($_POST['poro']==2){
			$TheObj = M("personal");
			$member = $TheObj->where($map)->find();
			if(!empty($member)){
				$_SESSION['member'] = $member;
				$_SESSION['member']['poro'] = 2;
				$TheObj->id = $member['id'];
				$TheObj->lasttime = time();
				$TheObj->ip = get_client_ip();
				$TheObj->save();
				$TheObj->where("id={$member['id']}")->setInc('loginnum'); 
				if($url_self = $_POST['url_self']){
					redirect($url_self);
				}else{
					$this->redirect('index/index');
				}
			}else{
				echo "<script>alert('账户或密码错误！');history.go(-1);</script>";
			}
		}else{
			echo "<script>alert('请选择会员类型！');history.go(-1);</script>";
		}	
	}
}