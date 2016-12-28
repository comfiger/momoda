<?php
/**
 * 用户管理类
 * comfiger
 * 2016-8-28
 **/
namespace Home\Model;
use Think\Model;
class UsersModel extends Model{
	//定义表名称
	protected $tableName = 'users';
	
	/**
	 * 获取用户列表
	 **/
	public function getUsersList($page){
		return $this->alias('users')
					->join('left join dudu_role as role on users.role_id = role.role_id')
					->join('left join dudu_company as company on users.company_id = company.company_id')
					->field('users.*,role.role_name,company.company_name')
					->where(array('users.visible' => 1))
					->page($page,C('PAGE_SIZE'))
					->select();
	}
	/**
	 * 获取用户列表
	 **/
	public function getUsersCount(){
		return $this->where(array('visible' => 1))->count();
	}
	/**
	 * 获取用户列表
	 **/
	public function getUsersInfoById($userid){
		return $this->where(array('user_id' => $userid, 'visible' => 1))->find();
	}
	
	/**
	 * 添加用户
	 * @param string $username 帐号
	 * @param string $password 密码
	 * @param string $nickname 昵称
	 * @param int $roleid 用户ID
	 **/
	public function addUsersInfo($username, $password, $nickname, $roleid, $company_id){
		return $this->data(array(
			'username' => $username,
			'password' => $password,
			'nickname' => $nickname,
			'role_id' => $roleid,
			'company_id' => $company_id,
			'createtime' => time()
		))->add();
	}
	
	/**
	 * 编辑用户
	 * @param int $userid 用户ID
	 * @param string $data 修改的数据
	 **/
	public function editUserInfo($userid, $data){
		return $this->where(array('user_id' => $userid))->save($data);
	}
	
	/**
	 * 获取用户资料
	 * comfiger
	 * @param string $username
	 **/
	public function getUserInfoByName($username){
		return $this->where(array('username' => $username, 'visible' => 1))->find();
	}
	
	/**
	 * 删除管理员信息
	 * comfiger
	 * @param int $userid
	 **/
	public function deleteUserInfoByUserId($userid){
		return $this->where(array('user_id' => $userid))->setField('visible', 0);
	}
	
}