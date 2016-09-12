<?php
class RbacAction extends CommonAction{
	//用户列表
	public function index(){
		echo 111;
	}
	
	//角色列表
	public function role(){
		//$this->assign('role',$role);
		$this->role = M('role')->select();
		//print_r($role);
		$this->display();
	}
	
	
	//节点列表
	public function node(){
		$field = array('id','name','title','pid');
		$node = M('node')->field($field)->order('sort')->select();
		$this->node = node_merge($node);
		
		$this->display();
	}
	
	
	//添加用户
	public function addUser(){
		$this->role = M('role')->select();
		$this->display();
	}
	
	//添加用户表单
	Public function addUserHandle(){
		$user = array(
		'username'=>I('username'),
		'password'=>I('password','','md5'),
		'logintime'=>time(),
		'loginip'=>get_client_ip()
		);
		
		$role = array();
		
		if($uid = M('user')->add($user)){
			foreach($_POST['role_id'] as $v){
				$role[] = array(
				  'role_id'=>$v,
				  'user_id'=>$uid
				);
			}
			M('role_user')->addAll($role);
			$this->success('添加成功',U('Admin/Rbac/index'));
		}else{
			$this->error('添加失败');
		}
	}
	
	//添加节点
	public function addRole(){
		$this->display();
	}
	
	public function addRoleHandle(){
		if(M('role')->add($_POST)){
			$this->success('添加成功',U('Admin/Rbac/role'));
		}else{
			$this->error('添加失败');
		}
	}
	
	//添加节点
	public function addNode(){
		//$pid = isset($_GET['pid'])?$_GET['pid']:0;
		$this->pid = I('pid',0,'intval');
		$this->level = I('level',1,'intval');
		
		switch($this->level){
			case 1:
				$this->type = '应用';
				break;
			case 2:
				$this->type = '控制器';
				break;
			case 3:
				$this->type = '动作方法';
				break;
		}
		//print_r($_GET);die;
		$this->display();
	}
	
	public function addNodeHandle(){
		
		if(M('node')->add($_POST)){
			$this->success('添加成功',U('Admin/Rbac/node'));
		}else{
			$this->error('添加失败');
		}
	}
	
	public function access(){
		$rid = I('rid',0,'intval');
		$field = array('id','name','title','pid');
		$node = M('node')->order('sort')->field($field)->select();
	
		//原有权限
		$access = M('access')->where(array('role_id'=>$rid))->getField('node_id',true);
		$this->node = node_merge($node,$access);
		
		$this->rid = $rid;
		$this->display();	
	}
	
	public function setAccess(){
	
		$rid = I('rid',0,'intval');
		$db = M('access');
		//清空原权限
		$db->where(array('role_id'=>$rid))->delete();
		
		//组全新权限
		$data = array();
		foreach($_POST['access'] as $v){
			$tmp = explode('_',$v);
			$data[] = array(
				'role_id' => $rid,
				'node_id' => $tmp[0],
				'level' =>$tmp[1]
			);
		}
		
		//插入新权限
		if($db->addAll($data)){
			$this->success('修改成功',U('Admin/Rbac/role'));
			}else{
				$this->error('修改失败');
			}
	}
}
?>