<?php
/**
 * 前台控制器文件
 * @author hansing
 * 20130411
 * 20130416
 * 20130427——楼层索引
**/
class IndexAction extends Action {
	/**
	 +----------------------------------------------------------
	 * 初始化函数
	 +----------------------------------------------------------
	**/
	public function _initialize()
	{
		header("Content-Type:text/html; charset=UTF-8");
		global $noticelist,$category;
		$category = M('category');
		$categorylist = $category->where('pcid = 0')->order("seq")->select();
		foreach($categorylist as $key=>$value){
			$pcid = $value['cid'];
			$categorylist[$key]['subcate'] = $category->where("pcid = $pcid")->order("seq")->select();
		}
		$noticelist = M("notice")->order("seq,id desc")->select();
		
		$this->assign("categorylist",$categorylist);
		$this->assign("noticelist",$noticelist);
    } 
	
	/**
     +----------------------------------------------------------
     * 首页
     +----------------------------------------------------------
    **/
	public function index()
	{
		global $category;
		$article = D('article');
		$tongzhi = $article->where("aid = 7")->getField("content");
		
		
		$info = $this->getAllCategory(1);
		
		$infolist = $article->field("aid,cid,title,dateline")->where("cid in ($info)")->order('aid desc')->top9();
		$infolist = $this->isNew($infolist);
		
		$img_list = $article->field("aid,cid,title,pic")->where("cid in ($info) and pic<>''")->order('aid desc')->top4();
		$info_img = $article->field("aid,cid,title,pic")->where("cid in ($info) and pic<>''")->order('aid desc')->select();
		
		$act = $this->getAllCategory(2);
		
		$actlist = $article->field("aid,cid,title,dateline")->where("cid in ($act)")->order('aid desc')->top9();
		$actlist = $this->isNew($actlist);
		
		$act_img = $article->field("aid,cid,title,pic")->where("cid in ($act) and pic<>''")->order('aid desc')->select();
		
		$li = $this->getAllCategory(4);
		
		$li_list = $article->field("aid,cid,title,dateline")->where("cid in ($li)")->order('aid desc')->top9();
		$li_list = $this->isNew($li_list);
		
		$trade_list = $article->field("aid,cid,title,dateline")->where("cid = 11")->order('aid desc')->top9();
		$trade_list = $this->isNew($trade_list);
		
		$vote_list = $article->field("aid,cid,title,dateline")->where("cid = 31")->order('aid desc')->top9();
		$vote_list = $this->isNew($vote_list);
		
		$train = D("train");
		$tra = $this->getAllCategory(3);
		
		$tra_list = $train->field("aid,cid,title,dateline")->where("cid in ($tra)")->order('aid desc')->top9();
		$tra_list = $this->isNew($tra_list);
		
		$tra_img = $train->field("aid,cid,title,pic")->where("cid in ($tra) and pic<>''")->order('aid desc')->select();
		
		$adv = M("adv");
		$now = time();
		$advdata = $adv->where("position = 1 and cids like('%,1001,%') and dates < $now and (datee > $now or datee = 0)")->order("seq,id desc")->find();
		if(!$advdata){
			$advdata['path'] = "Public/images/adv_deafult.jpg";
			$advdata['link'] = "";
		}
		$advdatas = $adv->where("position = 2 and cids like('%,1001,%') and dates < $now and (datee > $now or datee = 0)")->order("seq,id desc")->select();
		$count = count($advdatas);
		if($count<4){
			$diff = 4 - $count;
			for($j=0;$j<$diff;$j++){
				$advdatas[$count+$j]['path'] = "Public/images/adv_deafult2.jpg";
				$advdatas[$count+$j]['link'] = "";
			}
		}
		
		$video = M("video");
		$videodata = $video->order("seq,id desc")->find();
		
		$this->assign("tz",$tongzhi);
		$this->assign("advdata",$advdata);
		$this->assign("advdatas",$advdatas);
		$this->assign("videodata",$videodata);
		$this->assign("infolist",$infolist);
		$this->assign("img_list",$img_list);
		$this->assign("actlist",$actlist);
		$this->assign("li_list",$li_list);
		$this->assign("trade_list",$trade_list);
		$this->assign("vote_list",$vote_list);
		$this->assign("tra_list",$tra_list);
		$this->assign("info_img",$info_img);
		$this->assign("act_img",$act_img);
		$this->assign("tra_img",$tra_img);
		$this->display("index:index");
    }
	
