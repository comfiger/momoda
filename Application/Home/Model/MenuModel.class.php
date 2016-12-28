<?php
/**
 * 菜单管理类
 * comfiger
 * 2016-8-28
 **/
namespace Home\Model;
use Think\Model;
class MenuModel extends Model{
	//定义表名称
	protected $tableName = 'menu';

	/**
	 * 根据菜单ID获取菜单列表
	 * comfiger
	 * 2016-8-28
	 * @param string $menuId 菜单ID列表
	 **/
	public function getMenuList(){
		$where = array('visible' => 1);
		return $this->where($where)->order('parent_id asc')->select();
	}
	
	public function getMenuManageList(){
		return $this->order('menu_id asc')->select();
	}
	
	/**
	 * 根据菜单父级获取菜单信息
	 * @param int $parent_id 父级ID
	 **/
	public function getMenuListByParentId($parent_id=0){
		$where = array('visible' => 1);
		$where['parent_id'] = $parent_id;
		
		return $this->where($where)->order('parent_id asc')->select();
	}
	
	/**
	 * 根据菜单ID获取菜单列表
	 * comfiger
	 * 2016-8-28
	 * @param string $menuId 菜单ID列表
	 **/
	public function getMenuListByRoleId($menuId){
		return $this->where(array('visible' => 1,'menu_id' => array('in', $menuId)))->order('parent_id asc')->select();
	}
	
	/**
	 * 根据菜单ID获取菜单详情
	 * @param int $parent_id 菜单ID
	 *
	 **/
	public function getMenuInfoByMenuId($menuId){
		return $this->where(array('menu_id' => $menuId))->find();
	}
	
	/**
	 * 添加菜单
	 * comfiger
	 * 2016-8-28
	 *
	 * @param int $parent_id 父级菜单
	 * @param string $mname 菜单名
	 **/
	public function addMenu($parent_id, $mname, $action){
		return $this->data(array(
			'menu_name' => $mname,
			'parent_id' => $parent_id,
			'action' => $action
		))->add();
	}
	
	/**
	 * 编辑菜单
	 * comfiger
	 * 2016-8-28
	 *
	 * @param int $menu_id 父级菜单
	 * @param string $mname 菜单名 $roleid, $parent_id, $rolename, $action
	 **/
	public function editMenu($menu_id, $parent_id, $mname, $action){
		return $this->where(array('menu_id' => $menu_id))->save(array(
			'menu_name' => $mname,
			'parent_id' => $parent_id,
			'action' => $action
		));
	}
	
	/**
	 * 修改菜单状态
	 * @param int $visible 状态 1：有效 0：暂停
	 * @param int $menuId 菜单ID
	 **/
	public function editMenuVisibleByMenuId($visible, $menuId){
		return $this->where(array('menu_id' => $menuId))->setField('visible', $visible);
	}
	
	/**
	 * 删除菜单
	 * @param int $menu_id
	 **/
	public function deleteMenu($menu_id){
		return $this->where(array('menu_id' => $menu_id))->delete();
	}
}