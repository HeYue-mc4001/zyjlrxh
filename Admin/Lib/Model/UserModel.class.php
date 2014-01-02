<?php 
class UserModel extends Model {  
    
	/*增加用户*/
	public function addUser()
	{
		// 自动填充设置 
        $auto     =     array(  
			// 对password字段在所有情况下使用md5函数处理
        	array('password','md5',3,'function'),
        	// 对用户 在新增时写入当前时间戳
			array('create_time','time',1,'function'),
			array('last_login_time','time',1,'function'),
        	array('update_time','time',1,'function'),
			// 对regip字段在新增时写入用户注册IP地址
        	array('last_login_ip','get_client_ip',1,'function'),
		);
        // 自动验证设置 
        $validate     =     array( 
			array('account','require','账号不能为空！',1), 
        	array('account','','账号已存在！',1,'unique',self::MODEL_BOTH),
	    	array('password','require','密码不能为空！',1),
	    	array('password','password2','密码不一致！',3,'confirm'),
	    	array('email','email','邮箱格式不符合要求。',1),
		);
        $this->setProperty('_auto', $auto);
        $this->setProperty('_validate', $validate);
		}
		
	/*编辑用户*/
	public function editUser()
	{
		// 自动填充设置 
        $auto     =     array(  
			// 对password字段在所有情况下使用md5函数处理
        	array('update_time','time',3,'function'),        
        );
        // 自动验证设置 
        $validate     =     array(
	    	array('password1','password2','密码不一致！',1,'confirm'),
	    	array('email','email','邮箱格式不符合要求。',1),
        );
        $this->setProperty('_auto', $auto);
        $this->setProperty('_validate', $validate);
		}
	
}
?> 