	/**
     +----------------------------------------------------------
     * 获取所有子栏目
     +----------------------------------------------------------
    **/
    protected function getAllCategory($cid = 0)
    {
		global $category;
		
		$category_arr = $category->where("pcid = $cid")->order("seq,cid")->select();
		$str = $cid;
		foreach ( $category_arr as $pe){
			$str.=",".$pe['cid'];
		}
		return $str;
    } 
	
	/**
     +----------------------------------------------------------
     * 是否是新发表
     +----------------------------------------------------------
    **/
    protected function isNew($art_list_arr)
    {
		$now = time();
		
		foreach($art_list_arr as $key=>$value){
			$diff = $now - $value['dateline'];
			if($diff<2592000){
				$art_list_arr[$key]['is_new'] = '<img src="__PUBLIC__/images/new.gif" />';
			}else{
				$art_list_arr[$key]['is_new'] = '';
			}
		}
		return $art_list_arr;
    }
	
	/**
     +----------------------------------------------------------
     * 公告列表页
     +----------------------------------------------------------
    **/
	public function notices()
	{
		$notice = M("notice");
		$noticelist = $notice->order("seq,id desc")->select();
		
		$this->assign("noticelist",$noticelist);
		$this->display("public:notices");
    }

	/**
     +----------------------------------------------------------
     * 公告内容页
     +----------------------------------------------------------
    **/
	public function notice()
	{

		if($_GET['id']){
			$id = $_GET['id'];
		}else{
			echo "参数错误！";
		}
		
		$notice = M("notice");
		$noticedata = $notice->where("id = $id")->find();
		if(!$noticedata){
			echo "该篇公告不存在或已经被删除！";
		}
		$this->assign("noticedata",$noticedata);
		$this->display("public:notice");
    }



	/**
     +----------------------------------------------------------
     * 视频列表页
     +----------------------------------------------------------
    **/
	public function videos()
	{	
		global $category;
		
		$video = M("video");
		$catelist = $category->field("cid,cname")->where("pcid = 0")->select();
		foreach($catelist as $k=>$v){
			$pcid = $v['cid'];
			$videolist = $video->where("cid = $pcid")->select();
			$sublist = $category->field("cid,cname")->where("pcid = $pcid")->select();
			$catelist[$k]['sub'] = $sublist;
			$catelist[$k]['video'] = $videolist;
			foreach($sublist as $key=>$value){
				$sub_id = $value['cid'];
				$catelist[$k]['sub'][$key]['video'] = $video->where("cid = $sub_id")->select();
			}
		}
		
		//$video = M("video");
		//$videolist = $video->order("seq,id desc")->select();
		
		$this->assign("catelist",$catelist);
		$this->display("public:videos");
    }

	/**
     +----------------------------------------------------------
     * 视频内容页
     +----------------------------------------------------------
    **/
	public function video()
	{

		if($_GET['id']){
			$id = $_GET['id'];
		}else{
			echo "参数错误！";
		}
		
		$video = M("video");
		$videodata = $video->where("id = $id")->find();
		if(!$videodata){
			echo "该视频不存在或已经被删除！";
		}
		$this->assign("videodata",$videodata);
		$this->display("public:video");
    }

