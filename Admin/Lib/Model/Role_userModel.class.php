<?php 
class Role_userModel extends Model {  
    protected $role;
    protected $user;
    
	// 根据角色id获取用户列表
	public function getUserByRole($roleId)
	{
		$user=M('User');
        $userIdList=$this->field('user_id')->where("role_id = $roleId")->select();
	    foreach ($userIdList as $v){
            $userId[]=$v['user_id'];
        }
        $map['id']=array('in',$userId);
        $userList=$user->where($map)->select();
        return $userList;
	}
	
    // 根据用户id获取角色列表
	public function getRoleByUser($userId)
	{
		$role=M('Role');
        $roleIdList=$this->field('role_id')->where("user_id = $userId")->select();
        foreach ($roleIdList as $v){
            $roleId[]=$v['role_id'];
        }
        //$roleId=array_values($roleIdList);
        $map['id']=array('in',$roleId);
        $roleList=$role->field('name')->where($map)->select();
        foreach($roleList as $v)
        {
        	$rolename[]=$v['name'];
        }
        $userrolename=implode(',', $rolename);
        return $userrolename;
	}
    
	// 增加数据
	public function addRole_User($role,$user)
	{
        // 自动验证设置 
        if(!$this->where("user_id = $user AND role_id = $role")->find()){
        	$data['role_id']=$role;
        	$data['user_id']=$user;
        	$this->add($data);            
        }else{
            throw_exception('改组中已存在该用户');
        }
	}	  
} 
?> 