<?php
/**
 * 角色管理类
 * comfiger
 * 2016-8-28
 **/
namespace Home\Model;
use Think\Model;
class RoleModel extends Model{
	//定义表名称
	protected $tableName = 'role';
	
	/**
	 * 获取角色列表
	 **/
	public function getRoleList(){
		return $this->where(array('visible' => 1))->select();
	}
	
	/**
	 * 获取角色详情
	 *
	 * @param int $roleid
	 **/
	public function getRoleInfo($roleid){
		return $this->where(array('role_id' => $roleid))->find();
	}
	
	/**
	 * 添加角色
	 * @param string $role_name
	 * @param string $role_list
	 **/
	public function addRoleInfo($role_name, $role_list){
		return $this->data(array(
			'role_name' => $role_name,
			'role_list' => $role_list,
			'createtime' => time()
		))->add();
	}
	
	/**
	 * 编辑角色
	 * @param int $roleid
	 * @param string $role_name
	 * @param string $role_list
	 **/
	public function editRoleInfo($roleid, $role_name, $role_list){
		return $this->where(array('role_id' => $roleid))->save(array(
			'role_name' => $role_name,
			'role_list' => $role_list
		));
	}
	
	public function deleteRoleInfo($roleid){
		return $this->where(array('role_id' => $roleid))->delete();
	}
}