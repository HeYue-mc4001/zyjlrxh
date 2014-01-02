<?php
/**
 * 前台控制器文件
 * @author hansing
 * 20130411
 * 20130416
 * 20130427——楼层索引
**/
class MagazineAction extends Action {
	/**
	 +----------------------------------------------------------
	 * 初始化函数
	 +----------------------------------------------------------
	**/
	public function _initialize()
	{
		header("Content-Type:text/html; charset=UTF-8");
		$category = M('category');
		$categorylist = $category->where('pcid = 0')->order("seq")->select();
		foreach($categorylist as $key=>$value){
			$pcid = $value['cid'];
			$categorylist[$key]['subcate'] = $category->where("pcid = $pcid")->order("seq")->select();
		}
		$adv = M("adv");
		$now = time();
		$advdata = $adv->where("position = 1 and cids like('%,5,%') and dates < $now and (datee > $now or datee = 0)")->order("seq,id desc")->find();
		if(!$advdata){
			$advdata['path'] = "Public/images/adv_deafult.jpg";
			$advdata['link'] = "";
		}
		$advdatas = $adv->where("position = 2 and cids like('%,5,%') and dates < $now and (datee > $now or datee = 0)")->order("seq,id desc")->select();
		$count = count($advdatas);
		if($count<4){
			$diff = 4 - $count;
			for($j=0;$j<$diff;$j++){
				$advdatas[$count+$j]['path'] = "Public/images/adv_deafult2.jpg";
				$advdatas[$count+$j]['link'] = "";
			}
		}
		$advbanner = $adv->where("position = 1 and cids like('%,1000,%') and dates < $now and (datee > $now or datee = 0)")->order("seq,id desc")->find();
		if(!$advbanner){
			$advbanner['path'] = "Public/images/adv_deafult3.jpg";
			$advbanner['link'] = "";
		}
		
		$video = M("video");
		$videodata = $video->order("seq,id desc")->find();
		
		
		$this->assign("videodata",$videodata);
		$this->assign("advdata",$advdata);
		$this->assign("advdatas",$advdatas);
		$this->assign("advbanner",$advbanner);
		$this->assign("categorylist",$categorylist);
    } 
	
	/**
     +----------------------------------------------------------
     * 首页
     +----------------------------------------------------------
    **/
	public function index()
	{
		$category = M("category");
		$article = D('article');
		$catelist = $category->field("cid,cname")->where("pcid = 5 and cid <> 28")->order("seq")->select();
		foreach($catelist as $k=>$v){
			$cid = $v['cid'];
			$magas = $article->where("cid = $cid")->top12();
			foreach($magas as $key => $value){
				if(!$value['pic']){
					$default_img = fmod($key,5);
					$magas[$key]['pic'] = "Public/images/".$default_img.".jpg";
					$magas[$key]['istrue'] = '注，图片不符';
				}
			}
			$catelist[$k]['list'] = $magas;
		}
		
		$this->assign("catelist",$catelist);
		$this->display("magazine:index");
    }
	
	/**
     +----------------------------------------------------------
     * 报名页面
     +----------------------------------------------------------
    **/
	public function order()
	{
		$category = M("category");
		$article = D('article');
		$catelist = $category->field("cid,cname")->where("pcid = 5 and cid <> 28")->order("seq")->select();
		foreach($catelist as $k=>$v){
			$cid = $v['cid'];
			$magas = $article->where("cid = $cid")->select();
			foreach($magas as $key => $value){
				if(!$value['pic']){
					$default_img = fmod($key,5);
					$magas[$key]['pic'] = "Public/images/".$default_img.".jpg";
					$magas[$key]['istrue'] = '注，图片不符';
				}
			}
			$catelist[$k]['list'] = $magas;
		}
		
		$this->assign("catelist",$catelist);		
		$this->display("magazine:order");
    }
	
