<?php
/**
 * 会员控制器文件
 * @author hansing
 * 首页
 * 20130416
 * 20130417
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
		global $member;
		$member=$_SESSION['member'];
		$this->assign("member",$member);
		if(empty($member)){
			$this->redirect('login/login');
		}
	}
	
    /**
     +----------------------------------------------------------
     * 默认操作
     +----------------------------------------------------------
    **/
    public function index()
	{		
		$organization = M("organization");
		$personal = M("personal");
		$id = $_SESSION['member']['id'];
		if($_SESSION['member']['poro']==1){
			$member = $organization->where("id = $id")->find();
		}elseif($_SESSION['member']['poro']==2){
			$member = $personal->where("id = $id")->find();
		}
		
		$this->assign("member",$member);
        $this->display('index:index');        
    }
	
/*++++++++++++++++++++++++++++++++++++++++++++++++*/	
/****************microblog__start******************/	
/*++++++++++++++++++++++++++++++++++++++++++++++++*/

	public function mblog()
	{
		$nor[0] = 'normal';
		$nor[1] = 'normal';
		$nor[2] = 'normal';
		$nor[3] = 'normal';
		$non[0] = 'none';
		$non[1] = 'none';
		$non[2] = 'none';
		$non[3] = 'none';
		if($_GET['num']){
			$num = $_GET['num'];
		}else{
			$num = 1;
		}
		$nor[$num] = 'active';
		$non[$num] = '';
		$organization = M("organization");
		$personal = M("personal");
		
		$id = $_SESSION['member']['id'];
		$poro = $_SESSION['member']['poro'];
		
		$microblog = M('microblog');
		$personal = M("personal");
		$organization = M("organization");
		$mblog_new = $microblog->where("bid = 0")->order("id desc")->select();
		foreach($mblog_new as $key=>$value){
			$owner_id = $value['uid'];
			if($value['poro']==1){
				$owner = $organization->where("id = $owner_id")->find();
			}else{
				$owner = $personal->where("id = $owner_id")->find();			
			}
			$mblog_new[$key]['owner'] = $owner;
			
			$bid = $value['id'];
			$comments = $microblog->where("bid = $bid")->order("id asc")->select();
			$com_num = count($comments);
			foreach($comments as $k1=>$v1){
				$com_owner_id = $v1['uid'];
				if($v1['poro']==1){
					$com_owner = $organization->where("id = $com_owner_id")->find();
				}else{
					$com_owner = $personal->where("id = $com_owner_id")->find();			
				}
				$comments[$k1]['owner'] = $com_owner;
			}
			$mblog_new[$key]['comments'] = $comments;
			$mblog_new[$key]['com_num'] = $com_num;
		}
		$mblog_my = $microblog->where("bid = 0 and uid = $id and poro = $poro")->order("id desc")->select();
		$mblog_com = $microblog->where("bid <> 0 and uid = $id and poro = $poro")->order("id desc")->select();
		
		$operation = M("operation");
		$collect = $operation->where("uid = $id and poro = $poro")->getField("collect");
		if($collect){
			$c_arr = explode(",",$collect);
			$c_arr = array_reverse($c_arr,false);
			foreach($c_arr as $k=>$v){
				$id = $v;
				$collect = $microblog->where("id = $id")->find();
				$uid = $collect['uid'];
				if($collect['poro']==1){
					$collect['uname'] = $organization->where("id = $uid")->getField("loginname");
				}elseif($collect['poro']==2){
					$collect['uname'] = $personal->where("id = $uid")->getField("loginname");
				}
				$mblog_col[$k] = $collect;
			}
		}
				
		$this->assign("mblog_new",$mblog_new);		
		$this->assign("mblog_my",$mblog_my);		
		$this->assign("mblog_com",$mblog_com);		
		$this->assign("mblog_col",$mblog_col);
		$this->assign("nor",$nor);
		$this->assign("num",$num);
        $this->display('microblog:index'); 
	}
	
	//发布微博或评论
	public function addmblog()
	{
		if($_POST['num']){
			$num = $_POST['num'];
		}else{
			$num = 1;
		}	
		if($_SESSION['member']){
			$microblog = M("microblog");//实例化模型类
			$validate = array(
				array('comment','require','内容不能为空！',1),
			);
		
			$auto = array(
				array('parttime','time',1,'function'),
			);	
			$microblog->setProperty('_validate',$validate);
			$microblog->setProperty('_auto',$auto);
			if($microblog->Create()){
				$microblog->uid = $_SESSION['member']['id'];
				$microblog->poro = $_SESSION['member']['poro'];
				$microblog->add();				
				$this->redirect('index/mblog?num='.$num);
			}else{
				$err=$microblog->getError();
				echo "<script>alert('".$err."');</script>";
				$this->redirect('index/mblog?num='.$num);
			}
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}	
	
	
	//收藏微博
	public function colmblog()
	{	
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			if($_GET['bid']){
				$bid = $_GET['bid'];
			}else{
				exit("参数错误！");
			}
			$operation = M("operation");//实例化模型类	
			$op_data = $operation->where("uid = $uid and poro = $poro")->find();
			if($op_data){
				if($op_data['collect']){
					$c_arr = explode(",",$op_data['collect']);
					if(in_array($bid,$c_arr)){
						$this->error('您已经收藏过该微博！');
					}else{
						$c_arr[] = $bid;
						$data['collect'] = implode(",",$c_arr);
						$data['id'] = $op_data['id'];
						$operation->data($data)->save();						
					}
				}else{
					$c_arr[] = $bid;
					$data['collect'] = implode(",",$c_arr);
					$data['id'] = $op_data['id'];
					$operation->data($data)->save();
				}
			}else{
				$data['collect'] = $bid;
				$data['uid'] = $uid;
				$data['poro'] = $poro;
				$operation->data($data)->add();
			}
			$this->success("收藏成功！",'mblog?num=1');
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}	
	
	//删除微博或评论
	public function delmblog()
	{
		if($_GET['num']){
			$num = $_GET['num'];
		}else{
			$num = 1;
		}	
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			if($_GET['bid']){
				$bid = $_GET['bid'];
			}else{
				exit("参数错误！");
			}
			$microblog = M("microblog");//实例化模型类	
			$mblog = $microblog->where("id = $bid")->find();
			if($mblog){
				if($mblog['uid']==$uid && $mblog['poro']==$poro){
					$microblog->delete($bid);
					$this->redirect('index/mblog?num='.$num);
				}else{
					echo "<script>alert('您只能删除自己的微博或评论！');</script>";
					$this->redirect('index/mblog?num='.$num);
				}
			}else{
				echo "<script>alert('该微博（评论）不存在或已被删除！');</script>";
				$this->redirect('index/mblog?num='.$num);
			}
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}	
	
	//删除收藏
	public function delcollect()
	{
		if($_GET['num']){
			$num = $_GET['num'];
		}else{
			$num = 1;
		}	
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			if($_GET['bid']){
				$bid = $_GET['bid'];
			}else{
				exit("参数错误！");
			}
			$operation = M("operation");//实例化模型类	
			$op_data = $operation->where("uid = $uid and poro = $poro")->find();
			if($op_data){
				$c_arr = explode(",",$op_data['collect']);
				if(in_array($bid,$c_arr)){					
					$len = count( $c_arr );
					for( $i=0;$i<$len; $i++){
						if( $c_arr[$i] == $bid ){
							unset( $c_arr[$i] );
						}
					}
					$data['collect'] = implode(",",$c_arr);
					$data['id'] = $op_data['id'];
					$operation->data($data)->save();
					echo "<script>alert('删除成功！');</script>";
					$this->redirect('index/mblog?num=4');
				}else{
					echo "<script>alert('您没有收藏该条微博！');</script>";
					$this->redirect('index/mblog?num=4');
				}
			}else{
				echo "<script>alert('您还没有收藏过微博！');</script>";
				$this->redirect('index/mblog?num=4');
			}
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}		
	}
	
