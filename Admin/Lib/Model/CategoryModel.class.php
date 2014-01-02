<?php 
class CategoryModel extends Model {  
    
        
	//验证表单cid和pcid是否相同
	public function checkpcid()
	{
		if($_POST['cid'] == $_POST['pcid']){
			return false;
		}else{
			return true;
		}
	}

    /**
     +----------------------------------------------------------
     * 获取栏目选择框
     * @param int $pcid 默认父栏目
     * @param int $cid 以该ID为顶层开始显示
     * @param int $level
     +----------------------------------------------------------
    **/
    public function getCategoryOption($pcid=0,$cid = 0,$level = 0)
    {
		$category_arr = $this->where("pcid=$cid")->order("seq")->select();
		for($lev = 0; $lev < $level * 2 - 1; $lev ++) {
			$level_nbsp .= "　";
		}
		if($level++){
			$level_nbsp .= "┝";
		}
		foreach($category_arr as $category ){
			$cid = $category['cid'];
			$cname = $category['cname'];
			$selected = $pcid==$cid?'selected':'';
			echo "<option value=\"".$cid."\" ".$selected.">".$level_nbsp . " " . $cname."</option>\n";
			$this->getCategoryOption($pcid, $cid, $level );
		}
    }
}
?> 