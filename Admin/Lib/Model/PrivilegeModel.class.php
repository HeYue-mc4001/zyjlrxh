<?php 
class PrivilegeModel extends Model {
        
	//验证用户是否拥有该操作权限
	public function checkPrivilege($id,$privilege)
	{
		$roleUser= new Role_userModel();
		$roleIdList=$roleUser->field('role_id')->where("user_id = $id")->select();
		$actionarr=array();
        foreach ($roleIdList as $v){
            $actionlist=$this->where('id='.$v['role_id'])->getField("actionlist");
        	$tmpactionarr=explode(',', $actionlist);
        	$actionarr=array_merge($actionarr,$tmpactionarr);
        	$actionarr=array_unique($actionarr);
        }
        if(in_array($privilege,$actionarr)){
        	return true;
        }else{
        	exit("你没有该操作权限！");        	
        }
	}
}
?> 