	/**
     +----------------------------------------------------------
     * 订单表单提交
     +----------------------------------------------------------
    **/
    public function addorder()
    {   
		$order=M('Order');
    	$auto=array(
    		array('datetime','time','1','function'),
    	);
    	$validate = array(
        	array('name','require','姓名不能为空！',1),
			array('address','require','地址不能为空！',1),
			array('phone','require','电话不能为空！',1),
			array('phone','number','电话必须是数字！',1),
		);
        $order->setProperty('_auto', $auto);
        $order->setProperty('_validate', $validate);
		if($order->Create()){
			$order->add();
			$this->success('新增成功');
		}else{
			$err=$order->getError();
			echo "<script>alert('".$err."');history.go(-1);</script>";
		}
	}
 	
	/**
     +----------------------------------------------------------
     * 列表页
     +----------------------------------------------------------
    **/
	public function column()
	{

		if($_GET['pcid']){
			$pcid = $_GET['pcid'];
			if($pcid==28){
				$this->redirect('magazine/order');
			}
		}else{
			$pcid = 0;
			//$index = "img";
		}
		
		$category = M("Category");
		$subcategorylist = $category->where("pcid = $pcid")->order("seq asc")->select();
		$listtpl = $category->where("cid = $pcid")->getField("listtpl");
		if($listtpl){
			$index = "img";
			foreach($subcategorylist as $key => $value){
				if(!$value['pic']){
					$default_img = fmod($key,5);
					$subcategorylist[$key]['pic'] = "Public/images/".$default_img.".jpg";
				}
			}
		}
		
		$back = $category->where("cid = $pcid")->getField("pcid");
		if(is_null($back)){
			$back = "<a class=\"back\" href=\"__ROOT__/\">返回</a>";
		}else{
			$back = "<a class=\"back\" href=\"__ROOT__/index.php/index/column?pcid=".$back."\">返回</a>";
		}
		
		$article = M("Article");
		$articlelist = $article->where("cid = $pcid")->select();
		//$is_single = count($articlelist);
		//if($listtpl){
			foreach($articlelist as $key => $value){
				if(!$value['pic']){
					$default_img = fmod($key,5);
					$articlelist[$key]['pic'] = "Public/images/".$default_img.".jpg";
					$articlelist[$key]['istrue'] = '注，图片不符';
				}
			}
		//}
		
		if($subcategorylist){
			if($articlelist){
				$index = "mix".$index;
			}else{
				$index = "category".$index;
			}
		}else{
			if($articlelist){
				if($is_single==1){
					$this->redirect('index/content?aid='.$articlelist[0]['aid']);
				}else{
					$index = "article".$index;
				}
			}else{
				$index = "nothing";
			}
		}
		$cate_title = $category->field("cname,pcid")->where("cid = $pcid")->find();
		$p_pcid = $cate_title['pcid'];
		$p_cate_title = $category->where("cid = $p_pcid")->getField("cname");
		
		$this->assign("cate_title",$cate_title);
		$this->assign("p_cate_title",$p_cate_title);
		$this->assign("back",$back);
		$this->assign("subcategorylist",$subcategorylist);
		$this->assign("articlelist",$articlelist);
		$this->display("magazine:article");
    }
	
	/**
     +----------------------------------------------------------
     * 列表页
     +----------------------------------------------------------
    **/
	public function content()
	{

		if($_GET['aid']){
			$aid = $_GET['aid'];
		}else{
			echo "参数错误！";
		}
		
		$article = M("Article");
		$articlecontent = $article->where("aid = $aid")->find();
		if(!$articlecontent){
			echo "该篇文章不存在或已经被删除！";
		}
		$this->assign("articlecontent",$articlecontent);
		$this->display("content:content");
    }