	/**
     +----------------------------------------------------------
     * 视频内容页
     +----------------------------------------------------------
    **/
	public function search()
	{
		global $category;
		$article = M('article');
		$train = M('train');
		$key = $_POST["keys"];
		$cid = $_POST['cateid'];
		if($key==""){
			echo "<script>alert('关键词不能为空！');history.go(-1);</script>";
		}else{
			switch ($cid){
				case 1:
					$info = $this->getAllCategory(1);
					$info_list = $article->field("aid,cid,title,dateline")->where("cid in ($info) and title like('%".$key."%')")->order('aid desc')->select();
				break;
				case 2:
					$act = $this->getAllCategory(2);
					$act_list = $article->field("aid,cid,title,dateline")->where("cid in ($act) and title like('%".$key."%')")->order('aid desc')->select();
				break;
				case 3:
					$tra = $this->getAllCategory(3);
					$tra_list = $train->field("aid,cid,title,dateline")->where("cid in ($tra) and title like('%".$key."%')")->order('aid desc')->select();
				break;
				case 4:
					$lis = $this->getAllCategory(4);
					$lis_list = $article->field("aid,cid,title,dateline")->where("cid in ($lis) and title like('%".$key."%')")->order('aid desc')->select();
				break;
				case 5:
					$mag = $this->getAllCategory(5);
					$mag_list = $article->field("aid,cid,title,dateline")->where("cid in ($mag) and title like('%".$key."%')")->order('aid desc')->select();
				break;
				case 6:
					$job = $this->getAllCategory(6);
					$job_list = $article->field("aid,cid,title,dateline")->where("cid in ($job) and title like('%".$key."%')")->order('aid desc')->select();
				break;
				case 8:
					$abo = $this->getAllCategory(8);
				$abo_list = $article->field("aid,cid,title,dateline")->where("cid in ($abo) and title like('%".$key."%')")->order('aid desc')->select();
				break;
				default:
					$info = $this->getAllCategory(1);
					$info_list = $article->field("aid,cid,title,dateline")->where("cid in ($info) and title like('%".$key."%')")->order('aid desc')->select();
				
					$act = $this->getAllCategory(2);
					$act_list = $article->field("aid,cid,title,dateline")->where("cid in ($act) and title like('%".$key."%')")->order('aid desc')->select();
				
					$mag = $this->getAllCategory(5);
					$mag_list = $article->field("aid,cid,title,dateline")->where("cid in ($mag) and title like('%".$key."%')")->order('aid desc')->select();
				
					$lis = $this->getAllCategory(4);
					$lis_list = $article->field("aid,cid,title,dateline")->where("cid in ($lis) and title like('%".$key."%')")->order('aid desc')->select();
				
					$job = $this->getAllCategory(6);
					$job_list = $article->field("aid,cid,title,dateline")->where("cid in ($job) and title like('%".$key."%')")->order('aid desc')->select();
				
					$abo = $this->getAllCategory(8);
					$abo_list = $article->field("aid,cid,title,dateline")->where("cid in ($abo) and title like('%".$key."%')")->order('aid desc')->select();
				
					$tra = $this->getAllCategory(3);
					$tra_list = $train->field("aid,cid,title,dateline")->where("cid in ($tra) and title like('%".$key."%')")->order('aid desc')->select();
			}
		}
		$this->assign("info_list",$info_list);
		$this->assign("mag_list",$mag_list);
		$this->assign("act_list",$act_list);
		$this->assign("lis_list",$lis_list);
		$this->assign("tra_list",$tra_list);
		$this->assign("job_list",$job_list);
		$this->assign("abo_list",$abo_list);
		$this->display("public:search");		
	}

/*************************************************************************/	
/***************留言板*********开始***********************/	
/*************************************************************************/	
    /**
     +----------------------------------------------------------
     * 留言板
     +----------------------------------------------------------
    **/
    public function message()
    {
    	import("ORG.Util.Page");
    	
    	$message = M('message');
		$reply = M('reply');
    	$messagelist = $message->where()->order('id desc')->select();
    	if($messagelist){
			foreach($messagelist as $key => $value){
				if($value['is_checked']==0){
					$messagelist[$key]['theme'] = "请等待审核……";
					$messagelist[$key]['content'] = "请等待审核……";
				}
				$id = $value['id'];
				$replylist = $reply->where("mid = $id")->select();
				if($replylist){
					$messagelist[$key]['reply'] = $replylist;
				}else{
					$messagelist[$key]['reply'][0]['content'] = "暂无回复！";
				}
			}
		}else{
			echo "暂无留言！";
		}
		
    	$this->assign("messagelist",$messagelist);
        $this->display("message:message");
    }
	
	/**
     +----------------------------------------------------------
     * 添加留言
     +----------------------------------------------------------
    **/
    public function addmessage()
    {   	
		$message=M('message');
    	$auto=array(
    		array('createtime','time','1','function'),
    	);
    	$validate = array(
        	array('name','require','姓名不能为空！',1),
			array('theme','require','主题不能为空！',1),
        	//array('email','email','邮箱格式不符合要求。',1),
        	//array('phone','number','电话必须为数字！',1),
        	//array('phone','require','联系电话不能为空！',1),
        	array('content','require','内容不能为空！',1),
		);
        $message->setProperty('_auto', $auto);
        $message->setProperty('_validate', $validate);
		if($message->Create()){
			$message->add();
			$this->redirect("index/message");
		}else{
			$err=$message->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
    }
/*************************************************************************/	
/***************留言板*********结束***********************/	
/*************************************************************************/	

}
?>