/*++++++++++++++++++++++++++++++++++++++++++++++++*/	
/*****************microblog__end*******************/	
/*++++++++++++++++++++++++++++++++++++++++++++++++*/
	
/*++++++++++++++++++++++++++++++++++++++++++++++++*/	
/*****************contacts__start*******************/	
/*++++++++++++++++++++++++++++++++++++++++++++++++*/

	public function contacts()
	{
		if($_GET['num']){
			$num = $_GET['num'];
		}else{
			$num = 1;
		}
		$nor[$num] = 'active';
		$non[$num] = '';
		
		$organization = M("organization");
		$personal = M("personal");
		$contacts = M("contacts");
		
		$org_list = $organization->select();
		$per_list = $personal->select();
		
		$id = $_SESSION['member']['id'];
		$poro = $_SESSION['member']['poro'];
		
		$agree_list1 = $contacts->where("uid1 = $id and poro1 = $poro and status = 3")->select();
		foreach($agree_list1 as $k=>$v){
			$poro2 = $v['poro2'];
			$uid2 = $v['uid2'];
			if($poro2==1){
				$agree_list1[$k]['info'] = $organization->where("id = $uid2")->find();
			}else{
				$agree_list1[$k]['info'] = $personal->where("id = $uid2")->find();
			}			
		}
		
		$agree_list2 = $contacts->where("uid2 = $id and poro2 = $poro and status = 3")->select();
		foreach($agree_list2 as $key=>$va){
			$poro2 = $va['poro1'];
			$uid2 = $va['uid1'];
			if($poro2==1){
				$agree_list2[$key]['info'] = $organization->where("id = $uid2")->find();
			}else{
				$agree_list2[$key]['info'] = $personal->where("id = $uid2")->find();
			}			
		}
		
		$ini_list = $contacts->where("uid1 = $id and poro1 = $poro and status = 1")->select();
		foreach($ini_list as $kk=>$vv){
			$poro2 = $vv['poro2'];
			$uid2 = $vv['uid2'];
			if($poro2==1){
				$ini_list[$kk]['info'] = $organization->where("id = $uid2")->find();
			}else{
				$ini_list[$kk]['info'] = $personal->where("id = $uid2")->find();
			}			
		}
		$ini2_list = $contacts->where("uid2 = $id and poro2 = $poro and status = 2")->select();
		foreach($ini2_list as $kk=>$vv){
			$poro2 = $vv['poro1'];
			$uid2 = $vv['uid1'];
			if($poro2==1){
				$ini2_list[$kk]['info'] = $organization->where("id = $uid2")->find();
			}else{
				$ini2_list[$kk]['info'] = $personal->where("id = $uid2")->find();
			}			
		}
		
		$pas_list = $contacts->where("uid2 = $id and poro2 = $poro and status = 1")->select();
		foreach($pas_list as $k1=>$v1){
			$poro2 = $v1['poro1'];
			$uid2 = $v1['uid1'];
			if($poro2==1){
				$pas_list[$k1]['info'] = $organization->where("id = $uid2")->find();
			}else{
				$pas_list[$k1]['info'] = $personal->where("id = $uid2")->find();
			}			
		}
		$pas2_list = $contacts->where("uid1 = $id and poro1 = $poro and status = 2")->select();
		foreach($pas2_list as $k1=>$v1){
			$poro2 = $v1['poro2'];
			$uid2 = $v1['uid2'];
			if($poro2==1){
				$pas2_list[$k1]['info'] = $organization->where("id = $uid2")->find();
			}else{
				$pas2_list[$k1]['info'] = $personal->where("id = $uid2")->find();
			}			
		}
		
		$bla_list = $contacts->where("uid1 = $id and poro1 = $poro and (status = 4 or status = 7)")->select();
		foreach($bla_list as $kk=>$vv){
			$poro2 = $vv['poro2'];
			$uid2 = $vv['uid2'];
			if($poro2==1){
				$bla_list[$kk]['info'] = $organization->where("id = $uid2")->find();
			}else{
				$bla_list[$kk]['info'] = $personal->where("id = $uid2")->find();
			}			
		}
		$bla2_list = $contacts->where("uid2 = $id and poro2 = $poro and (status = 5 or status = 7)")->select();
		foreach($bla2_list as $kk=>$vv){
			$poro2 = $vv['poro1'];
			$uid2 = $vv['uid1'];
			if($poro2==1){
				$bla2_list[$kk]['info'] = $organization->where("id = $uid2")->find();
			}else{
				$bla2_list[$kk]['info'] = $personal->where("id = $uid2")->find();
			}			
		}
				
		$this->assign("org_list",$org_list);
		$this->assign("per_list",$per_list);
		$this->assign("agree_list1",$agree_list1);
		$this->assign("agree_list2",$agree_list2);
		$this->assign("ini_list",$ini_list);
		$this->assign("pas_list",$pas_list);
		$this->assign("ini2_list",$ini2_list);
		$this->assign("pas2_list",$pas2_list);
		$this->assign("bla_list",$bla_list);
		$this->assign("bla2_list",$bla2_list);
		$this->assign("nor",$nor);
		$this->assign("num",$num);
		$this->display('contacts:index');
	}
	
	//添加关注
	public function addcontact()
	{
		if($_GET['poro']&&$_GET['uid']){
			$uid2 = $_GET['uid'];
			$poro2 = $_GET['poro'];
		}else{
			exit("参数错误！");
		}
		if($_SESSION['member']){
			$uid1 = $_SESSION['member']['id'];
			$poro1 = $_SESSION['member']['poro'];
			if($uid1==$uid2&&$poro1==$poro2){
				$this->error("您不需要关注自己！");
			}else{
				$contacts = M("contacts");//实例化模型类
				$is1 = $contacts->where("uid1=$uid1 and poro1=$poro1 and uid2=$uid2 and poro2=$poro2")->find();
				$is2 = $contacts->where("uid1=$uid2 and poro1=$poro2 and uid2=$uid1 and poro2=$poro1")->find();
				if($is1){
					$status = $is1['status'];
					switch($status){
						case 1:
							$this->error("您已经关注了TA！");
						break;
						case 2:
							$data['id'] = $is1['id'];
							$data['status'] = 3;
							$data['invtime'] = time();
							if($contacts->create($data)){
								$contacts->save();
								$this->success('相互关注，升级为好友关系！','contacts');
							}
						break;
						case 3:
							$this->error("你们已经是好友关系！");
						break;
						case 4:
							$this->error("对方在您的黑名单里，如需关注，请先将其移出黑名单！");
						break;
						case 5:
							$this->error("您在对方的黑名单里，不能添加对TA的关注！");
						break;
						default:
							$data['id'] = $is1['id'];
							$data['status'] = 1;
							$data['invtime'] = time();
							if($contacts->create($data)){
								$contacts->save();
								$this->success('关注操作成功！','contacts');
							}
					}
				}elseif($is2){
					$status = $is2['status'];
					switch($status){
						case 1:
							$data['id'] = $is2['id'];
							$data['status'] = 3;
							$data['reptime'] = time();
							if($contacts->create($data)){
								$contacts->save();
								$this->success('相互关注，升级为好友关系！','contacts');
							}
						break;
						case 2:
							$this->error("您已经关注了TA！");
						break;
						case 3:
							$this->error("你们已经是好友关系！");
						break;
						case 4:
							$this->error("您在对方的黑名单里，不能添加对TA的关注！");
						break;
						case 5:
							$this->error("对方在您的黑名单里，如需关注，请先将其移出黑名单！");
						break;
						default:
							$data['id'] = $is2['id'];
							$data['status'] = 2;
							$data['reptime'] = time();
							if($contacts->create($data)){
								$contacts->save();
								$this->success('关注操作成功！','contacts');
							}
					}
				}else{
					$data['uid1'] = $uid1;
					$data['poro1'] = $poro1;
					$data['uid2'] = $uid2;
					$data['poro2'] = $poro2;
					$data['status'] = 1;
					$data['invtime'] = time();
					if($contacts->create($data)){
						$contacts->add();
						$this->success('关注操作成功！','contacts');
					}else{
						$err=$contacts->getError();
						echo "<script>alert('".$err."');</script>";
						$this->redirect('contacts');
					}
				}
			}
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}
		
	//添加黑名单
	public function addblist()
	{
		if($_GET['poro']&&$_GET['uid']){
			$uid2 = $_GET['uid'];
			$poro2 = $_GET['poro'];
		}else{
			exit("参数错误！");
		}
		if($_SESSION['member']){
			$uid1 = $_SESSION['member']['id'];
			$poro1 = $_SESSION['member']['poro'];
			if($uid1==$uid2&&$poro1==$poro2){
				$this->error("您不需要黑自己吧！");
			}else{
				$contacts = M("contacts");//实例化模型类
				$is1 = $contacts->where("uid1=$uid1 and poro1=$poro1 and uid2=$uid2 and poro2=$poro2")->find();
				$is2 = $contacts->where("uid1=$uid2 and poro1=$poro2 and uid2=$uid1 and poro2=$poro1")->find();
				if($is1){
					$status = $is1['status'];
					switch($status){
						case 4:
							$this->error("TA已经在黑名单里！");
						break;
						case 7:
							$this->error("TA已经在黑名单里！");
						break;
						case 5:
							$data['id'] = $is1['id'];
							$data['status'] = 7;
							$data['invtime'] = time();
							if($contacts->create($data)){
								$contacts->save();
								$this->success('加入黑名单操作成功！','contacts');
							}
						break;
						default:
							$data['id'] = $is1['id'];
							$data['status'] = 4;
							$data['invtime'] = time();
							if($contacts->create($data)){
								$contacts->save();
								$this->success('加入黑名单操作成功！','contacts');
							}
					}
				}elseif($is2){
					$status = $is2['status'];
					switch($status){
						case 5:
							$this->error("TA已经在黑名单里！");
						break;
						case 7:
							$this->error("TA已经在黑名单里！");
						break;
						case 4:
							$data['id'] = $is2['id'];
							$data['status'] = 7;
							$data['reptime'] = time();
							if($contacts->create($data)){
								$contacts->save();
								$this->success('加入黑名单操作成功！','contacts');
							}
						break;
						default:
							$data['id'] = $is2['id'];
							$data['status'] = 5;
							$data['reptime'] = time();
							if($contacts->create($data)){
								$contacts->save();
								$this->success('加入黑名单操作成功！','contacts');
							}
					}
				}else{
					$data['uid1'] = $uid1;
					$data['poro1'] = $poro1;
					$data['uid2'] = $uid2;
					$data['poro2'] = $poro2;
					$data['status'] = 4;
					$data['invtime'] = time();
					if($contacts->create($data)){
						$contacts->add();
						$this->success('加入黑名单操作成功！','contacts');
					}else{
						$err=$contacts->getError();
						echo "<script>alert('".$err."');</script>";
						$this->redirect('contacts');
					}
				}
			}
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}	
	//解除好友、关注、黑名单关系
	public function relieve()
	{
		if($_GET['poro']&&$_GET['uid']){
			$uid2 = $_GET['uid'];
			$poro2 = $_GET['poro'];
		}else{
			exit("参数错误！");
		}
		if($_SESSION['member']){
			$uid1 = $_SESSION['member']['id'];
			$poro1 = $_SESSION['member']['poro'];
			$contacts = M("contacts");//实例化模型类
			$is1 = $contacts->where("uid1=$uid1 and poro1=$poro1 and uid2=$uid2 and poro2=$poro2 and status=3")->find();
			$is2 = $contacts->where("uid1=$uid2 and poro1=$poro2 and uid2=$uid1 and poro2=$poro1 and status=3")->find();
			$is3 = $contacts->where("uid1=$uid1 and poro1=$poro1 and uid2=$uid2 and poro2=$poro2 and status=1")->find();
			$is4 = $contacts->where("uid1=$uid2 and poro1=$poro2 and uid2=$uid1 and poro2=$poro1 and status=2")->find();
			$is5 = $contacts->where("uid1=$uid1 and poro1=$poro1 and uid2=$uid2 and poro2=$poro2 and (status=4 or status=7)")->find();
			$is6 = $contacts->where("uid1=$uid2 and poro1=$poro2 and uid2=$uid1 and poro2=$poro1 and (status=5 or status=7)")->find();
			if($is1){
				$data['id'] = $is1['id'];
				$data['status'] = 2;
				$data['invtime'] = time();
				if($contacts->create($data)){
					$contacts->save();
					$this->success('解除好友关系成功！','contacts?num=2');
				}					
			}elseif($is2){
				$data['id'] = $is2['id'];
				$data['status'] = 1;
				$data['reptime'] = time();
				if($contacts->create($data)){
					$contacts->save();
					$this->success('解除好友关系成功！','contacts?num=2');
				}
			}elseif($is3){
				$data['id'] = $is3['id'];
				$data['status'] = 6;
				$data['invtime'] = time();
				if($contacts->create($data)){
					$contacts->save();
					$this->success('取消关注成功！','contacts?num=2');
				}
			}elseif($is4){
				$data['id'] = $is4['id'];
				$data['status'] = 6;
				$data['reptime'] = time();
				if($contacts->create($data)){
					$contacts->save();
					$this->success('取消关注成功！','contacts?num=2');
				}
			}elseif($is5){
				if($is5['status']==7){
					$data['id'] = $is5['id'];
					$data['status'] = 5;
					$data['invtime'] = time();
					if($contacts->create($data)){
						$contacts->save();
						$this->success('解除黑名单成功！','contacts?num=2');
					}
				}else{
					$data['id'] = $is5['id'];
					$data['status'] = 6;
					$data['invtime'] = time();
					if($contacts->create($data)){
						$contacts->save();
						$this->success('解除黑名单成功！','contacts?num=2');
					}
				}
			}elseif($is6){
				if($is6['status']==7){
					$data['id'] = $is6['id'];
					$data['status'] = 4;
					$data['reptime'] = time();
					if($contacts->create($data)){
						$contacts->save();
						$this->success('解除黑名单成功！','contacts?num=2');
					}
				}else{
					$data['id'] = $is6['id'];
					$data['status'] = 6;
					$data['reptime'] = time();
					if($contacts->create($data)){
						$contacts->save();
						$this->success('解除黑名单成功！','contacts?num=2');
					}
				}
			}else{
				$this->error("你们暂无关系！");
			}			
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}
		
/*++++++++++++++++++++++++++++++++++++++++++++++++*/	
/*****************contacts__end*******************/	
/*++++++++++++++++++++++++++++++++++++++++++++++++*/

		
/*++++++++++++++++++++++++++++++++++++++++++++++++*/	
/*****************circle__start*******************/	
/*++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function circle()
	{
		if($_GET['num']){
			$num = $_GET['num'];
		}else{
			$num = 1;
		}
		
		$organization = M("organization");
		$personal = M("personal");
		$circle = M("circle");
		$leaguers = M("leaguers");
		$circlechat = M("circlechat");
		
		$cir_list = $circle->where("status=1")->select();
		foreach($cir_list as $k=>$v){
			$poro2 = $v['poro'];
			$uid2 = $v['hostid'];
			if($poro2==1){
				$cir_list[$k]['host'] = $organization->where("id = $uid2")->find();
			}else{
				$cir_list[$k]['host'] = $personal->where("id = $uid2")->find();
			}
		}
		
		$id = $_SESSION['member']['id'];
		$poro = $_SESSION['member']['poro'];
		$owner = $circle->where("hostid=$id and poro=$poro")->select();
		$chat1 = $owner;
		foreach($owner as $k=>$v){
			$cid = $v['id'];
			$leag_list = $leaguers->where("cid=$cid and status=4")->select();
			if($leag_list){
				foreach($leag_list as $key=>$value){
					if($value['poro']==1){
						$owner[$k]['mem'][$key] = $organization->where("id=".$value['uid'])->find();
						if($owner[$k]['mem'][$key]){
							$owner[$k]['mem'][$key]['poro'] = 1;
						}
					}else{
						$owner[$k]['mem'][$key] = $personal->where("id=".$value['uid'])->find();
						if($owner[$k]['mem'][$key]){
							$owner[$k]['mem'][$key]['poro'] = 2;
						}
					}
				}
			}
			$app = $leaguers->where("cid=$cid and status=1")->select();
			if($app){
				foreach($app as $key=>$value){
					if($value['poro']==1){
						$owner[$k]['app'][$key] = $organization->where("id=".$value['uid'])->find();
						if($owner[$k]['app'][$key]){
							$owner[$k]['app'][$key]['poro'] = 1;
						}
					}else{
						$owner[$k]['app'][$key] = $personal->where("id=".$value['uid'])->find();
						if($owner[$k]['app'][$key]){
							$owner[$k]['app'][$key]['poro'] = 2;
						}
					}
				}
			}
			$ref = $leaguers->where("cid=$cid and status=3")->select();
			if($ref){
				foreach($ref as $key=>$value){
					if($value['poro']==1){
						$owner[$k]['ref'][$key] = $organization->where("id=".$value['uid'])->find();
						if($owner[$k]['ref'][$key]){
							$owner[$k]['ref'][$key]['poro'] = 1;
						}
					}else{
						$owner[$k]['ref'][$key] = $personal->where("id=".$value['uid'])->find();
						if($owner[$k]['ref'][$key]){
							$owner[$k]['ref'][$key]['poro'] = 2;
						}
					}
				}
			}
			$fir = $leaguers->where("cid=$cid and status=5")->select();
			if($fir){
				foreach($fir as $key=>$value){
					if($value['poro']==1){
						$owner[$k]['fir'][$key] = $organization->where("id=".$value['uid'])->find();
						if($owner[$k]['fir'][$key]){
							$owner[$k]['fir'][$key]['poro'] = 1;
						}
					}else{
						$owner[$k]['fir'][$key] = $personal->where("id=".$value['uid'])->find();
						if($owner[$k]['fir'][$key]){
							$owner[$k]['fir'][$key]['poro'] = 2;
						}
					}
				}
			}
			$bla = $leaguers->where("cid=$cid and status=6")->select();
			if($bla){
				foreach($bla as $key=>$value){
					if($value['poro']==1){
						$owner[$k]['bla'][$key] = $organization->where("id=".$value['uid'])->find();
						if($owner[$k]['bla'][$key]){
							$owner[$k]['bla'][$key]['poro'] = 1;
						}
					}else{
						$owner[$k]['bla'][$key] = $personal->where("id=".$value['uid'])->find();
						if($owner[$k]['bla'][$key]){
							$owner[$k]['bla'][$key]['poro'] = 2;
						}
					}
				}
			}
		}
		$join_list = $leaguers->where("uid=$id and poro=$poro and status=4")->select();
		$chat2 = $join_list;	
		foreach($join_list as $k=>$v){
			$cid = $v['cid'];
			$join_list[$k]['cir'] = $circle->where("id=$cid")->find();
		}
		$app_list = $leaguers->where("uid=$id and poro=$poro and status=1")->select();
		foreach($app_list as $k=>$v){
			$cid = $v['cid'];
			$app_list[$k]['cir'] = $circle->where("id=$cid")->find();
		}
		
		$circlechat = D("circlechat");
		foreach($chat1 as $key=>$value){
			$cid = $value['id'];
			$chat_list = $circlechat->where("cid=$cid")->order("id desc")->top10();
			asort($chat_list);
			foreach($chat_list as $k=>$v){
				$uid2 = $v['uid'];
				if($v['poro']==1){
					$chat_list[$k]['author'] = $organization->where("id = $uid2")->find();
				}else{
					$chat_list[$k]['author'] = $personal->where("id = $uid2")->find();
				}
			}
			$chat1[$key]['chat'] = $chat_list;
		}
		foreach($chat2 as $key=>$value){
			$cid = $value['cid'];
			$chat2[$key] = $circle->field("id,name,pic,date")->where("id=$cid")->find();
			$chat2_list = $circlechat->where("cid=$cid")->order("id desc")->top10();
			asort($chat2_list);
			foreach($chat2_list as $k=>$v){
				$uid2 = $v['uid'];
				if($v['poro']==1){
					$chat2_list[$k]['author'] = $organization->where("id = $uid2")->find();
				}else{
					$chat2_list[$k]['author'] = $personal->where("id = $uid2")->find();
				}
			}
			$chat2[$key]['chat'] = $chat2_list;
		}
		$cid1_list = $circle->where("hostid=$id and poro=$poro and status=1")->select();
		$cid2_list = $leaguers->where("uid=$id and poro=$poro and status=4")->select();
		foreach($cid1_list as $k=>$v){
			$cid_arr[] = $v['id'];
		}
		foreach($cid2_list as $k=>$v){
			$cid_arr[] = $v['cid'];
		}
		$map['cid'] = array('in',$cid_arr);
		$map['tid'] = -1;
		$topic = $circlechat->where($map)->select();
		
		$this->assign("cir_list",$cir_list);
		$this->assign("owner",$owner);
		$this->assign("chat1",$chat1);
		$this->assign("chat2",$chat2);
		$this->assign("topic",$topic);
		$this->assign("join_list",$join_list);
		$this->assign("app_list",$app_list);
		$this->assign("num",$num);
		$this->display('circle:index');
	}
	//创建圈子
	public function addcircle()
	{
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
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
			$circle = M("circle");//实例化模型类
			$validate = array(
				array('name','require','圈子名称不能为空！',1),
				array('name','','圈子名称已经存在！',0,'unique',1),
			);
			$auto = array(
				array('date','time',1,'function'),
			);
			$circle->setProperty('_validate',$validate);
			$circle->setProperty('_auto',$auto);
			if($circle->Create()){
				if($info[0]["savename"]){
					$circle->pic=$info[0]["savepath"].$info[0]["savename"];
				}
				$circle->hostid=$uid;
				$circle->poro=$poro;
				$circle->add();
				$this->redirect('circle');
			}else{
				$err=$circle->getError();
				echo "<script>alert('".$err."');history.go(-1);</script>";
			}
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}	
	//修改圈子信息
	public function editcircle()
	{
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
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
			$circle = M("circle");//实例化模型类
			/*$validate = array(
				array('name','require','圈子名称不能为空！',1),
				array('name','','圈子名称已经存在！',0,'unique',1),
			);
			$auto = array(
				array('date','time',1,'function'),
			);
			$circle->setProperty('_validate',$validate);
			$circle->setProperty('_auto',$auto);*/
			if($circle->Create()){
				if($info[0]["savename"]){
					$circle->pic=$info[0]["savepath"].$info[0]["savename"];
				}
				$circle->hostid=$uid;
				$circle->poro=$poro;
				$circle->save();
				$this->redirect('circle');
			}else{
				$err=$circle->getError();
				echo "<script>alert('".$err."');history.go(-1);</script>";
			}
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}	
	//圈子群聊		
	public function chat()
	{
		$circle = M("circle");//实例化模型类
		$circlechat = D("circlechat");
		$organization = M("organization");
		$personal = M("personal");
		$leaguers =M("leaguers");
		if($_GET['cid']){
			$cid = $_GET['cid'];
			$is = $circle->where("id=$cid and status=1")->find();//圈子是否存在
			if(!$is){
				$this->error("该圈子不存在或尚未通过审核！");
			}
		}else{
			exit("参数错误！");
		}
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			$is1 = $circle->where("id=$cid and hostid=$uid and poro=$poro")->find();
			$is2 = $leaguers->where("cid=$cid and uid=$uid and poro=$poro and status=4")->find();
			if($is1||$is2){
				if($uid==$is['hostid']&&$poro==$is['poro']){
					$this->assign("power","1");
				}
				if($_GET['tid']){
					$tid = $_GET['tid'];
					$topic = $circlechat->where("id=$tid")->find();
					$chat_list = $circlechat->where("cid=$cid and tid=$tid")->select();
					$this->assign("tid",$tid);
					$this->assign("topic",$topic);
				}else{
					$chat_list = $circlechat->where("cid=$cid")->order("id desc")->top10();
					asort($chat_list);
				}
				foreach($chat_list as $k=>$v){
					$uid2 = $v['uid'];
					if($v['poro']==1){
						$chat_list[$k]['author'] = $organization->where("id = $uid2")->find();
					}else{
						$chat_list[$k]['author'] = $personal->where("id = $uid2")->find();
					}
				}
				$this->assign("chat_list",$chat_list);
				$this->assign("cid",$cid);
				$this->display("circle:chat");	
			}else{
				$this->error("您不是该圈子成员或尚未通过审核！","circle");
			}	
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}
	//添加聊天数据		
	public function addchat()
	{
		$cid = $_POST['cid'];
		$tid = $_POST['tid'];
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			$circle = M("circle");
			$leaguers = M("leaguers");
			$is1 = $circle->where("id=$cid and hostid=$uid and poro=$poro")->find();
			$is2 = $leaguers->where("cid=$cid and uid=$uid and poro=$poro and status=4")->find();
			if($is1||$is2){
				$circlechat = M("circlechat");//实例化模型类
				$validate = array(
					array('content','require','内容不能为空！',1),
				);
				$auto = array(
					array('posttime','time',1,'function'),
				);
				$circlechat->setProperty('_validate',$validate);
				$circlechat->setProperty('_auto',$auto);
				if($circlechat->Create()){
					$circlechat->uid=$uid;
					$circlechat->poro=$poro;
					if($tid){
						$circlechat->tid=$tid;
					}
					$circlechat->add();
					$this->redirect('chat?cid='.$cid.'&tid='.$tid);
				}else{
					$err=$circlechat->getError();
					echo "<script>alert('".$err."');history.go(-1);</script>";
				}
			}else{
				$this->error("您不是该圈子成员！","circle");
			}
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}		
	//申请加入圈子		
	public function joincircle()
	{
		$circle = M("circle");//实例化模型类
		$leaguers = M("leaguers");
		if($_GET['cid']){
			$cid = $_GET['cid'];
			$is = $circle->where("id=$cid and status=1")->find();//圈子是否存在
			if(!$is){
				$this->error("该圈子不存在或尚未通过审核！");
			}
		}else{
			exit("参数错误！");
		}
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			$is1 = $leaguers->where("uid=$uid and poro=$poro and cid=$cid")->find();
			if($uid==$is['hostid']&&$poro==$is['poro']){
				$this->error("您是圈子管理员，不用再次加入圈子！");
			}elseif($is1){
				switch($is1['status']){
					case 1:
						$this->error("您已经提交过申请，请耐心等待！");
					break;
					case 4:
						$this->error("您已经是圈子成员！");
					break;
					case 6:
						$this->error("你在圈子黑名单里，不能申请加入该圈子！");
					break;
					default:					
						$data['id'] = $is1['id'];
						$data['status'] = 1;
						$data['joindate'] = time();
						if($leaguers->create($data)){
							$leaguers->save();
							$this->success('你的申请已经提交，等待审核！','circle');
						}	
				}
			}else{
				$data['uid'] = $uid;
				$data['poro'] = $poro;
				$data['cid'] = $cid;
				$data['status'] = 1;
				$data['joindate'] = time();
				if($leaguers->create($data)){
					$leaguers->add();
					$this->success('你的申请已经提交，等待审核！','circle');
				}
			}			
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}	
	//撤销申请或退出圈子		
	public function cancelcircle()
	{
		$circle = M("circle");//实例化模型类
		$leaguers = M("leaguers");
		if($_GET['cid']){
			$cid = $_GET['cid'];
			$is = $circle->where("id=$cid and status=1")->find();//圈子是否存在
			if(!$is){
				$this->error("该圈子不存在或尚未通过审核！");
			}
		}else{
			exit("参数错误！");
		}
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			$is1 = $leaguers->where("uid=$uid and poro=$poro and cid=$cid")->find();
			if($is1){
				switch($is1['status']){
					case 2:
						$this->error("您已经完成了撤销申请或退出圈子操作，请勿重复操作！");
					break;
					case 6:
						$this->error("你在圈子黑名单里，不能进行该操作！");
					break;
					default:					
						$data['id'] = $is1['id'];
						$data['status'] = 2;
						$data['joindate'] = time();
						if($leaguers->create($data)){
							$leaguers->save();
							$this->success('您已经完成了撤销申请或退出圈子操作！','circle');
						}	
				}
			}else{
				$data['uid'] = $uid;
				$data['poro'] = $poro;
				$data['cid'] = $cid;
				$data['status'] = 1;
				$data['joindate'] = time();
				if($leaguers->create($data)){
					$leaguers->add();
					$this->success('你的申请已经提交，等待审核！','circle');
				}
			}			
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}
	//解散圈子
	public function dissolve(){
		$circle = M("circle");//实例化模型类
		$leaguers = M("leaguers");
		if($_GET['cid']){
			$cid = $_GET['cid'];
			$is = $circle->where("id = $cid")->find();//圈子是否存在
			if(!$is){
				$this->error("该圈子不存在或者已经被删除！");
			}
		}else{
			exit("参数错误！");
		}
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			$is1 = $circle->where("hostid=$uid and poro=$poro and id=$cid")->find();
			if($is1){
				$leaguers->where("cid=$cid")->delete();
				$circle->where("uid=$uid and poro=$poro and id=$cid")->delete();
				$this->success('圈子已经解散！','circle');
			}else{
				$this->error("您没有权限解散该圈子！");
			}			
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}
	//转移圈子
	public function divert(){
		$circle = M("circle");//实例化模型类
		$leaguers = M("leaguers");
		if($_GET['cid']&&$_GET['uid']&&$_GET['poro']){
			$cid = $_GET['cid'];
			$is = $circle->where("id = $cid")->find();//圈子是否存在
			if(!$is){
				$this->error("该圈子不存在或者已经被删除！");
			}
			$uid2 = $_GET['uid'];
			$poro2 = $_GET['poro'];
			$is1 = $leaguers->where("uid=$uid2 and poro=$poro2 and cid=$cid and status=4")->find();
			if(!$is1){
				$this->error("圈子只能转移给本圈子成员！");
			}
		}else{
			exit("参数错误！");
		}
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			$is2 = $circle->where("hostid=$uid and poro=$poro and id=$cid")->find();
			if($is2){
				$data['hostid'] = $uid2;
				$data['poro'] = $poro2;
				$leaguers->where("uid=$uid2 and poro=$poro2 and cid=$cid")->delete();
				$circle->where("uid=$uid and poro=$poro and id=$cid")->save($data); ;
				$this->success('圈子已经转移！','circle');
			}else{
				$this->error("您没有权限转移该圈子！");
			}			
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}
	//管理圈子成员
	public function manage()
	{
		$circle = M("circle");//实例化模型类
		$leaguers = M("leaguers");
		if($_GET['cid']&&$_GET['uid']&&$_GET['poro']&&$_GET['t']){
			$cid = $_GET['cid'];
			$is = $circle->where("id=$cid and status=1")->find();//圈子是否存在
			if(!$is){
				$this->error("该圈子不存在或尚未通过审核！");
			}
			$uid2 = $_GET['uid'];
			$poro2 = $_GET['poro'];
			$is1 = $leaguers->where("uid=$uid2 and poro=$poro2 and cid=$cid")->find();
			if(!$is1){
				$this->error("您只能管理本圈子成员！");
			}
		}else{
			exit("参数错误！");
		}
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			$is2 = $circle->where("hostid=$uid and poro=$poro and id=$cid")->find();
			if($is2){
				switch($_GET['t']){
					case 3:
						$data['id'] = $is1['id'];
						$data['status'] = 3;
						$leaguers->save($data);
						$this->success('拒绝申请操作成功！','circle');
					break;
					case 4:
						$data['id'] = $is1['id'];
						$data['status'] = 4;
						$leaguers->save($data);
						$this->success('接受申请操作成功！','circle');
					break;
					case 5:
						$data['id'] = $is1['id'];
						$data['status'] = 5;
						$leaguers->save($data);
						$this->success('踢人操作成功！','circle');
					break;
					case 6:
						$data['id'] = $is1['id'];
						$data['status'] = 6;
						$leaguers->save($data);
						$this->success('加入黑名单操作成功！','circle');
					break;
					case 7:
						$data['id'] = $is1['id'];
						$data['status'] = 7;
						$leaguers->save($data);
						$this->success('移出黑名单操作成功！','circle');
					break;
					default:
						exit("参数错误！");
				}
			}else{
				$this->error("您没有权限管理该圈子！");
			}			
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}
	//设置话题
	public function addtopic()
	{
		$circle = M("circle");//实例化模型类
		$circlechat = M("circlechat");
		if($_GET['tid']){
			$tid = $_GET['tid'];
			$is = $circlechat->where("id=$tid")->find();//圈子是否存在
			if($is['tid']==-1){
				$this->error("该记录已经被设为话题！");
			}
		}else{
			exit("参数错误！");
		}
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			$cid = $is['cid'];
			$is2 = $circle->where("hostid=$uid and poro=$poro and id=$cid")->find();
			if($is2){
				$data['id'] = $tid;
				$data['tid'] = -1;
				$circlechat->save($data); ;
				$this->success('设置话题成功！','circle');
			}else{
				$this->error("您没有权限设置该圈子的话题！");
			}			
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}
/*++++++++++++++++++++++++++++++++++++++++++++++++*/	
/*****************circle__end*******************/	
/*++++++++++++++++++++++++++++++++++++++++++++++++*/

/*++++++++++++++++++++++++++++++++++++++++++++++++*/	
/*****************message__start*******************/	
/*++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function message()
	{
		if($_GET['num']){
			$num = $_GET['num'];
		}else{
			$num = 4;
		}
		$uid = $_SESSION['member']['id'];
		$poro = $_SESSION['member']['poro'];
		$organization = M("organization");
		$personal = M("personal");
		$letter = M("letter");
		if($_GET['sta']==1){
			$let_list = $letter->where("uid1=$uid and poro1=$poro and status=1")->order("id desc")->select();
		}else{
			$let_list = $letter->where("uid1=$uid and poro1=$poro")->order("id desc")->select();
		}
		foreach($let_list as $k=>$v){
			$uid2 =$v['uid2'];
			if($v['poro2']==1){
				$let_list[$k]['mem'] = $organization->field("id,loginname,realname")->where("id=$uid2")->find();
			}else{
				$let_list[$k]['mem'] = $personal->field("id,loginname,realname")->where("id=$uid2")->find();
			}
		}
		
		$this->assign("num",$num);
		$this->assign("let_list",$let_list);
		$this->display("message:index");		
	}
	//查看私信
	public function readletter()
	{
		$uid = $_SESSION['member']['id'];
		$poro = $_SESSION['member']['poro'];
		$organization = M("organization");
		$personal = M("personal");
		$letter = M("letter");
		if($_GET['lid']){
			$lid = $_GET['lid'];
			$letterdata = $letter->where("id=$lid")->find();
			if($uid==$letterdata['uid1']&&$poro==$letterdata['poro1']){
				$uid2 =$letterdata['uid2'];
				if($letterdata['poro2']==1){
					$letterdata['mem'] = $organization->field("id,loginname,realname")->where("id=$uid2")->find();
				}else{
					$letterdata['mem'] = $personal->field("id,loginname,realname")->where("id=$uid2")->find();
				}
				$letter->where("id=$lid")->setField("status","1");
			}else{
				$this->error('您无权限查看此记录！');
			}
		}else{
			exit("参数错误！");
		}
		
		$this->assign("letterdata",$letterdata);
		$this->display("message:letter");		
	}
	//新增私信
	public function addletter()
	{
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			$letter = M("letter");//实例化模型类
			$validate = array(
				array('title','require','主题不能为空！',1),
				array('content','require','内容不能为空！',1),
			);
			$auto = array(
				array('posttime','time',1,'function'),
			);
			$letter->setProperty('_validate',$validate);
			$letter->setProperty('_auto',$auto);
			if($letter->Create()){
				$letter->uid2=$uid;
				$letter->poro2=$poro;
				$letter->add();
				$this->success('回复成功！','readletter?lid='.$_POST['lid']);
			}else{
				$err=$letter->getError();
				echo "<script>alert('".$err."');history.go(-1);</script>";
			}
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}	
	}
	//删除私信
	public function delletter()
	{
		if($_SESSION['member']){
			$uid = $_SESSION['member']['id'];
			$poro = $_SESSION['member']['poro'];
			$letter = M("letter");//实例化模型类
			if($_GET['lid']){
				$lid = $_GET['lid'];
			}else{
				exit("参数错误！");
			}
			$letterdata = $letter->where("id=$lid")->find();
			if($uid==$letterdata['uid1']&&$poro==$letterdata['poro1']){
				$letter->where("id=$lid")->delete();
				$this->success('删除操作成功！','message?num=4');
			}else{
				$this->error("您无权限删除此记录！","message?num=4");
			}
		}else{
			echo "<script>alert('请先登录！');</script>";
			$this->redirect('login/login');
		}
	}
/*++++++++++++++++++++++++++++++++++++++++++++++++*/	
/*****************circle__end*******************/	
/*++++++++++++++++++++++++++++++++++++++++++++++++*/	
	/**
     +----------------------------------------------------------
     * 会员信息数据处理
     +----------------------------------------------------------
    **/
	public function editmemberdata()
	{
		$id = $_SESSION['member']['id'];
		$member = M("member");
		$memberdata = $member->where("id = $id")->find();
		switch($memberdata['gender']){
			case 1:
				$memberdata['checked1'] = 'checked="checked"';
				break;
			case 2:
				$memberdata['checked2'] = 'checked="checked"';
				break;
			default:
				$memberdata['checked0'] = 'checked="checked"';
		}
		if($memberdata['icon']){
			$memberdata['icon'] = '<img src="__ROOT__/'.$memberdata['icon'].'" />';
		}else{
			$memberdata['icon'] = "你还没有上传过头像哦！";
		}
		
		$this->assign("member",$memberdata);
		$this->display('index:editmemberdata');
	}

    /**
     +----------------------------------------------------------
     * 会员信息数据处理
     +----------------------------------------------------------
    **/
    public function submitmemberdata()
	{
		if($_FILES['postpic']['name']){
			import('ORG.Net.UploadFile');
        	$upload = new UploadFile();
        	//设置上传文件大小
        	$upload->maxSize = 3292200;
        	//设置上传文件类型
        	$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        	$upload->savePath = 'Public/Member/icon/'; // 设置附件上传目录
			$upload->saveRule = time;
        	if(!$upload->upload()) { // 上传错误 提示错误信息 
            	$this->error($upload->getErrorMsg()); 
			}else{ // 上传成功 获取上传文件信息 
            	$info = $upload->getUploadFileInfo(); 
			} 
		}
		$member = M("Member");//实例化模型类
		$validate = array(
			array('nickname','require','昵称不能为空！',1),
			array('nickname','','帐号名称已经存在！',0,'unique',1),
			array('realname','require','昵称不能为空！',1),
	    	array('password1','password2','密码不一致！',3,'confirm'),
	    	array('email','email','邮箱格式不符合要求。',1),
		);
		$member->setProperty('_validate',$validate);
		if($member->Create()){
			if($info[0]["savename"]){
				$member->icon=$info[0]["savepath"].$info[0]["savename"];
			}
			if($_POST['password1']){
				$member->password = md5($_POST['password1']);
			}
			$member->save();
			$this->redirect('index/index');
		}else{
			$err=$member->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
	}
}