	/**
     +----------------------------------------------------------
     * 杂志内容页
     +----------------------------------------------------------
    **/
	public function pageflash()
	{

		if($_GET['aid']){
			$aid = $_GET['aid'];
		}else{
			echo "参数错误！";
		}
		
		$magazine = M("magazine");
		$magacon = $magazine->where("aid = $aid")->select();
		if(!$magacon){
			echo "该杂志暂无内容！";
		}
		$this->assign("magacon",$magacon);
		$this->display("magazine:pageflash");
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

/*************************************************************************/	
/***************首字母检索*********开始***********************/	
/*************************************************************************/	

	/**
    +----------------------------------------------------------
    * 汉字字符串首字母
    +----------------------------------------------------------
    */
	public function acronym()
	{
		$acronym = $_GET['acr'];
		$directory = M('Directory');
		$dir_result = $directory->where("acronym like('%".$acronym."%')")->select();
		foreach($dir_result as $key => $value){
			if(!$value['icon']){
				$default_img = fmod($key,5);
				$dir_result[$key]['icon'] = "Public/images/".$default_img.".jpg";
			}
		}
		
		$this->assign("result",$dir_result);
		$this->display('acronym:acronym');
	}

/*************************************************************************/	
/***************首字母检索*********结束***********************/	
/*************************************************************************/	

/*************************************************************************/	
/****************楼层索引*********开始***********************/	
/*************************************************************************/	

	/**
     +----------------------------------------------------------
     * 列表页
     +----------------------------------------------------------
    **/
	public function directory()
	{

		$building = M("building");
		$directory = M("directory");
		$index = "img";
		if($_GET['pbid']){
			$pbid = $_GET['pbid'];
			$buildingdata = $building->where("bid = $pbid")->find();
			if(!$buildingdata){
				echo "参数无效！";
				exit();
			}
			$subbuildinglist = $building->where("pbid = $pbid")->order("seq asc,bid asc")->select();
			if($subbuildinglist){
				foreach($subbuildinglist as $value){
					if($subbids){
						$subbids .= ",".$value['bid'];
					}else{
						$subbids = $value['bid'];
					}
				}
				$non_penult = $building->where("pbid in ($subbids)")->find();
				if($non_penult){echo $subbids;
					if($buildingdata['listtpl']){
						$index = "nonpenultimg";
						foreach($subbuildinglist as $key=>$value){
							if(!$value['pic']){
								$default_img = fmod($key,5);
								$subbuildinglist[$key]['pic'] = "Public/images/".$default_img.".jpg";
							}
						}
					}else{
						$index = "nonpenult";
					}
				}else{
					$index = "penult";
					$this->assign("buildingdata",$buildingdata);
				}
			}else{
				$this->redirect('index/directory?pbid='.$buildingdata['pbid']);
			}
		}else{
			$pbid = 0;
			$subbuildinglist = $building->where("pbid = 0")->order("seq asc,bid asc")->select();
			$index = "nonpenult".$index; 
		}
		
		$back = $building->where("bid = $pbid")->getField("pbid");
		if(is_null($back)){
			$back = "<a class=\"back\" href=\"__ROOT__/\">返回</a>";
		}else{
			$back = "<a class=\"back\" href=\"__ROOT__/index.php/index/directory?pbid=".$back."\">返回</a>";
		}
		
		$this->assign("back",$back);
		$this->assign("subbuildinglist",$subbuildinglist);
		$this->display("directory:$index");
    }
	
	/**
     +----------------------------------------------------------
     * 获取对应索引
     +----------------------------------------------------------
    **/
	public function getdirectory() 
	{
		$directory = M("directory");
		$bid = $_REQUEST['bid'];
		$directorylist = $directory->where("bid = $bid")->order("rank asc,id asc")->select();
		if($directorylist){
			foreach($directorylist as $key=>$value){
				if(!$value['icon']){
					$directorylist[$key]['icon'] = 'Public/images/nopic.png';
				}
				if(!$value['description']){
					$directorylist[$key]['description'] = '暂无描述';
				}
			}
		}else{
			$directorylist[0]['name'] = '暂无名称';
			$directorylist[0]['roomnumber'] = '暂无房间号';
			$directorylist[0]['description'] = '暂无描述';
			$directorylist[0]['icon'] = 'Public/images/nopic.png';
		}
		$this->ajaxReturn($directorylist,'数据保存成功！',1);
	}
/*************************************************************************/	
/****************楼层索引*********结束***********************/	
/*************************************************************************/	

/*************************************************************************/	
/***************周边*********开始***********************/	
/*************************************************************************/	
	
	/**
     +----------------------------------------------------------
     * 搜狗搜索服务API
     +----------------------------------------------------------
    **/
	public function perimeter()
	{	
		$option = M("option");
		$arr = $option->where("id = 1")->getField("arr");
		
		$perimeter = M("perimeter");
		$classids = $perimeter->field("cid,name,icon")->where("id in ($arr)")->order("seq asc")->select();
		
		$bus = M("bus");
		$destinations = $bus->field("id,destination")->where("status = 1")->order("seq asc")->select();
		
		$url = "http://api.go2map.com/engine/api/search/";
		$output = "json?";
		$range = "&range=";
		$center = "center:13373093,3516875:500:1";
		$what = "what=";
		$contenttype = "&contenttype=utf-8";
		$item_per_page = ",10";
		if($_GET['id']){
			$parameters = "classid:".$_GET['id'];
			if($_GET['p']){
				$parameters .= "&pageinfo=".$_GET['p'].$item_per_page;
			}
			$id = "id=".$_GET['cid'];
			$this->assign("cork",$id);
		}elseif($_GET['kw']){
			$parameters = "keyword:".$_GET['kw'];
			if($_GET['p']){
				$parameters .= "&pageinfo=".$_GET['p'].$item_per_page;
			}
			$kw = "kw=".$_GET['kw'];
			$this->assign("cork",$kw);
		}else{
			$parameters = "";
		}
		
		$parameters = $parameters.$range.$center.$contenttype;
		$http = $url.$output.$what.$parameters;
		$return = file_get_contents($http);
		
		$this->assign("classids",$classids);
		$this->assign("destinations",$destinations);
		$this->assign("obj",$return);
		$this->display("map:perimeter");
	}

/*************************************************************************/	
/****************周边*********结束***********************/	
/*************************************************************************/		


/*************************************************************************/	
/***************公交线路*********开始***********************/	
/*************************************************************************/	
	
	/**
     +----------------------------------------------------------
     * 搜狗公交服务API
     +----------------------------------------------------------
    **/
	public function bus() 
	{
		$option = M("option");
		$arr = $option->where("id = 1")->getField("arr");
		
		$perimeter = M("perimeter");
		$classids = $perimeter->field("cid,name,icon")->where("id in ($arr)")->order("seq asc")->select();
		
		$bus = M("bus");
		$destinations = $bus->field("id,destination")->where("status = 1")->order("seq asc")->select();
		
		$id = $_GET['id'];
		if($id){
			$destdata = $bus->field("coord,keyword,destination")->where("id = $id AND status = 1")->find();
			if($destdata){
				if($destdata['coord']){
					$destination = "new sogou.maps.Point(".$destdata['coord'].")";
				}elseif($destdata['keyword']){
					$destination = '"'.$destdata['keyword'].'"';
				}elseif($destdata['destination']){
					$destination = '"'.$destdata['destination'].'"';
				}else{
					echo "<script>alert('参数对应记录无有效值！');history.go(-1);</script>";					
				}
			}else{
				echo "<script>alert('参数错误或无效！');history.go(-1);</script>";
			}
		}else{
			echo "<script>alert('参数不存在！');history.go(-1);</script>";
		}
		
		$this->assign("classids",$classids);
		$this->assign("destinations",$destinations);
		$this->assign("destination",$destination);
		$this->display("map:bus");
	}

/*************************************************************************/	
/****************公交线路*********结束***********************/	
/*************************************************************************/	
}
?>