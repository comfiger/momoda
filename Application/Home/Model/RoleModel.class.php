<?php
/**
 * ��ɫ������
 * comfiger
 * 2016-8-28
 **/
namespace Home\Model;
use Think\Model;
class RoleModel extends Model{
	//���������
	protected $tableName = 'role';
	
	/**
	 * ��ȡ��ɫ�б�
	 **/
	public function getRoleList(){
		return $this->where(array('visible' => 1))->select();
	}
	
	/**
	 * ��ȡ��ɫ����
	 *
	 * @param int $roleid
	 **/
	public function getRoleInfo($roleid){
		return $this->where(array('role_id' => $roleid))->find();
	}
	
	/**
	 * ��ӽ�ɫ
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
	 * �༭��ɫ
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